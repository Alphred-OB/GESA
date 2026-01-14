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
        $emailVerificationEnabled = config('app.email_verification_enabled', false);

        foreach ($guards as $guard) {
            $provider = Auth::guard($guard)->getProvider();
            $credentialsWithRole = array_merge($credentials, ['role' => $guard]);
            $user = $provider->retrieveByCredentials($credentialsWithRole);

            if ($user) {
                $userFound = true; // User exists with this role
                
                if (! $provider->validateCredentials($user, $credentials)) {
                    continue; // Password wrong, try next guard
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

            // Check email verification (only if enabled in env)
            if ($emailVerificationEnabled && method_exists($user, 'getAttribute') && is_null($user->getAttribute('email_verified_at'))) {
                $this->emailVerificationService->send($user);
                $request->session()->put('pending_verification', [
                    'email' => $user->email,
                    'guard' => $guard,
                    'remember' => $remember,
                ]);

                return redirect()->route('auth.verify.notice')
                    ->withErrors(['verification' => __('Please verify your email before signing in. We just sent you a new verification code.')]);
            }

            // Login OTP disabled - log in directly
            Auth::guard($guard)->login($user, $remember);
            $request->session()->regenerate();

            $dashboard = $guard === 'admin' ? 'admin.dashboard' : 'student.dashboard';

            return redirect()->intended(route($dashboard));
        }

        // FIRST: Check if this email exists in pending registrations table
        // This should be checked before "no account found" to provide better UX
        $pendingRegistration = \App\Models\PendingRegistration::where('email', $credentials['email'])->first();
        
        if ($pendingRegistration) {
            // Verify password first
            if (!\Illuminate\Support\Facades\Hash::check($credentials['password'], $pendingRegistration->password)) {
                return back()
                    ->withErrors(['email' => __('The password you entered is incorrect.')])
                    ->onlyInput('email');
            }
            
            // Password correct - check registration status
            switch ($pendingRegistration->status) {
                case 'pending':
                    // Check if email verification is needed
                    if ($emailVerificationEnabled && is_null($pendingRegistration->email_verified_at)) {
                        session(['fresher_pending_id' => $pendingRegistration->id]);
                        return redirect()->route('auth.fresher-register.verify')
                            ->withErrors(['verification' => __('Your email is not verified yet. Please verify your email to complete registration.')]);
                    }
                    
                    // Email verified (or verification disabled) but awaiting admin approval
                    return back()
                        ->withErrors([
                            'email' => __('Your registration is pending admin approval. Please wait for an administrator to review your request. If this is taking too long, please contact the GESA executives.')
                        ])
                        ->onlyInput('email');
                    
                case 'approved':
                    // This shouldn't happen - approved registrations should be in users table
                    // But just in case, tell user to try logging in again
                    return back()
                        ->withErrors([
                            'email' => __('Your registration has been approved! Please try logging in again. If this issue persists, contact the GESA executives.')
                        ])
                        ->onlyInput('email');
                    
                case 'rejected':
                    // Registration was rejected
                    $adminNotes = $pendingRegistration->admin_notes;
                    $message = __('Your registration request was not approved.');
                    if ($adminNotes) {
                        $message .= ' ' . __('Reason: :reason', ['reason' => $adminNotes]);
                    }
                    $message .= ' ' . __('Please contact the GESA executives for more information or to submit a new request.');
                    
                    return back()
                        ->withErrors(['email' => $message])
                        ->onlyInput('email');
                    
                default:
                    return back()
                        ->withErrors(['email' => __('There was an issue with your registration. Please contact the GESA executives for assistance.')])
                        ->onlyInput('email');
            }
        }

        // If we reached here, login failed. Determine specific error.
        $errorMessage = $userFound 
            ? 'The password you entered is incorrect.' 
            : 'We could not find an account with that email address. If you recently registered, your account may still be pending approval.';

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
