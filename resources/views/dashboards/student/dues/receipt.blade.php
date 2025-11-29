<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
    <style>
        /* PDF RESET & BASICS 
           Using exact A4 dimensions minus margins to prevent content cropping 
        */
        @page {
            margin: 0;
            size: A4;
        }
        
        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #1e293b; /* Slate 800 */
            background-color: #fff;
        }

        /* Main Container 
           A4 width is 210mm. We set max-width to 190mm to ensure 10mm margins 
           on both sides, preventing the right side from being cropped.
        */
        .invoice-box {
            width: 100%;
            max-width: 190mm; 
            margin: 0 auto; /* Centers it on the page */
            padding: 30px 0; /* Vertical padding only */
        }

        /* UTILITIES */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .text-light { color: #64748b; } /* Slate 500 */
        .text-xs { font-size: 10px; letter-spacing: 1px; }
        
        /* HEADER */
        .header-table {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #1e293b;
            padding-bottom: 15px;
        }
        
        .logo-img {
            max-height: 60px;
            max-width: 150px;
            object-fit: contain;
        }

        .brand-name {
            font-size: 18px;
            font-weight: 800;
            margin-top: 5px;
        }

        .receipt-title {
            font-size: 32px;
            font-weight: 900;
            color: #e2e8f0; /* Very light gray */
            text-transform: uppercase;
            line-height: 0.8;
            margin-bottom: 5px;
        }
        
        .receipt-number {
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
        }

        /* INFO SECTION */
        .info-table {
            width: 100%;
            margin-bottom: 25px;
        }
        
        .info-table td {
            vertical-align: top;
            width: 50%;
        }

        .info-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            margin-bottom: 4px;
            display: block;
            font-weight: 700;
        }

        .info-value {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 12px;
            display: block;
        }

        /* PAID BADGE */
        .badge-container {
            text-align: right;
            padding-top: 10px;
            padding-right: 5px; /* Extra buffer from edge */
        }
        
        .status-badge {
            display: inline-block;
            border: 2px solid #10b981; /* Green */
            color: #10b981;
            font-weight: 800;
            font-size: 16px;
            padding: 8px 20px;
            border-radius: 4px;
            text-transform: uppercase;
            transform: rotate(-5deg);
            opacity: 0.9;
            background: #fff; /* Ensure background covers lines if needed */
        }

        /* SUMMARY BOX */
        .summary-box {
            background-color: #f8fafc;
            border-left: 4px solid #1e293b;
            padding: 15px 20px;
            margin-bottom: 25px;
        }

        .summary-table {
            width: 100%;
        }

        .total-amount {
            font-size: 24px;
            font-weight: 800;
            color: #1e293b;
        }

        /* ITEMS TABLE */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table th {
            text-align: left;
            padding: 10px 0;
            border-bottom: 1px solid #cbd5e1;
            font-size: 10px;
            text-transform: uppercase;
            color: #64748b;
        }

        .items-table td {
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        /* FOOTER */
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px dashed #cbd5e1;
            font-size: 10px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        
        <!-- Header Table -->
        <table class="header-table">
            <tr>
                <td style="vertical-align: middle;">
                    @if ($logoData)
                        <img src="{{ $logoData }}" alt="Logo" class="logo-img">
                    @endif
                    <div class="brand-name">{{ $institution }}</div>
                </td>
                <td style="text-align: right; vertical-align: middle;">
                    <div class="receipt-title">Receipt</div>
                    <div class="receipt-number">#{{ $due->payment_reference ?? $due->reference_number ?? ('DUE-' . $due->due_id) }}</div>
                    <div class="text-light text-xs">{{ $generatedAt }}</div>
                </td>
            </tr>
        </table>

        <!-- Info Grid -->
        <table class="info-table">
            <tr>
                <!-- Column 1: Student -->
                <td>
                    <span class="info-label">Billed To</span>
                    <span class="info-value" style="font-size: 14px;">{{ $student->fullname ?? $student->username }}</span>
                    
                    <div style="line-height: 1.5; color: #475569;">
                        Reference Number : {{ $student->index_number ?? $student->username }}<br>
                        {{ $student->class ?? '' }} {{ $student->year ? '· Year '.$student->year : '' }}<br>
                        {{ $student->email ?? '' }}
                    </div>
                </td>
                
                <!-- Column 2: Payment Details -->
                <td class="text-right">
                    <span class="info-label">Payment Method</span>
                    <span class="info-value">Online / Portal</span>

                    <span class="info-label" style="margin-top: 10px;">Reference ID</span>
                    <span class="info-value">{{ $due->payment_reference ?? '—' }}</span>

                    <!-- Dynamic Badge -->
                    @if(strtolower($due->payment_status) == 'paid')
                        <div class="badge-container">
                            <span class="status-badge">PAID</span>
                        </div>
                    @else
                        <div class="badge-container">
                            <span class="status-badge" style="border-color: #ef4444; color: #ef4444;">UNPAID</span>
                        </div>
                    @endif
                </td>
            </tr>
        </table>

        <!-- Highlight Box -->
        <div class="summary-box">
            <table class="summary-table">
                <tr>
                    <td colspan="2" style="vertical-align: middle;">
                        <span class="info-label">Student name</span>
                        <span class="info-value">{{ $student->fullname ?? $student->username }}</span>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: middle;">
                        <span class="info-label" style="margin-bottom: 0;">Total Amount Paid</span>
                    </td>
                    <td class="text-right" style="vertical-align: middle;">
                        <span class="total-amount">GHS {{ number_format((float) $due->amount, 2) }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Line Items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Due Date</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <span style="font-weight: 600; font-size: 13px;">{{ $due->description }}</span>
                        @if(isset($due->academic_year))
                            <br><span class="text-light" style="font-size: 10px;">Academic Year: {{ $due->academic_year }}</span>
                        @endif
                    </td>
                    <td class="text-right">{{ optional($due->due_date)->format('d M Y') ?? '—' }}</td>
                    <td class="text-right" style="font-weight: bold;">
                        GHS {{ number_format((float) $due->amount, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Footer (Simplified, No Signature) -->
        <div class="footer">
            <strong>Thank you for your payment.</strong><br>
            This is a system-generated receipt from the GESA Student Portal.
        </div>
    </div>
</body>
</html>