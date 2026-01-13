<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\FresherRegisterRequest;
use App\Models\PendingRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

class FresherRegisterController extends Controller
{
    /**
     * Show the fresher registration form.
     */
    public function create(): View
    {
        return view('auth.fresher-register');
    }

    /**
     * Handle an incoming fresher registration request.
     */
    public function store(FresherRegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();
        
        // Handle student ID upload
        $studentIdPath = null;
        if ($request->hasFile('student_id')) {
            $studentIdPath = $request->file('student_id')->store('student-ids', 'public');
        }

        // Check if email verification is enabled via environment variable
        $otpEnabled = config('app.email_verification_enabled', false);

        // Generate OTP if enabled
        $otp = null;
        if ($otpEnabled) {
            $otp = (string) random_int(100000, 999999);
        }

        // Create pending registration
        $registration = PendingRegistration::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'] ?? null,
            'index_number' => $data['index_number'],
            'class' => $data['class'],
            'year' => $data['year'],
            'password' => Hash::make($data['password']),
            'reason' => $data['reason'],
            'student_id_path' => $studentIdPath,
            'status' => 'pending',
            'verification_code' => $otpEnabled ? Hash::make($otp) : null,
            'verification_expires_at' => $otpEnabled ? now()->addMinutes(15) : null,
            'email_verified_at' => $otpEnabled ? null : now(), // Auto-verify if OTP disabled
        ]);

        if ($otpEnabled) {
            // Send OTP and redirect to verification page
            try {
                Mail::send('emails.fresher-verification', ['code' => $otp], function ($message) use ($registration) {
                    $message->to($registration->email)
                        ->subject('Verify Your GESA Registration');
                });
            } catch (\Exception $e) {
                \Log::error('Failed to send verification email: ' . $e->getMessage());
            }

            // Store ID in session for verification step
            session(['fresher_pending_id' => $registration->id]);

            return redirect()->route('auth.fresher-register.verify');
        } else {
            // Skip OTP, go directly to success
            return redirect()->route('auth.fresher-register.success')
                ->with('success', __('Your registration request has been submitted successfully! An administrator will review your request within 24-48 hours.'));
        }
    }

    /**
     * Show the verification form.
     */
    public function showVerifyForm(): View|RedirectResponse
    {
        if (! session('fresher_pending_id')) {
            return redirect()->route('auth.fresher-register');
        }

        return view('auth.fresher-verify');
    }

    /**
     * Verify the OTP code.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $registrationId = session('fresher_pending_id');
        if (! $registrationId) {
            return redirect()->route('auth.fresher-register');
        }

        $registration = PendingRegistration::find($registrationId);
        if (! $registration) {
            return redirect()->route('auth.fresher-register');
        }

        if ($registration->email_verified_at) {
            return redirect()->route('auth.fresher-register.success');
        }

        // Check if verification code exists
        if (! $registration->verification_code || ! $registration->verification_expires_at) {
            return back()->withErrors(['code' => 'The verification code has expired. Please request a new one.']);
        }

        // Check expiration using explicit comparison to avoid timezone issues
        if (now()->gt($registration->verification_expires_at)) {
            return back()->withErrors(['code' => 'The verification code has expired. Please request a new one.']);
        }

        if (! Hash::check($request->code, $registration->verification_code)) {
            return back()->withErrors(['code' => 'Invalid verification code. Please try again.']);
        }

        // Success - Mark verified and clear code
        $registration->update([
            'email_verified_at' => now(),
            'verification_code' => null,
            'verification_expires_at' => null,
        ]);

        session()->forget('fresher_pending_id');

        return redirect()->route('auth.fresher-register.success')
            ->with('success', __('Your email has been verified! Your registration is now pending admin approval.'));
    }

    /**
     * Resend the verification code.
     */
    public function resend(): RedirectResponse
    {
        $registrationId = session('fresher_pending_id');
        if (! $registrationId) {
            return redirect()->route('auth.fresher-register');
        }

        $registration = PendingRegistration::find($registrationId);
        if (! $registration) {
            return redirect()->route('auth.fresher-register');
        }

        $otp = (string) random_int(100000, 999999);
        
        $registration->update([
            'verification_code' => Hash::make($otp),
            'verification_expires_at' => now()->addMinutes(15),
        ]);

        try {
            Mail::send('emails.fresher-verification', ['code' => $otp], function ($message) use ($registration) {
                $message->to($registration->email)
                    ->subject('Verify Your GESA Registration');
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email. Please try again.');
        }

        return back()->with('success', 'A new verification code has been sent to your email.');
    }

    /**
     * Show the success page after fresher registration.
     */
    public function success(): View
    {
        return view('auth.fresher-register-success');
    }
}
