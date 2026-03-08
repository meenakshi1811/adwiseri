<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>adwiseri</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<style>
    body {
        background: #F5F5F5;
        font-family: 'Lato', sans-serif;
    }
</style>

<body style="background: #F5F5F5; margin: 0px;">
    <div style="text-align: center;">
        <a class="navbar-brand text-white" href="https://adwiseri.com/">
            <img width="170" src="https://adwiseri.com/web_assets/images/Style2_blue.png" alt="Adwiseri" />
        </a>
    </div>

    <div style="margin:40px 0px;">
        <div style="border-radius: 10px; width:50%; background:white; padding-bottom:40px; position:relative; margin:auto;">
            <h2 style="text-align:center; padding:20px 0px;">Scheduled Report</h2>

            <div style="padding: 0px 30px;">
                <div style="margin-bottom:40px; color:#333; line-height:1.6;">
                    <p><strong>Hello {{ $recipientName ?? 'Subscriber' }},</strong></p>
                    <p>
                        Your scheduled Adwiseri report for <strong>{{ $startDate->format('d M Y') }}</strong> to
                        <strong>{{ $endDate->format('d M Y') }}</strong> has been generated successfully.
                    </p>

                    @if($deliveryMode === 'attachment')
                        <p>Please find the report attached to this email for your review.</p>
                    @else
                        <p>
                            You can securely download your report using the link below:
                        </p>
                        <p>
                            <a href="{{ $downloadLink }}" style="color:#695EEE; word-break: break-all;">
                                Download Scheduled Report
                            </a>
                        </p>
                        <p style="font-size: 13px; color:#666;">For security reasons, this link will expire in 7 days.</p>
                    @endif
                </div>

                <div style="margin-bottom:40px; color:#333; line-height:1.6;">
                    <p><strong>Need assistance?</strong></p>
                    <p>Visit our <strong><a href="https://adwiseri.com/faqs">FAQ Page</a></strong> for quick help.</p>
                    <p>
                        If you have any questions, please contact our support team at care@adwiseri.com.<br><br>
                        Regards,<br>
                        <b>The Adwiseri Team</b>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <footer style="text-align:center; background:#695EEE; padding:20px 0px; color:white;">
        <p style="text-align:center">&copy; {{ date('Y') }} adwiseri. All rights reserved.</p>
        <div style="text-align:center" class="footer-links">
            <a style="text-align:center; color:white;" href="https://adwiseri.com/terms_of_use">Terms of Use</a> |
            <a style="text-align:center; color:white;" href="https://adwiseri.com/privacy_policy">Privacy Policy</a> |
            <a style="text-align:center; color:white;" href="https://adwiseri.com/contactus">Contact Support</a>
        </div>
    </footer>
</body>

</html>
