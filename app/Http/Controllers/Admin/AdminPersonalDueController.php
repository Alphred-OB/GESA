<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Due;
use App\Models\PaymentSetting;
use App\Services\Student\StudentDueService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Dompdf\Dompdf;
use Dompdf\Options;

class AdminPersonalDueController extends Controller
{
    public function __construct(private readonly StudentDueService $dueService)
    {
    }

    /**
     * Show the admin's personal dues.
     */
    public function index(Request $request): View
    {
        $admin = $request->user('admin');

        $filters = $request->only(['status', 'academic_year', 'search']);
        $perPage = (int) $request->integer('per_page', 10);
        $perPageOptions = [10, 25, 50];

        if (! in_array($perPage, $perPageOptions, true)) {
            $perPage = 10;
        }

        $summary = $this->dueService->summary($admin);
        $dues = $this->dueService->list($admin, $filters, $perPage);
        $filterOptions = $this->dueService->filterOptions($admin);

        return view('dashboards.admin.dues.personal.index', [
            'title' => 'My personal dues',
            'summary' => $summary,
            'dues' => $dues,
            'filters' => $filters,
            'filterOptions' => $filterOptions,
            'perPageOptions' => $perPageOptions,
            'currentPerPage' => $perPage,
            'statusLabels' => StudentDueService::STATUS_LABELS,
        ]);
    }

    /**
     * Show the manual payment details for the admin.
     */
    public function showManualPayment(Request $request, Due $due): View|RedirectResponse
    {
        $admin = $request->user('admin');

        if (!$admin || (int)$due->student_id !== (int)$admin->getAuthIdentifier()) {
            abort(403);
        }

        if ($due->payment_status === 'paid') {
            return redirect()
                ->route('admin.personal-dues.index')
                ->with('status', __('This due was already marked as paid.'));
        }

        if (PaymentSetting::getValue('manual_payment_enabled', '0') !== '1') {
            return redirect()
                ->route('admin.personal-dues.index')
                ->with('error', __('Manual payment is currently disabled.'));
        }

        $settings = [
            'merchant_number' => PaymentSetting::getValue('merchant_number', ''),
            'merchant_name' => PaymentSetting::getValue('merchant_name', ''),
            'merchant_network' => PaymentSetting::getValue('merchant_network', ''),
            'manual_payment_instructions' => PaymentSetting::getValue('manual_payment_instructions', ''),
        ];

        return view('dashboards.admin.dues.personal.manual-payment', [
            'title' => 'Submit Payment Evidence',
            'due' => $due,
            'settings' => $settings,
        ]);
    }

    /**
     * Handle the manual payment submission for the admin.
     */
    public function storeManualPayment(Request $request, Due $due): RedirectResponse
    {
        $admin = $request->user('admin');

        if (!$admin || (int)$due->student_id !== (int)$admin->getAuthIdentifier()) {
            abort(403);
        }

        if (PaymentSetting::getValue('manual_payment_enabled', '0') !== '1') {
            return redirect()
                ->route('admin.personal-dues.index')
                ->with('error', __('Manual payment is currently disabled.'));
        }

        $request->validate([
            'payment_proof' => 'required|image|max:2048', // Max 2MB
        ]);

        if ($request->hasFile('payment_proof')) {
            // Delete old proof if exists
            if ($due->payment_proof) {
                Storage::disk('public')->delete($due->payment_proof);
            }

            $path = $request->file('payment_proof')->store('payment-proofs', 'public');
            
            $due->update([
                'payment_proof' => $path,
                'payment_status' => 'pending_verification',
                'payment_method' => 'manual',
                'payment_notes' => __('Manual payment proof submitted by admin.'),
            ]);

            return redirect()
                ->route('admin.personal-dues.index')
                ->with('status', __('Your payment proof has been submitted and is awaiting verification by another admin.'));
        }

        return back()->with('error', __('Failed to upload payment proof. Please try again.'));
    }
    public function receipt(Request $request, Due $due): Response|RedirectResponse
    {
        $admin = $request->user('admin');

        if (!$admin || (int)$due->student_id !== (int)$admin->getAuthIdentifier()) {
            abort(403);
        }

        if ($due->payment_status !== 'paid') {
            return redirect()
                ->route('admin.personal-dues.index')
                ->with('error', __('Receipt is available once the payment is marked as paid.'));
        }

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultPaperOrientation', 'portrait');
        $options->set('defaultPaperSize', 'a4');

        $dompdf = new Dompdf($options);

        $institution = config('app.name', 'GESA Portal');
        $generatedAt = now()->format('F j, Y \a\t g:i A');

        $logoPath = public_path('logo.png');
        $logoData = null;
        if (is_file($logoPath)) {
            $logoContents = @file_get_contents($logoPath);
            if ($logoContents !== false) {
                $logoData = 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode($logoContents);
            }
        }

        $html = view('dashboards.student.dues.receipt', [
            'due' => $due,
            'student' => $admin,
            'institution' => $institution,
            'generatedAt' => $generatedAt,
            'logoData' => $logoData,
        ])->render();

        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->render();

        $filename = sprintf(
            'GESA-receipt-%s.pdf',
            Str::slug($due->payment_reference ?? $due->reference_number ?? ('due-' . $due->due_id))
        );

        $pdfOutput = $dompdf->output();

        return response($pdfOutput, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}

