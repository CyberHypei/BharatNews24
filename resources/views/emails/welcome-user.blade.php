<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to {{ config('app.name') }}</title>
</head>
<body style="margin:0; padding:0; font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background-color:#f4f4f4; color:#2c3e50;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#f4f4f4; padding:40px 20px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="max-width:600px; background:#ffffff; border-radius:12px; box-shadow:0 4px 24px rgba(0,0,0,0.08); overflow:hidden;">
                    <!-- Header with gradient -->
                    <tr>
                        <td style="background:linear-gradient(135deg, #f48120 0%, #d66f0a 100%); padding:32px 40px; text-align:center;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center">
                                        <span style="font-size:28px; font-weight:700; color:#ffffff; display:inline-block;">{{ config('app.name') }}</span>
                                        <p style="margin:8px 0 0; font-size:14px; color:rgba(255,255,255,0.9);">Welcome aboard!</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- Welcome message -->
                    <tr>
                        <td style="padding:40px 40px 24px;">
                            <h1 style="margin:0 0 16px; font-size:24px; font-weight:600; color:#2c3e50;">Hello, {{ $user->name }}!</h1>
                            <p style="margin:0; font-size:16px; line-height:1.6; color:#5a6c7d;">
                                Your account has been created successfully. Below are your login credentials. Please keep them secure and change your password after your first login.
                            </p>
                        </td>
                    </tr>
                    <!-- Credentials card -->
                    <tr>
                        <td style="padding:0 40px 32px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#fafbfc; border:1px solid #ecf0f1; border-radius:10px; overflow:hidden;">
                                <tr>
                                    <td style="padding:24px;">
                                        <p style="margin:0 0 16px; font-size:13px; font-weight:600; color:#7f8c8d; text-transform:uppercase; letter-spacing:0.5px;">Your Credentials</p>
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td style="padding:12px 0; border-bottom:1px solid #ecf0f1;">
                                                    <span style="font-size:12px; color:#95a5a6;">Email</span><br>
                                                    <span style="font-size:16px; font-weight:500; color:#2c3e50;">{{ $user->email }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:12px 0;">
                                                    <span style="font-size:12px; color:#95a5a6;">Password</span><br>
                                                    <code style="font-size:15px; font-weight:600; color:#f48120; background:#fff3e6; padding:4px 10px; border-radius:6px; display:inline-block; letter-spacing:1px;">{{ $plainPassword }}</code>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- CTA Button -->
                    <tr>
                        <td style="padding:0 40px 40px; text-align:center;">
                            <a href="{{ $loginUrl }}" style="display:inline-block; background:linear-gradient(135deg, #f48120 0%, #d66f0a 100%); color:#ffffff; font-size:16px; font-weight:600; text-decoration:none; padding:14px 36px; border-radius:8px; box-shadow:0 3px 10px rgba(244,129,32,0.3);">
                                Sign in to your account
                            </a>
                            <p style="margin:20px 0 0; font-size:13px; color:#95a5a6;">
                                Or copy this link: <a href="{{ $loginUrl }}" style="color:#f48120; word-break:break-all;">{{ $loginUrl }}</a>
                            </p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="padding:24px 40px; background:#f8f9fa; border-top:1px solid #ecf0f1;">
                            <p style="margin:0; font-size:12px; color:#95a5a6; text-align:center;">
                                This is an automated message from {{ config('app.name') }}. Please do not share your credentials with anyone.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
