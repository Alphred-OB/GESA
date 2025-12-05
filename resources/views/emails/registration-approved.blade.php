<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Approved</title>
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
        .success-badge {
            background: #dcfce7;
            color: #166534;
            padding: 12px 20px;
            border-radius: 12px;
            text-align: center;
            font-weight: 600;
            margin-bottom: 30px;
            border: 2px solid #86efac;
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
        .credentials {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            padding: 20px;
            border-radius: 12px;
            margin: 20px 0;
        }
        .credentials strong {
            color: #16136a;
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
            text-align: center;
        }
        .cta-button:hover {
            background: #1a1a8a;
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
            <h1>Account Approved</h1>
            <p>Welcome to the GESA Community</p>
        </div>

        <div class="content">
            <div class="success-badge">
                Your registration has been approved
            </div>

            <p>Hello <strong>{{ $user->fullname }}</strong>,</p>

            <p>Great news! Your GESA account registration has been reviewed and <strong>approved</strong> by our administrator. You can now access all features of the GESA Portal.</p>

            <div class="credentials">
                <h3 style="margin-top: 0; color: #16136a;">Your Login Credentials</h3>
                <p><strong>Username:</strong> {{ $user->username }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Password:</strong> The password you created during registration</p>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('login') }}" class="cta-button">
                    Login to Your Account →
                </a>
            </div>

            <div class="info-box">
                <h3>What you can do now:</h3>
                <p>View announcements and departmental updates</p>
                <p>Register for courses online</p>
                <p>Pay dues via Paystack</p>
                <p>Access academic resources</p>
                <p>View upcoming events and add them to your calendar</p>
                <p>Submit suggestions to the executive committee</p>
            </div>

            <p style="margin-top: 30px;">If you have any questions or need assistance, please don't hesitate to contact the GESA executive committee.</p>

            <p>Welcome aboard!</p>
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
