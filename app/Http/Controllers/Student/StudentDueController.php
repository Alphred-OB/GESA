<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\Student\StudentDueService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentDueController extends Controller
{
    public function __construct(private readonly StudentDueService $dueService)
    {
    }

    public function index(Request $request): View
    {
        $student = $request->user('student');

        $filters = $request->only(['status', 'academic_year', 'search']);
        $perPage = (int) $request->integer('per_page', 10);
        $perPageOptions = [10, 25, 50];

        if (! in_array($perPage, $perPageOptions, true)) {
            $perPage = 10;
        }

        $summary = $this->dueService->summary($student);
        $dues = $this->dueService->list($student, $filters, $perPage);
        $filterOptions = $this->dueService->filterOptions($student);

        return view('dashboards.student.dues.index', [
            'title' => 'My dues',
            'summary' => $summary,
            'dues' => $dues,
            'filters' => $filters,
            'filterOptions' => $filterOptions,
            'perPageOptions' => $perPageOptions,
            'currentPerPage' => $perPage,
            'statusLabels' => StudentDueService::STATUS_LABELS,
        ]);
    }
    public function cancel(\App\Models\Due $due, Request $request): \Illuminate\Http\RedirectResponse
    {
        $student = $request->user('student');
        if ($due->student_id !== $student->user_id && $due->student_id !== $student->id) {
            abort(403);
        }

        if (in_array($due->payment_status, ['pending_verification', 'pending'])) {
            $due->update([
                'payment_status' => 'owing',
                'payment_reference' => null,
                'payment_method' => null,
                'payment_proof' => null,
            ]);
            return redirect()->route('student.dues.index')->with('status', __('Payment cancelled successfully.'));
        }

        return redirect()->route('student.dues.index')->with('error', __('Cannot cancel this payment.'));
    }
}
