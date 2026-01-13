<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\EmailVerificationService;
use App\Services\Admin\AdminDueService;
use App\Models\User;
use App\Models\PendingRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function __construct(private readonly AdminDueService $dues)
    {
    }

    /**
     * Show the student registration form.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();
        
        // Check for existing user or pending registration
        if (User::where('email', $data['email'])->exists() || User::where('index_number', $data['index_number'])->exists()) {
            return back()
                ->withErrors(['email' => __('An account already exists with these details.')])
                ->withInput();
        }

        if (PendingRegistration::where('email', $data['email'])->where('status', 'pending')->exists()) {
            return back()
                ->withErrors(['email' => __('A registration request is already pending for this email.')])
                ->withInput();
        }

        // Check if email verification is enabled via environment variable
        $otpEnabled = config('app.email_verification_enabled', false);

        // Generate OTP if enabled
        $otp = null;
        if ($otpEnabled) {
            $otp = (string) random_int(100000, 999999);
        }

        // Handle document upload
        $documentPath = null;
        if ($request->hasFile('student_document')) {
            $documentPath = $request->file('student_document')->store('student-ids', 'public');
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
            'student_id_path' => $documentPath,
            'reason' => null,
            'status' => 'pending',
            'verification_code' => $otpEnabled ? Hash::make($otp) : null,
            'verification_expires_at' => $otpEnabled ? now()->addMinutes(15) : null,
            'email_verified_at' => $otpEnabled ? null : now(), // Auto-verify if OTP disabled
        ]);

        if ($otpEnabled) {
            // Send OTP and redirect to verification page
            try {
                \Illuminate\Support\Facades\Mail::send('emails.fresher-verification', ['code' => $otp], function ($message) use ($registration) {
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
}
