<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginOtpRequest;
use App\Models\User;
use App\Services\Auth\LoginOtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginOtpController extends Controller
{
    public function __construct(private readonly LoginOtpService $loginOtpService)
    {
    }

    /**
     * Show the OTP entry screen after password authentication.
     */
    public function create(Request $request): RedirectResponse|View
    {
        $pending = $request->session()->get('pending_login_otp');

        if (! $pending) {
            return redirect()->route('login');
        }

        return view('auth.login-otp', [
            'pending' => $pending,
        ]);
    }

    /**
     * Verify the one-time code and sign the user in.
     */
    public function store(LoginOtpRequest $request): RedirectResponse
    {
        $pending = $request->session()->get('pending_login_otp');

        if (! $pending) {
            return redirect()->route('login')->withErrors([
                'email' => __('Your session expired. Please sign in again.'),
            ]);
        }

        $user = User::find($pending['user_id'] ?? null);

        if (! $user) {
            $request->session()->forget('pending_login_otp');

            return redirect()->route('login')->withErrors([
                'email' => __('We could not find your account. Please sign in again.'),
            ]);
        }

        $result = $this->loginOtpService->verify($user, $pending['guard'], $request->input('code'));

        if (! $result['success']) {
            return back()->withErrors([
                'code' => $result['message'],
            ])->onlyInput('code');
        }

        $remember = (bool) ($pending['remember'] ?? false);

        $request->session()->forget('pending_login_otp');

        Auth::shouldUse($pending['guard']);
        Auth::guard($pending['guard'])->login($user, $remember);
        $request->session()->regenerate();

        return redirect()->intended(match ($pending['guard']) {
            'admin' => route('admin.dashboard'),
            default => route('student.dashboard'),
        })->with('status', __('Login verified! Welcome back.'));
    }

    /**
     * Resend a one-time code for the pending login.
     */
    public function resend(Request $request): RedirectResponse
    {
        $pending = $request->session()->get('pending_login_otp');

        if (! $pending) {
            return redirect()->route('login')->withErrors([
                'email' => __('Your session expired. Please sign in again.'),
            ]);
        }

        $user = User::where('user_id', $pending['user_id'] ?? null)->first();

        if (! $user) {
            $request->session()->forget('pending_login_otp');

            return redirect()->route('login')->withErrors([
                'email' => __('We could not find your account. Please sign in again.'),
            ]);
        }

        $this->loginOtpService->send($user, $pending['guard']);

        return back()->with('status', __('A fresh login code has been sent to your email.'));
    }
}
