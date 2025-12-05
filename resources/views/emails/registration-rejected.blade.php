<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Update</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #1e293b;
            background-color: #f8fafc;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #881337 0%, #9f1239 100%);
            color: #ffffff;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 600;
        }
        .content {
            padding: 40px 30px;
        }
        .warning-badge {
            background: #fef3c7;
            color: #92400e;
            padding: 12px 20px;
            border-radius: 12px;
            text-align: center;
            font-weight: 600;
            margin-bottom: 30px;
            border: 2px solid #fcd34d;
        }
        .info-box {
            background: #fef2f2;
            border-left: 4px solid #dc2626;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .info-box p {
            margin: 5px 0;
        }
        .cta-button {
            display: inline-block;
            background: #16136a;
            color: #ffffff;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            margin: 20px 0;
        }
        .footer {
            background: #f8fafc;
            padding: 30px;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Registration Update</h1>
        </div>

        <div class="content">
            <div class="warning-badge">
                Registration Not Approved
            </div>

            <p>Hello <strong>{{ $registration->first_name }} {{ $registration->last_name }}</strong>,</p>

            <p>Thank you for your interest in joining the GESA community. After reviewing your registration request, we are unable to approve your account at this time.</p>

            @if($registration->admin_notes)
            <div class="info-box">
                <p><strong>Admin Notes:</strong></p>
                <p>{{ $registration->admin_notes }}</p>
            </div>
            @endif

            <p><strong>What should you do next?</strong></p>
            <ul>
                <li>Please visit the GESA office during office hours</li>
                <li>Bring your Student ID or admission documents</li>
                <li>Speak with a member of the executive committee for clarification</li>
            </ul>

            <p>We're here to help! If you believe this decision was made in error or if you have additional documentation to support your registration, please contact us directly.</p>

            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ route('auth.fresher-register') }}" class="cta-button">
                    Submit New Registration
                </a>
            </div>
        </div>

        <div class="footer">
            <p><strong>GESA Portal</strong></p>
            <p>Geomatic Engineering Students' Association</p>
            <p>University of Mines and Technology</p>
        </div>
    </div>
</body>
</html>
