<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { text-align: center; margin-bottom: 30px; }
        .content { margin-bottom: 30px; }
        .footer { font-size: 12px; color: #777; text-align: center; }
        .button { display: inline-block; padding: 12px 24px; background-color: #16136a; color: #fff; text-decoration: none; border-radius: 5px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Payment Approved</h2>
        </div>
        <div class="content">
            <p>Dear {{ $due->student->fullname }},</p>
            <p>Your manual payment for <strong>{{ $due->description }}</strong> has been verified and approved by the financial secretary.</p>
            <p><strong>Amount:</strong> GHS {{ number_format((float) $due->amount, 2) }}</p>
            <p><strong>Status:</strong> Paid</p>
            <p>You can now download your official receipt from the student portal.</p>
            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ route('student.payments.paystack.receipt', $due) }}" class="button">Download Receipt</a>
            </div>
        </div>
        <div class="footer">
            <p>This is an automated email from the GESA Student Portal. Please do not reply.</p>
        </div>
    </div>
</body>
</html>
