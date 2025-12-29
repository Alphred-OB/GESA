<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDueRequest;
use App\Http\Requests\Admin\UpdateDueRequest;
use App\Models\Due;
use App\Services\Admin\AdminDueService;
use Illuminate\Http\RedirectResponse;
use App\Mail\Student\ManualPaymentApproved;
use App\Mail\Student\ManualPaymentRejected;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminDueController extends Controller
{
    public function __construct(private readonly AdminDueService $service)
    {
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'academic_year', 'status', 'class', 'year']);
        $perPage = (int) $request->integer('per_page', 25);
        $perPageOptions = [25, 50, 100];

        if (! in_array($perPage, $perPageOptions, true)) {
            $perPage = 25;
        }

        $dues = $this->service->list($filters, $perPage);
        $totals = $this->service->totals($filters);
        $filtersMeta = $this->service->filterOptions();

        return view('dashboards.admin.dues.index', [
            'title' => 'Student dues',
            'dues' => $dues,
            'totals' => $totals,
            'filters' => $filters,
            'filtersMeta' => $filtersMeta,
            'perPageOptions' => $perPageOptions,
            'currentPerPage' => $perPage,
        ]);
    }

    public function create(): View
    {
        $filtersMeta = $this->service->filterOptions();
        $matrix = $this->service->matrix();

        return view('dashboards.admin.dues.create', [
            'title' => 'Create academic year due',
            'filtersMeta' => $filtersMeta,
            'matrix' => $matrix,
        ]);
    }

    public function edit(Due $due): View
    {
        return view('dashboards.admin.dues.edit', [
            'title' => 'Edit due',
            'due' => $due,
            'statusOptions' => AdminDueService::STATUS_OPTIONS,
        ]);
    }

    public function store(StoreDueRequest $request): RedirectResponse
    {
        $payload = $request->validated();
        $createdCount = $this->service->createDue($payload, $request->user('admin'));

        return redirect()
            ->route('admin.dues.index', ['academic_year' => $payload['academic_year']])
            ->with('status', __('Issued due ":description" for :year to :count students.', [
                'description' => $payload['description'],
                'year' => $payload['academic_year'],
                'count' => number_format($createdCount),
            ]));
    }

    public function update(UpdateDueRequest $request, Due $due): RedirectResponse
    {
        $this->service->updateDue($due, $request->validated(), $request->user('admin'));

        return redirect()
            ->route('admin.dues.index', ['academic_year' => $due->academic_year])
            ->with('status', __('Updated due ":description".', ['description' => $due->description]));
    }

    public function destroy(Due $due): RedirectResponse
    {
        $this->service->deleteDue($due);

        return redirect()
            ->route('admin.dues.index', ['academic_year' => $due->academic_year])
            ->with('status', __('Removed due ":description" for :student.', [
                'description' => $due->description,
                'student' => $due->student?->fullname ?? $due->student?->username ?? 'student #' . $due->student_id,
            ]));
    }

    /**
     * Show the verification page for a manual payment.
     */
    public function verify(Due $due): View
    {
        if ($due->payment_status !== 'pending_verification') {
            abort(404);
        }

        return view('dashboards.admin.dues.verify', [
            'title' => 'Verify Payment',
            'due' => $due,
        ]);
    }

    /**
     * Approve a manual payment.
     */
    public function approve(Request $request, Due $due): RedirectResponse
    {
        if ($due->payment_status !== 'pending_verification') {
            abort(404);
        }

        $admin = $request->user('admin');

        $due->update([
            'payment_status' => 'paid',
            'payment_date' => now(),
            'verification_date' => now(),
            'verified_by' => $admin->user_id,
            'verification_notes' => $request->input('verification_notes') ?? __('Approved by admin.'),
        ]);

        // Send Email
        Mail::to($due->student->email)->send(new ManualPaymentApproved($due));

        return redirect()
            ->route('admin.dues.index')
            ->with('status', __('Payment approved and student notified.'));
    }

    /**
     * Reject a manual payment.
     */
    public function reject(Request $request, Due $due): RedirectResponse
    {
        if ($due->payment_status !== 'pending_verification') {
            abort(404);
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $admin = $request->user('admin');

        $due->update([
            'payment_status' => 'owing',
            'rejection_reason' => $request->input('rejection_reason'),
            'verification_notes' => __('Rejected by admin.'),
            'verified_by' => $admin->user_id,
            'verification_date' => now(),
        ]);

        // Delete proof? Or keep it? Usually keep for records but maybe hide it. 
        // For now keep it.

        // Send Email
        Mail::to($due->student->email)->send(new ManualPaymentRejected($due));

        return redirect()
            ->route('admin.dues.index')
            ->with('status', __('Payment rejected and student notified.'));
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = $request->only(['search', 'academic_year', 'status', 'class', 'year']);

        return $this->service->export($filters);
    }

    public function statistics(Request $request): View
    {
        $filters = $request->only(['search', 'academic_year', 'status', 'class', 'year']);
        $stats = $this->service->statistics($filters);
        $filtersMeta = $this->service->filterOptions();

        return view('dashboards.admin.dues.statistics', [
            'title' => 'Dues performance analytics',
            'stats' => $stats,
            'filters' => $filters,
            'filtersMeta' => $filtersMeta,
        ]);
    }
}
