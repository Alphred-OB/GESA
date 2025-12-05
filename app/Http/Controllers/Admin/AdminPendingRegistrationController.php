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
        $fullName = trim($registration->first_name . ' ' . $registration->last_name);

        // Only create user if not already created (status was 'pending' or 'rejected')
        if ($registration->status !== 'approved') {
            // Check if user already exists
            $existingUser = User::where('email', $registration->email)->orWhere('index_number', $registration->index_number)->first();
            
            if (!$existingUser) {
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
                
                // Send approval email
                $this->sendApprovalEmail($user, $registration);
            }
        }

        // Update pending registration
        $registration->update([
            'status' => 'approved',
            'reviewed_at' => now(),
            'reviewed_by' => Auth::guard('admin')->id(),
            'admin_notes' => $request->input('notes'),
        ]);

        return redirect()
            ->route('admin.pending-registrations.index')
            ->with('success', "Registration approved! {$fullName} can now log in to their account.");
    }

    /**
     * Reject a pending registration.
     */
    public function reject(Request $request, PendingRegistration $registration): RedirectResponse
    {
        $reason = $request->input('notes') ?: $request->input('reason', 'Registration rejected.');

        $registration->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
            'reviewed_by' => Auth::guard('admin')->id(),
            'admin_notes' => $reason,
        ]);

        // Send rejection email
        $this->sendRejectionEmail($registration);

        return redirect()
            ->route('admin.pending-registrations.index')
            ->with('success', 'Registration has been rejected.');
    }

    /**
     * Bulk approve pending registrations.
     */
    public function bulkApprove(Request $request): RedirectResponse
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        
        if (empty($ids)) {
            return back()->withErrors(['error' => 'No registrations selected.']);
        }

        $registrations = PendingRegistration::whereIn('id', $ids)->get();

        if ($registrations->isEmpty()) {
            return back()->withErrors(['error' => 'No registrations found to approve.']);
        }

        $approved = 0;
        foreach ($registrations as $registration) {
            try {
                // Only create user if not already approved
                if ($registration->status !== 'approved') {
                    // Check if user already exists
                    $existingUser = User::where('email', $registration->email)
                        ->orWhere('index_number', $registration->index_number)
                        ->first();
                    
                    if (!$existingUser) {
                        $fullName = trim($registration->first_name . ' ' . $registration->last_name);

                        // Create the user account
                        $user = User::create([
                            'fullname' => $fullName,
                            'username' => $registration->username,
                            'email' => $registration->email,
                            'password' => $registration->password,
                            'phone_number' => $registration->phone_number,
                            'index_number' => $registration->index_number,
                            'class' => $registration->class,
                            'year' => $registration->year,
                            'role' => 'student',
                            'email_verified_at' => now(),
                        ]);

                        // Sync dues
                        $this->dues->syncStudent($user);
                        
                        // Send email
                        $this->sendApprovalEmail($user, $registration);
                    }
                }

                // Update registration
                $registration->update([
                    'status' => 'approved',
                    'reviewed_at' => now(),
                    'reviewed_by' => Auth::guard('admin')->id(),
                    'admin_notes' => 'Bulk approved',
                ]);

                $approved++;
            } catch (\Exception $e) {
                \Log::error('Failed to approve registration ' . $registration->id . ': ' . $e->getMessage());
            }
        }

        return redirect()
            ->route('admin.pending-registrations.index')
            ->with('success', "{$approved} registration(s) approved successfully.");
    }

    /**
     * Bulk reject pending registrations.
     */
    public function bulkReject(Request $request): RedirectResponse
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $reason = $request->input('reason', 'Bulk rejected');
        
        if (empty($ids)) {
            return back()->withErrors(['error' => 'No registrations selected.']);
        }

        $registrations = PendingRegistration::whereIn('id', $ids)->get();

        if ($registrations->isEmpty()) {
            return back()->withErrors(['error' => 'No registrations found to reject.']);
        }

        $rejected = 0;
        foreach ($registrations as $registration) {
            try {
                $registration->update([
                    'status' => 'rejected',
                    'reviewed_at' => now(),
                    'reviewed_by' => Auth::guard('admin')->id(),
                    'admin_notes' => $reason,
                ]);

                // Send email
                $this->sendRejectionEmail($registration);
                $rejected++;
            } catch (\Exception $e) {
                \Log::error('Failed to reject registration ' . $registration->id . ': ' . $e->getMessage());
            }
        }

        return redirect()
            ->route('admin.pending-registrations.index')
            ->with('success', "{$rejected} registration(s) rejected.");
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
