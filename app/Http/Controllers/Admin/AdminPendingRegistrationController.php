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
        // Allow approving pending OR rejected registrations (so admin can reverse a rejection)
        if (! in_array($registration->status, ['pending', 'rejected'])) {
            return back()->withErrors(['error' => 'This registration has already been approved.']);
        }

        try {
            $this->processApproval($registration, $request->input('notes'));
        } catch (\RuntimeException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        $fullName = trim($registration->first_name . ' ' . $registration->last_name);

        return redirect()
            ->route('admin.pending-registrations.index')
            ->with('success', "Registration approved! {$fullName} can now log in to their account.");
    }

    /**
     * Approve multiple pending registrations.
     */
    public function bulkApprove(Request $request): RedirectResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:pending_registrations,id',
        ]);

        $count = 0;
        $failed = [];
        
        foreach ($request->ids as $id) {
            $registration = PendingRegistration::find($id);
            if ($registration && $registration->status === 'pending') {
                try {
                    $this->processApproval($registration, 'Bulk approval');
                    $count++;
                } catch (\RuntimeException $e) {
                    $failed[] = "{$registration->first_name} {$registration->last_name} ({$registration->username})";
                }
            }
        }

        $message = "{$count} registrations have been approved successfully.";
        
        if (!empty($failed)) {
            $message .= ' Failed to approve: ' . implode(', ', $failed) . ' (duplicate username/email detected).';
        }

        return redirect()
            ->route('admin.pending-registrations.index')
            ->with('success', $message);
    }

    /**
 * Reject a pending registration.
 */
public function reject(Request $request, PendingRegistration $registration): RedirectResponse
{
    // Allow rejecting pending OR approved registrations (so admin can reverse an approval)
    if (! in_array($registration->status, ['pending', 'approved'])) {
        return back()->withErrors(['error' => 'This registration has already been rejected.']);
    }

    // If was approved, delete the User record first
    if ($registration->status === 'approved') {
        User::where('email', $registration->email)->delete();
    }

    $this->processRejection($registration, $request->input('notes', 'Registration rejected.'));

    return redirect()
        ->route('admin.pending-registrations.index')
        ->with('success', 'Registration has been rejected.');
}

    /**
     * Reject multiple pending registrations.
     */
    public function bulkReject(Request $request): RedirectResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:pending_registrations,id',
        ]);

        $count = 0;
        foreach ($request->ids as $id) {
            $registration = PendingRegistration::find($id);
            if ($registration && $registration->status === 'pending') {
                $this->processRejection($registration, $request->input('notes', 'Bulk rejection'));
                $count++;
            }
        }

        return redirect()
            ->route('admin.pending-registrations.index')
            ->with('success', "{$count} registrations have been rejected.");
    }

    /**
     * Process logic for approving a registration.
     * 
     * @throws \RuntimeException if username, email, or index_number already exists
     */
    private function processApproval(PendingRegistration $registration, ?string $notes = null): void
    {
        $fullName = trim($registration->first_name . ' ' . $registration->last_name);

        // Check for existing conflicts before creating
        $conflicts = [];
        
        if (User::where('username', $registration->username)->exists()) {
            $conflicts[] = "username '{$registration->username}'";
        }
        
        if (User::where('email', $registration->email)->exists()) {
            $conflicts[] = "email '{$registration->email}'";
        }
        
        if ($registration->index_number && User::where('index_number', $registration->index_number)->exists()) {
            $conflicts[] = "reference number '{$registration->index_number}'";
        }
        
        if (!empty($conflicts)) {
            throw new \RuntimeException(
                'Cannot approve: A user already exists with ' . implode(' and ', $conflicts) . '. ' .
                'Please reject this registration or ask the student to use a different username.'
            );
        }

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
            'email_verified_at' => $registration->email_verified_at, // Copy from OTP-verified registration
        ]);

        // Fix: The User model has a 'hashed' cast which hashes the password again.
        // Since $registration->password is already hashed, we need to bypass the model cast
        // by directly updating the record in the database.
        User::where('user_id', $user->user_id)->update([
            'password' => $registration->password
        ]);

        // Sync dues
        $this->dues->syncStudent($user);

        // Update pending registration
        $registration->update([
            'status' => 'approved',
            'reviewed_at' => now(),
            'reviewed_by' => Auth::guard('admin')->id(),
            'admin_notes' => $notes,
        ]);

        // Send approval email
        $this->sendApprovalEmail($user, $registration);
    }

    /**
     * Process logic for rejecting a registration.
     */
    private function processRejection(PendingRegistration $registration, ?string $notes = null): void
    {
        $registration->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
            'reviewed_by' => Auth::guard('admin')->id(),
            'admin_notes' => $notes,
        ]);

        // Send rejection email
        $this->sendRejectionEmail($registration);
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

    /**
     * API endpoint for live polling of pending registrations count.
     */
    public function pendingRegistrationsApi(): \Illuminate\Http\JsonResponse
    {
        $pendingCount = PendingRegistration::where('status', 'pending')->count();
        
        // Get the most recent pending registrations for notification details
        $recentPending = PendingRegistration::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get(['id', 'first_name', 'last_name', 'email', 'class', 'year', 'created_at']);

        return response()->json([
            'total_count' => $pendingCount,
            'registrations' => $recentPending->map(fn($r) => [
                'id' => $r->id,
                'name' => $r->first_name . ' ' . $r->last_name,
                'email' => $r->email,
                'class' => $r->class,
                'year' => $r->year,
                'created_at' => $r->created_at->diffForHumans(),
            ]),
        ]);
    }
}
