<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\FresherRegisterRequest;
use App\Models\PendingRegistration;
use App\Models\User;
use App\Services\Admin\AdminDueService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

class FresherRegisterController extends Controller
{
    public function __construct(
        private readonly AdminDueService $dueService
    ) {}

    /**
     * Show the fresher registration form.
     */
    public function create(): View
    {
        return view('auth.fresher-register');
    }

    /**
     * Handle an incoming fresher registration request.
     * Auto-approves the registration and creates the user account immediately.
     */
    public function store(FresherRegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();
        
        // Handle student ID upload
        $studentIdPath = null;
        if ($request->hasFile('student_id')) {
            $studentIdPath = $request->file('student_id')->store('student-ids', 'public');
        }

        $fullName = trim($data['first_name'] . ' ' . $data['last_name']);

        // Check for existing conflicts before creating
        $conflicts = [];
        
        if (User::where('username', $data['username'])->exists()) {
            return back()
                ->withErrors(['username' => 'This username is already taken. Please choose a different one.'])
                ->withInput();
        }
        
        if (User::where('email', $data['email'])->exists()) {
            return back()
                ->withErrors(['email' => 'An account with this email already exists.'])
                ->withInput();
        }
        
        if ($data['index_number'] && User::where('index_number', $data['index_number'])->exists()) {
            return back()
                ->withErrors(['index_number' => 'An account with this reference number already exists.'])
                ->withInput();
        }

        // Create the user account directly (auto-approved)
        $user = User::create([
            'fullname' => $fullName,
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone_number' => $data['phone_number'] ?? null,
            'index_number' => $data['index_number'],
            'class' => $data['class'],
            'year' => $data['year'],
            'role' => 'student',
            'email_verified_at' => now(), // Auto-verified
        ]);

        // Sync dues for the new student
        $this->dueService->syncStudent($user);

        // Optionally store a record in pending_registrations for audit trail
        PendingRegistration::create([
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
            'status' => 'approved', // Auto-approved
            'email_verified_at' => now(),
            'reviewed_at' => now(),
            'reviewed_by' => null, // System auto-approved
            'admin_notes' => 'Auto-approved during registration',
        ]);

        // Send welcome email
        $this->sendWelcomeEmail($user);

        return redirect()->route('auth.fresher-register.success')
            ->with('success', __('Your account has been created successfully! You can now log in with your credentials.'));
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

    /**
     * Send welcome email to the newly registered user.
     */
    private function sendWelcomeEmail(User $user): void
    {
        try {
            Mail::send('emails.registration-approved', [
                'user' => $user,
                'registration' => null,
            ], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Welcome to GESA! Your Account is Ready 🎉');
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email: ' . $e->getMessage());
        }
    }
}
