<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminPaymentSettingController extends Controller
{
    /**
     * Display the payment settings page.
     */
    public function index(): View
    {
        $settings = [
            'manual_payment_enabled' => PaymentSetting::getValue('manual_payment_enabled', '0'),
            'merchant_number' => PaymentSetting::getValue('merchant_number', ''),
            'merchant_name' => PaymentSetting::getValue('merchant_name', ''),
            'merchant_network' => PaymentSetting::getValue('merchant_network', ''),
            'manual_payment_instructions' => PaymentSetting::getValue('manual_payment_instructions', ''),
        ];

        return view('dashboards.admin.dues.payment-settings', [
            'title' => 'Payment Settings',
            'settings' => $settings,
        ]);
    }

    /**
     * Update the payment settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $settings = $request->validate([
            'manual_payment_enabled' => 'required|in:0,1',
            'merchant_number' => 'required_if:manual_payment_enabled,1|nullable|string|max:20',
            'merchant_name' => 'required_if:manual_payment_enabled,1|nullable|string|max:100',
            'merchant_network' => 'required_if:manual_payment_enabled,1|nullable|string|max:50',
            'manual_payment_instructions' => 'required_if:manual_payment_enabled,1|nullable|string|max:2000',
        ]);

        $adminId = $request->user('admin')->user_id;

        foreach ($settings as $key => $value) {
            PaymentSetting::setValue($key, $value ?? '', $adminId);
        }

        return redirect()
            ->route('admin.payment-settings.index')
            ->with('status', __('Payment settings updated successfully.'));
    }
}
