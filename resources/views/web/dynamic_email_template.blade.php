<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>adwiseri</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body style="background:#F5F5F5;margin:0;font-family:'Lato',sans-serif;">
    <div style="text-align:center;">
        <a class="navbar-brand text-white" href="https://adwiseri.com/">
            <img width="170" src="{{ url('web_assets/images/Style2_blue.png') }}" />
        </a>
    </div>
    <div style="margin:40px 0px;">
        <div style="border-radius:10px;width:50%;background:white;padding:30px;position:relative;margin:auto;">
            {!! $content !!}
        </div>
    </div>
    <footer style="text-align:center;background:#695EEE;padding:20px 0px;color:white;">
        <p style="text-align:center">&copy; {{ date('Y') }} adwiseri. All rights reserved.</p>
        <div style="text-align:center" class="footer-links">
            <a style="text-align:center; color:white;" href="https://adwiseri.com/terms_of_use">Terms of Use</a> |
            <a style="text-align:center; color:white;" href="https://adwiseri.com/privacy_policy">Privacy Policy</a> |
            <a style="text-align:center; color:white;" href="https://adwiseri.com/contactus">Contact Support</a>
        </div>
    </footer>
</body>
</html>
