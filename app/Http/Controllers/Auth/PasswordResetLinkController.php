<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(ForgotPasswordRequest $request): RedirectResponse
    {
        try {
            $status = Password::broker('users')->sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                Log::info('Password reset link sent successfully', ['email' => $request->email]);
                return back()->with('status', __($status));
            }

            Log::warning('Password reset failed', ['email' => $request->email, 'status' => $status]);
            return back()->withErrors(['email' => __($status)])->onlyInput('email');
            
        } catch (\Exception $e) {
            Log::error('Password reset email failed to send', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withErrors(['email' => __('We encountered an issue sending the password reset email. Please try again later or contact support.')])
                ->onlyInput('email');
        }
    }
}
