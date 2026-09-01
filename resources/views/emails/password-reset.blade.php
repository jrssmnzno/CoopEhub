<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Request</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
            color: #ffffff;
            padding: 2rem;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .header h1 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 700;
        }

        .content {
            padding: 2rem;
        }

        .greeting {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            color: #212529;
        }

        .message {
            margin-bottom: 1.5rem;
            color: #525252;
            font-size: 0.95rem;
        }

        .button-container {
            text-align: center;
            margin: 2rem 0;
        }

        .reset-button {
            display: inline-block;
            background-color: #0d6efd;
            color: #ffffff;
            padding: 0.75rem 2rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .reset-button:hover {
            background-color: #0b5ed7;
            text-decoration: none;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 2rem;
            text-align: center;
            border-top: 1px solid #dee2e6;
            font-size: 0.85rem;
            color: #6c757d;
            border-radius: 0 0 8px 8px;
        }

        .footer p {
            margin: 0.5rem 0;
        }

        .link {
            color: #0d6efd;
            text-decoration: none;
            word-break: break-all;
        }

        .expiration-notice {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            color: #856404;
            font-size: 0.9rem;
        }

        .security-notice {
            background-color: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 1rem;
            margin-top: 1.5rem;
            border-radius: 4px;
            color: #721c24;
            font-size: 0.9rem;
        }

        .security-notice strong {
            display: block;
            margin-bottom: 0.5rem;
        }

        .divider {
            border-top: 1px solid #dee2e6;
            margin: 1.5rem 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>Password Reset Request</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Hello {{ $user->name }},
            </div>

            <div class="message">
                We received a request to reset the password for your COOP Ehub account. If you didn't make this request, you can safely ignore this email.
            </div>

            <div class="expiration-notice">
                <strong>⏱️ This link expires in 60 minutes</strong>
                <br>
                For security reasons, this password reset link is only valid for the next hour. If you don't reset your password by then, you'll need to request a new link.
            </div>

            <div class="button-container">
                <a href="{{ $resetUrl }}" class="reset-button">Reset Password</a>
            </div>

            <div class="message">
                Or copy and paste this link in your browser:
                <br>
                <a href="{{ $resetUrl }}" class="link">{{ $resetUrl }}</a>
            </div>

            <div class="divider"></div>

            <div class="message">
                If you don't reset your password, your account remains secure. Your password will not be changed unless you complete the reset process.
            </div>

            <div class="security-notice">
                <strong>🔒 Security Tip:</strong>
                Never share your password reset link with anyone. COOP Ehub support will never ask for your password or reset link.
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ now()->year }} COOP Ehub. All rights reserved.</p>
            <p>This is an automated email. Please do not reply directly to this message.</p>
            <p>If you have questions, please contact your administrator.</p>
        </div>
    </div>
</body>
</html>
