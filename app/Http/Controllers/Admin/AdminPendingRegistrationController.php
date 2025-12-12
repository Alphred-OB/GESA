<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendingRegistration;
use App\Models\User;
use App\Services\Admin\AdminDueService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AdminPendingRegistrationController extends Controller
{
    public function __construct(private readonly AdminDueService $dues)
    {
    }

    /**
     * Display a listing of pending registrations.
     */
    public function index(Request $request): View
    {
        $query = PendingRegistration::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('index_number', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Class/Program filter
        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }

        $registrations = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('dashboards.admin.pending-registrations.index', compact('registrations'));
    }

    /**
     * Display the specified pending registration.
     */
    public function show(PendingRegistration $registration): View
    {
        return view('dashboards.admin.pending-registrations.show', compact('registration'));
    }

    /**
     * Approve a pending registration and create the user account.
     */
    public function approve(Request $request, PendingRegistration $registration): RedirectResponse
    {
        if ($registration->status !== 'pending') {
            return back()->withErrors(['error' => 'This registration has already been processed.']);
        }

        $fullName = trim($registration->first_name . ' ' . $registration->last_name);

        // Create the user account
        $user = User::create([
            'fullname' => $fullName,
            'username' => $registration->username,
            'email' => $registration->email,
            'password' => $registration->password, // Already hashed
            'phone_number' => $registration->phone_number,
            'index_number' => $registration->index_number,
            'class' => $registration->class,
            'year' => $registration->year,
            'role' => 'student',
            'email_verified_at' => now(), // Auto-verify since admin approved
        ]);

        // Sync dues
        $this->dues->syncStudent($user);

        // Update pending registration
        $registration->update([
            'status' => 'approved',
            'reviewed_at' => now(),
            'reviewed_by' => Auth::guard('admin')->id(),
            'admin_notes' => $request->input('notes'),
        ]);

        // Send approval email
        $this->sendApprovalEmail($user, $registration);

        return redirect()
            ->route('admin.pending-registrations.index')
            ->with('success', "Registration approved! {$fullName} can now log in to their account.");
    }

    /**
     * Reject a pending registration.
     */
    public function reject(Request $request, PendingRegistration $registration): RedirectResponse
    {
        if ($registration->status !== 'pending') {
            return back()->withErrors(['error' => 'This registration has already been processed.']);
        }

        $registration->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
            'reviewed_by' => Auth::guard('admin')->id(),
            'admin_notes' => $request->input('notes', 'Registration rejected.'),
        ]);

        // Send rejection email
        $this->sendRejectionEmail($registration);

        return redirect()
            ->route('admin.pending-registrations.index')
            ->with('success', 'Registration has been rejected.');
    }

    /**
     * Send approval email to the user.
     */
    private function sendApprovalEmail(User $user, PendingRegistration $registration): void
    {
        try {
            Mail::send('emails.registration-approved', [
                'user' => $user,
                'registration' => $registration,
            ], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Your GESA Account Has Been Approved! 🎉');
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send approval email: ' . $e->getMessage());
        }
    }

    /**
     * Send rejection email to the applicant.
     */
    private function sendRejectionEmail(PendingRegistration $registration): void
    {
        try {
            Mail::send('emails.registration-rejected', [
                'registration' => $registration,
            ], function ($message) use ($registration) {
                $message->to($registration->email)
                    ->subject('GESA Registration Update');
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send rejection email: ' . $e->getMessage());
        }
    }
}
