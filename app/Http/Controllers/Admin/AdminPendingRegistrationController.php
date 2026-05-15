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
    public function __construct(private readonly \App\Services\Auth\RegistrationService $registrationService)
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
            $this->registrationService->approve($registration, $request->input('notes'));
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

        $registrations = PendingRegistration::whereIn('id', $request->ids)
            ->where('status', 'pending')
            ->get();

        $count = 0;
        $failed = [];
        
        foreach ($registrations as $registration) {
            try {
                $this->registrationService->approve($registration, 'Bulk approval');
                $count++;
            } catch (\RuntimeException $e) {
                $failed[] = "{$registration->first_name} {$registration->last_name} ({$registration->username})";
            }
        }

        $message = "{$count} registrations approved successfully.";
        
        if (!empty($failed)) {
            $message .= ' Issues with: ' . implode(', ', $failed);
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

        $this->registrationService->reject($registration, $request->input('notes', 'Registration rejected.'));

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

        $registrations = PendingRegistration::whereIn('id', $request->ids)
            ->where('status', 'pending')
            ->get();

        $count = 0;
        foreach ($registrations as $registration) {
            $this->registrationService->reject($registration, $request->input('notes', 'Bulk rejection'));
            $count++;
        }

        return redirect()
            ->route('admin.pending-registrations.index')
            ->with('success', "{$count} registrations have been rejected.");
    }

    /**
     * Reject multiple pending registrations.
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:pending_registrations,id',
        ]);

        $count = PendingRegistration::whereIn('id', $request->ids)->delete();

        return redirect()
            ->route('admin.pending-registrations.index')
            ->with('success', "{$count} registrations have been permanently deleted.");
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

    /**
     * View the private student document (ID or registration slip).
     */
    public function viewDocument(PendingRegistration $registration): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        if (!$registration->student_id_path || !\Illuminate\Support\Facades\Storage::disk('local')->exists($registration->student_id_path)) {
            abort(404, 'Document not found.');
        }

        $path = \Illuminate\Support\Facades\Storage::disk('local')->path($registration->student_id_path);
        
        return response()->file($path);
    }
}
