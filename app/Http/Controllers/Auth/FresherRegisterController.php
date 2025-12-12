<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\FresherRegisterRequest;
use App\Models\PendingRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

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
        $fullName = trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));

        // Handle student ID upload
        $studentIdPath = null;
        if ($request->hasFile('student_id')) {
            $studentIdPath = $request->file('student_id')->store('student-ids', 'public');
        }

        // Create pending registration
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
            'status' => 'pending',
        ]);

        return redirect()->route('auth.fresher-register.success')
            ->with('success', __('Your registration request has been submitted successfully! An administrator will review your request within 24-48 hours. You will receive an email once your account is approved.'));
    }

    /**
     * Show the success page after fresher registration.
     */
    public function success(): View
    {
        return view('auth.fresher-register-success');
    }
}
