<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { text-align: center; margin-bottom: 30px; }
        .content { margin-bottom: 30px; }
        .footer { font-size: 12px; color: #777; text-align: center; }
        .rejection-box { background-color: #fff1f2; border: 1px solid #fecdd3; border-radius: 8px; padding: 15px; margin-top: 20px; color: #9f1239; }
        .button { display: inline-block; padding: 12px 24px; background-color: #16136a; color: #fff; text-decoration: none; border-radius: 5px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="color: #e11d48;">Payment Rejected</h2>
        </div>
        <div class="content">
            <p>Dear {{ $due->student->fullname }},</p>
            <p>Your manual payment for <strong>{{ $due->description }}</strong> has been reviewed and unfortunately rejected.</p>
            
            <div class="rejection-box">
                <strong>Reason for Rejection:</strong><br>
                {{ $due->rejection_reason ?: "No specific reason provided. Please contact the financial secretary for more details." }}
            </div>
            
            <p style="margin-top: 30px;">Please review the rejection reason and re-submit a valid proof of payment from the student portal.</p>
            
            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ route('student.dues.index') }}" class="button">Go to Dues</a>
            </div>
        </div>
        <div class="footer">
            <p>This is an automated email from the GESA Student Portal. Please do not reply.</p>
        </div>
    </div>
</body>
</html>
