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

        $userFound = false;

        foreach ($guards as $guard) {
            $provider = Auth::guard($guard)->getProvider();
            $credentialsWithRole = array_merge($credentials, ['role' => $guard]);
            $user = $provider->retrieveByCredentials($credentialsWithRole);

            if ($user) {
                $userFound = true; // User exists with this role
                
                if (! $provider->validateCredentials($user, $credentials)) {
                    continue; // Password wrong, try next guard? (Unlikely to match another guard with same email/diff pass, but safe to continue)
                }
            } else {
                continue; // User not found in this guard
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

            // Login OTP disabled - log in directly
            Auth::guard($guard)->login($user, $remember);
            $request->session()->regenerate();

            $dashboard = $guard === 'admin' ? 'admin.dashboard' : 'student.dashboard';

            return redirect()->intended(route($dashboard));
        }

        // If we reached here, login failed. Determine specific error.
        $errorMessage = $userFound 
            ? 'The password you entered is incorrect.' 
            : 'We could not find an account with that email address.';

        return back()
            ->withErrors([
                'email' => $errorMessage,
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
