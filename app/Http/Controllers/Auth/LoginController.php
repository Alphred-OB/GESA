<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\EmailVerificationService;
use App\Services\Auth\LoginOtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct(
        private readonly LoginOtpService $loginOtpService,
        private readonly EmailVerificationService $emailVerificationService,
    ) {
    }

    /**
     * Display the login form.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->safe()->only(['email', 'password']);
        $remember = $request->boolean('remember');
        $guards = ['admin', 'student'];

        foreach ($guards as $guard) {
            $provider = Auth::guard($guard)->getProvider();
            $credentialsWithRole = array_merge($credentials, ['role' => $guard]);
            $user = $provider->retrieveByCredentials($credentialsWithRole);

            if (! $user || ! $provider->validateCredentials($user, $credentials)) {
                continue;
            }

            if (method_exists($user, 'getAttribute')) {
                $role = $user->getAttribute('role');

                if ($role && $role !== $guard) {
                    continue;
                }
            }

            if (method_exists($user, 'getAttribute') && is_null($user->getAttribute('email_verified_at'))) {
                $this->emailVerificationService->send($user);
                $request->session()->put('pending_verification', [
                    'email' => $user->email,
                    'guard' => $guard,
                    'remember' => $remember,
                ]);

                return redirect()->route('auth.verify.notice')
                    ->withErrors(['verification' => __('Please verify your email before signing in. We just sent you a new verification link.')]);
            }

            // Check for PendingRegistration status (for freshers/manual approvals)
            $pendingRegistration = \App\Models\PendingRegistration::where('email', $user->email)->latest()->first();
            if ($pendingRegistration) {
                if ($pendingRegistration->status === 'rejected') {
                    return back()->withErrors([
                        'email' => __('Your account application has been rejected. Please contact the administrator.'),
                    ])->onlyInput('email');
                }
                if ($pendingRegistration->status === 'pending') {
                    return back()->withErrors([
                        'email' => __('Your account is currently pending approval by an administrator.'),
                    ])->onlyInput('email');
                }
            }

            $this->loginOtpService->send($user, $guard);

            $request->session()->put('pending_login_otp', [
                'user_id' => $user->getAuthIdentifier(),
                'guard' => $guard,
                'remember' => $remember,
                'email' => $user->email,
            ]);

            return redirect()->route('auth.login.otp')
                ->with('status', __('We sent a verification code to your email.'));
        }

        return back()
            ->withErrors([
                'email' => __('auth.failed'),
            ])
            ->onlyInput('email');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(): RedirectResponse
    {
        $guard = Auth::getDefaultDriver();
        Auth::guard($guard)->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();
        request()->session()->forget(['pending_login_otp']);

        return redirect()->route('login')
            ->with('status', __('You have been logged out.'));
    }
}
