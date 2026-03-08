<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>adwiseri</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: #F5F5F5;
            font-family: 'Lato', sans-serif !important;
            margin: 0;
        }
    </style>
</head>

<body style="background: #F5F5F5;margin:0;">
    <div style="text-align: center;">
        <a class="navbar-brand text-white" href="https://adwiseri.com/">
            <img width="170" src="{{ url('web_assets/images/Style2_blue.png') }}" alt="Adwiseri" />
        </a>
    </div>

    <div style="margin:40px 0;">
        <div style="border-radius:10px;width:50%;background:white;padding-bottom:40px;position:relative;margin:auto;">
            <h2 style="text-align:center;padding:20px 0;">Scheduled Report</h2>
            <div style="padding:0 30px;">
                <div style="margin-bottom:30px;">
                    <p><strong>Hello {{ $data['recipient_name'] }},</strong></p>
                    <p>
                        Please find your Adwiseri scheduled report for the period
                        <strong>{{ $data['start_date'] }} - {{ $data['end_date'] }}</strong>.
                    </p>
                    <p>
                        For your convenience, the report is attached to this email and can also be downloaded using the secure link below.
                    </p>
                </div>

                <div style="margin-bottom:30px;">
                    <p><strong>Download Link:</strong></p>
                    <p>
                        <a href="{{ $data['download_link'] }}">{{ $data['download_link'] }}</a>
                    </p>
                </div>

                <div style="margin-bottom:40px;">
                    <p><strong>Need help?</strong></p>
                    <p>
                        If you have any questions, please contact our support team via live chat or email.
                        We are always happy to help.
                    </p>
                    <p>
                        Regards,<br>
                        <strong>The Adwiseri Team</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <footer style="text-align:center;background:#695EEE;padding:20px 0;color:white;">
        <p style="text-align:center">&copy; {{ date('Y') }} adwiseri. All rights reserved.</p>
        <div style="text-align:center" class="footer-links">
            <a style="text-align:center; color:white;" href="https://adwiseri.com/terms_of_use">Terms of Use</a> |
            <a style="text-align:center; color:white;" href="https://adwiseri.com/privacy_policy">Privacy Policy</a> |
            <a style="text-align:center; color:white;" href="https://adwiseri.com/contactus">Contact Support</a>
        </div>
    </footer>
</body>

</html>
