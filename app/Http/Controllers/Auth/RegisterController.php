<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\EmailVerificationService;
use App\Services\Admin\AdminDueService;
use App\Models\User;
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
        $fullName = trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));

        $existingByEmail = User::where('email', $data['email'])->first();
        $existingByIndex = User::where('index_number', $data['index_number'])->first();
        $existingByUsername = User::where('username', $data['username'])->first();

        if ($existingByEmail && $existingByEmail->email_verified_at) {
            return back()
                ->withErrors([
                    'email' => __('An account already exists with this email. Please sign in or use "Forgot password" to access it.'),
                ])
                ->withInput($request->except('password', 'password_confirmation'));
        }

        if ($existingByIndex && $existingByIndex->email_verified_at) {
            return back()
                ->withErrors([
                    'index_number' => __('We cannot create a new account with these details. If you have registered before, please sign in or use "Forgot password". If you no longer have access to your email, contact the administrator.'),
                ])
                ->withInput($request->except('password', 'password_confirmation'));
        }

        if ($existingByIndex && ! $existingByIndex->email_verified_at) {
            $user = $existingByIndex;

            if ($existingByEmail && $existingByEmail->user_id !== $user->user_id) {
                return back()
                    ->withErrors([
                        'email' => __('This email is already used by another account. Please use a different email or sign in with that account.'),
                    ])
                    ->withInput($request->except('password', 'password_confirmation'));
            }

            if ($existingByUsername && $existingByUsername->user_id !== $user->user_id) {
                return back()
                    ->withErrors([
                        'username' => __('This username is already taken. Please choose another.'),
                    ])
                    ->withInput($request->except('password', 'password_confirmation'));
            }

            $user->fullname = $fullName;
            $user->username = $data['username'];
            $user->email = $data['email'];
            $user->password = $data['password'];
            $user->phone_number = $data['phone_number'] ?? null;
            $user->index_number = $data['index_number'];
            $user->class = $data['class'];
            $user->year = $data['year'];
            $user->role = 'student';
            $user->save();

            $this->dues->syncStudent($user);

            app(EmailVerificationService::class)->send($user);

            $request->session()->put('pending_verification', [
                'email' => $user->email,
                'guard' => 'student',
                'user_id' => $user->getAuthIdentifier(),
                'remember' => false,
            ]);

            return redirect()->route('auth.verify.notice')
                ->with('status', __('We found an existing registration for your details. We have updated your information and sent a new verification code. Please check your email to activate your account.'));
        }

        if ($existingByUsername) {
            return back()
                ->withErrors([
                    'username' => __('This username is already taken. Please choose another.'),
                ])
                ->withInput($request->except('password', 'password_confirmation'));
        }

        if ($existingByEmail && ! $existingByEmail->email_verified_at) {
            $user = $existingByEmail;

            app(EmailVerificationService::class)->send($user);

            $request->session()->put('pending_verification', [
                'email' => $user->email,
                'guard' => 'student',
                'user_id' => $user->getAuthIdentifier(),
                'remember' => false,
            ]);

            return redirect()->route('auth.verify.notice')
                ->with('status', __('An account is already pending for this email. We have resent the verification code. Please check your inbox to complete your registration.'));
        }

        $user = User::create([
            'fullname' => $fullName,
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
            'phone_number' => $data['phone_number'] ?? null,
            'index_number' => $data['index_number'],
            'class' => $data['class'],
            'year' => $data['year'],
            'role' => 'student',
        ]);

        $this->dues->syncStudent($user);

        app(EmailVerificationService::class)->send($user);

        $request->session()->put('pending_verification', [
            'email' => $user->email,
            'guard' => 'student',
            'user_id' => $user->getAuthIdentifier(),
            'remember' => false,
        ]);

        return redirect()->route('auth.verify.notice')
            ->with('status', __('Registration successful. Please verify your email to activate your account.'));
    }
}
