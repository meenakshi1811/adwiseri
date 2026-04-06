<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>adwiseri</title>
</head>
<body style="margin:0;padding:0;background:#f3f5fb;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
    <div style="width:100%;padding:28px 12px;">
        <div style="max-width:640px;margin:0 auto;background:#ffffff;border-radius:10px;border:1px solid #e5e7eb;overflow:hidden;">
            <div style="background:#1f4bb8;color:#ffffff;padding:18px 24px;text-align:center;">
                <a href="https://adwiseri.com/" style="display:inline-block;">
                    <img width="170" src="{{ url('web_assets/images/Style2.png') }}" alt="Adwiseri" style="max-width:100%;height:auto;">
                </a>
            </div>
            <div style="padding:24px;font-size:14px;line-height:1.7;">
                {!! $content !!}
            </div>
            <div style="padding:16px 24px 22px;color:#6b7280;font-size:12px;border-top:1px solid #e5e7eb;background:#fafafa;text-align:center;">
                <p style="margin:0 0 8px;">&copy; {{ date('Y') }} adwiseri. All rights reserved.</p>
                <p style="margin:0;">
                    <a style="color:#1f4bb8;text-decoration:none;" href="https://adwiseri.com/terms_of_use">Terms of Use</a>
                    <span style="color:#9ca3af;"> | </span>
                    <a style="color:#1f4bb8;text-decoration:none;" href="https://adwiseri.com/privacy_policy">Privacy Policy</a>
                    <span style="color:#9ca3af;"> | </span>
                    <a style="color:#1f4bb8;text-decoration:none;" href="https://adwiseri.com/contactus">Contact Support</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
