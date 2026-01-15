<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
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
     * Users can login with email, username, or index number (reference number).
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $identifier = $request->input('identifier');
        $password = $request->input('password');
        $remember = $request->boolean('remember');
        $guards = ['admin', 'student'];

        $userFound = false;
        $emailVerificationEnabled = config('app.email_verification_enabled', false);

        // Find user by email, username, or index_number
        $user = $this->findUserByIdentifier($identifier);

        if ($user) {
            $userFound = true;
            $guard = $user->role === 'admin' ? 'admin' : 'student';

            // Validate password
            if (!\Illuminate\Support\Facades\Hash::check($password, $user->password)) {
                return back()
                    ->withErrors(['identifier' => __('The password you entered is incorrect.')])
                    ->onlyInput('identifier');
            }

            // Check email verification (only if enabled in env)
            if ($emailVerificationEnabled && is_null($user->email_verified_at)) {
                $this->emailVerificationService->send($user);
                $request->session()->put('pending_verification', [
                    'email' => $user->email,
                    'guard' => $guard,
                    'remember' => $remember,
                ]);

                return redirect()->route('auth.verify.notice')
                    ->withErrors(['verification' => __('Please verify your email before signing in. We just sent you a new verification code.')]);
            }

            // Login successfully
            Auth::guard($guard)->login($user, $remember);
            $request->session()->regenerate();

            $dashboard = $guard === 'admin' ? 'admin.dashboard' : 'student.dashboard';

            return redirect()->intended(route($dashboard));
        }

        // User not found - show appropriate error message
        return back()
            ->withErrors([
                'identifier' => __('We could not find an account with that email, username, or reference number.'),
            ])
            ->onlyInput('identifier');
    }

    /**
     * Find a user by email, username, or index_number (reference number).
     */
    private function findUserByIdentifier(string $identifier): ?User
    {
        return User::where('email', $identifier)
            ->orWhere('username', $identifier)
            ->orWhere('index_number', $identifier)
            ->first();
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
