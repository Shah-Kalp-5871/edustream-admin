<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f4f7f9;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f4f7f9;
            padding-bottom: 40px;
        }
        .main {
            background-color: #ffffff;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-spacing: 0;
            color: #2d3748;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-top: 40px;
        }
        .header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            padding: 40px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.025em;
        }
        .content {
            padding: 40px;
            text-align: center;
        }
        .content h2 {
            font-size: 20px;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 24px;
        }
        .otp-container {
            background-color: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 24px;
            margin: 30px 0;
            display: inline-block;
        }
        .otp-code {
            font-size: 42px;
            font-weight: 800;
            letter-spacing: 8px;
            color: #1e3a8a;
            margin: 0;
        }
        .footer {
            padding: 24px;
            text-align: center;
            font-size: 14px;
            color: #718096;
            background-color: #f8fafc;
        }
        .expire-text {
            color: #e53e3e;
            font-weight: 500;
            margin-top: 20px;
        }
        .ignore-text {
            font-size: 12px;
            color: #a0aec0;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <table class="main" role="presentation">
            <tr>
                <td class="header">
                    <h1>EduStream</h1>
                </td>
            </tr>
            <tr>
                <td class="content">
                    <h2>{{ $purpose === 'signup' ? 'Verify Your Account' : 'Login Verification' }}</h2>
                    <p>Hello,</p>
                    <p>Use the following One-Time Password (OTP) to complete your {{ $purpose === 'signup' ? 'registration' : 'login' }}.</p>
                    
                    <div class="otp-container">
                        <p class="otp-code">{{ $otp }}</p>
                    </div>

                    <p class="expire-text">This code will expire in 5 minutes.</p>
                    
                    <p class="ignore-text">If you did not request this code, please ignore this email or contact support if you have concerns.</p>
                </td>
            </tr>
            <tr>
                <td class="footer">
                    &copy; {{ date('Y') }} EduStream Academy. All rights reserved.
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
