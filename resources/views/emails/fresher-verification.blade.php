<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Registration</title>
</head>
<body style="font-family: sans-serif; background-color: #f3f4f6; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
        <div style="background-color: #16136a; padding: 20px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 24px;">Verify Your Email</h1>
        </div>
        <div style="padding: 30px;">
            <p style="color: #374151; font-size: 16px;">Hello,</p>
            <p style="color: #374151; font-size: 16px;">Thank you for registering with GESA. Please use the verification code below to confirm your email address and complete your application.</p>
            
            <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 15px; text-align: center; margin: 25px 0;">
                <span style="font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #16136a;">{{ $code }}</span>
            </div>
            
            <p style="color: #374151; font-size: 14px;">This code will expire in 15 minutes.</p>
            <p style="color: #374151; font-size: 14px;">If you did not request this, please ignore this email.</p>
        </div>
        <div style="background-color: #f9fafb; padding: 15px; text-align: center; border-top: 1px solid #e5e7eb;">
            <p style="color: #6b7280; font-size: 12px; margin: 0;">&copy; {{ date('Y') }} GESA UMaT. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
