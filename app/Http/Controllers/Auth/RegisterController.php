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
use Illuminate\Support\Facades\Mail;
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
     * Auto-approves the registration and creates the user account immediately.
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();
        
        $fullName = trim($data['first_name'] . ' ' . $data['last_name']);
        
        // Check for existing user conflicts
        if (User::where('email', $data['email'])->exists()) {
            return back()
                ->withErrors(['email' => __('An account already exists with this email.')])
                ->withInput();
        }
        
        if (User::where('index_number', $data['index_number'])->exists()) {
            return back()
                ->withErrors(['index_number' => __('An account already exists with this reference number.')])
                ->withInput();
        }
        
        if (User::where('username', $data['username'])->exists()) {
            return back()
                ->withErrors(['username' => __('This username is already taken.')])
                ->withInput();
        }

        // Handle document upload
        $documentPath = null;
        if ($request->hasFile('student_document')) {
            $documentPath = $request->file('student_document')->store('student-ids', 'public');
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
        $this->dues->syncStudent($user);

        // Store audit record in pending_registrations
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
            'student_id_path' => $documentPath,
            'reason' => null,
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
