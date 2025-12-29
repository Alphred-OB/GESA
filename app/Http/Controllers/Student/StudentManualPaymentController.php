<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Due;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class StudentManualPaymentController extends Controller
{
    /**
     * Show the manual payment details and upload form.
     */
    public function show(Request $request, Due $due): View|RedirectResponse
    {
        $student = $request->user('student');

        if (!$student || (int)$due->student_id !== (int)$student->getAuthIdentifier()) {
            abort(403);
        }

        if ($due->payment_status === 'paid') {
            return redirect()
                ->route('student.dues.index')
                ->with('status', __('This due was already marked as paid.'));
        }

        if (PaymentSetting::getValue('manual_payment_enabled', '0') !== '1') {
            return redirect()
                ->route('student.dues.index')
                ->with('error', __('Manual payment is currently disabled.'));
        }

        $settings = [
            'merchant_number' => PaymentSetting::getValue('merchant_number', ''),
            'merchant_name' => PaymentSetting::getValue('merchant_name', ''),
            'merchant_network' => PaymentSetting::getValue('merchant_network', ''),
            'manual_payment_instructions' => PaymentSetting::getValue('manual_payment_instructions', ''),
        ];

        return view('dashboards.student.dues.manual-payment', [
            'title' => 'Manual Payment',
            'due' => $due,
            'settings' => $settings,
        ]);
    }

    /**
     * Handle the manual payment submission.
     */
    public function store(Request $request, Due $due): RedirectResponse
    {
        $student = $request->user('student');

        if (!$student || (int)$due->student_id !== (int)$student->getAuthIdentifier()) {
            abort(403);
        }

        if (PaymentSetting::getValue('manual_payment_enabled', '0') !== '1') {
            return redirect()
                ->route('student.dues.index')
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
                'payment_notes' => __('Manual payment proof submitted by student.'),
            ]);

            return redirect()
                ->route('student.dues.index')
                ->with('status', __('Your payment proof has been submitted and is awaiting admin verification.'));
        }

        return back()->with('error', __('Failed to upload payment proof. Please try again.'));
    }
}
