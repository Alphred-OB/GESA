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
        $request->authenticate();

        $identifier = $request->input('identifier');
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        $emailVerificationEnabled = config('app.email_verification_enabled', false);

        // Find user by email, username, or index_number
        $user = $this->findUserByIdentifier($identifier);

        if ($user && \Illuminate\Support\Facades\Hash::check($password, $user->password)) {
            // Clear rate limiter on success
            \Illuminate\Support\Facades\RateLimiter::clear($request->throttleKey());

            $guard = $user->role === 'admin' ? 'admin' : 'student';

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

        // Increment rate limiter on failure
        \Illuminate\Support\Facades\RateLimiter::hit($request->throttleKey());

        // Generic error message for both "user not found" and "wrong password"
        return back()
            ->withErrors([
                'identifier' => __('Invalid credentials. Please check your details and try again.'),
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
