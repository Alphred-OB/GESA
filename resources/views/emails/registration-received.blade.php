<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Received</title>
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
            background: linear-gradient(135deg, #16136a 0%, #1a1a8a 100%);
            color: #ffffff;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 600;
        }
        .header p {
            margin: 0;
            opacity: 0.9;
            font-size: 16px;
        }
        .content {
            padding: 40px 30px;
        }
        .pending-badge {
            background: #fff7ed;
            color: #9a3412;
            padding: 12px 20px;
            border-radius: 12px;
            text-align: center;
            font-weight: 600;
            margin-bottom: 30px;
            border: 2px solid #fdba74;
        }
        .info-box {
            background: #f0f9ff;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .info-box h3 {
            margin: 0 0 10px 0;
            color: #1e40af;
            font-size: 18px;
        }
        .info-box p {
            margin: 5px 0;
        }
        .footer {
            background: #f8fafc;
            padding: 30px;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }
        .footer a {
            color: #16136a;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Registration Received</h1>
            <p>We've received your application</p>
        </div>

        <div class="content">
            <div class="pending-badge">
                Your registration is pending review
            </div>

            <p>Hello <strong>{{ $registration->first_name }}</strong>,</p>

            <p>Thank you for registering with the GESA Portal. We have received your registration details and they are currently being reviewed by our administrators.</p>

            <div class="info-box">
                <h3>What happens next?</h3>
                <p>1. An administrator will verify your student details.</p>
                <p>2. This process typically takes 24-48 hours.</p>
                <p>3. You will receive another email once your account is approved or if we need more information.</p>
            </div>

            <p>Please keep an eye on your email inbox (and spam folder) for updates regarding your registration status.</p>

            <p style="margin-top: 30px;">If you have any urgent questions, please contact the GESA executive committee.</p>
        </div>

        <div class="footer">
            <p><strong>GESA Portal</strong></p>
            <p>Geomatic Engineering Students' Association</p>
            <p>University of Mines and Technology</p>
            <p style="margin-top: 15px;">
                <a href="{{ route('legal.terms') }}">Terms of Service</a> • 
                <a href="{{ route('legal.privacy') }}">Privacy Policy</a>
            </p>
        </div>
    </div>
</body>
</html>
