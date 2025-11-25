<!DOCTYPE html>
<html>
<head>
    <title>Password Reset Code</title>
</head>
<body style="font-family: sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2 style="color: #8B0000; margin-top: 0;">Password Reset Request</h2>
        <p>Hello,</p>
        <p>You are receiving this email because we received a password reset request for your account.</p>
        <p>Your verification code is:</p>
        <div style="background-color: #f0f0f0; padding: 15px; text-align: center; font-size: 24px; font-weight: bold; letter-spacing: 5px; border-radius: 4px; margin: 20px 0;">
            {{ $code }}
        </div>
        <p>This code will expire in 10 minutes.</p>
        <p>If you did not request a password reset, no further action is required.</p>
        <br>
        <p>Regards,<br>WMSU REO Team</p>
    </div>
</body>
</html>
