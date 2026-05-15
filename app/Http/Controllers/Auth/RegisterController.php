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
        
        // Conflict checking is already handled by RegisterRequest validation rules (Rule::unique)

        // Handle document upload - Store in private storage for security
        $documentPath = null;
        if ($request->hasFile('student_document')) {
            $documentPath = $request->file('student_document')->store('student-ids', 'local');
        }

        // Create a pending registration record (requires admin approval)
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
            'status' => 'pending',
            'email_verified_at' => now(), // Still auto-verify email for now as per current flow
        ]);

        return redirect()->route('auth.fresher-register.success')
            ->with('success', __('Your registration request has been submitted successfully! Please wait for administrative approval. You will receive an email once your account is ready.'));
    }

}
