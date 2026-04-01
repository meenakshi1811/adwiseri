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
        font-family: 'Lato';
    }

    .footer-links a {
        color: white;
        text-decoration: underline;
        margin: 0 10px;
        font-size: 14px;
    }

    .footer-links {
        margin-top: 10px;
    }
</style>

<body style="background: #F5F5F5; margin:0px;">
    <div style="margin:40px 0px;">
        <div style="border-radius: 10px;width:50%;background:white;padding-bottom:40px;position:relative;margin:auto;">
            <div style="background:#695EEE;padding:16px 0px;text-align:center;border-radius:10px 10px 0 0;">
                <a class="navbar-brand text-white" href="https://adwiseri.com/">
                    <img width="170" src="{{ url('web_assets/images/Style2.png') }}" alt="adwiseri logo" />
                </a>
            </div>
            <h2 style="text-align:center;padding:20px 0px;">Welcome to Adwiseri</h2>
            <div style="margin-bottom:20px;text-align:center;">
                <img src="{{ url('web_assets/images/handshake.png') }}" width="150px" height="auto"
                    style="border:1px solid lightgrey;border-radius:7px;padding:15px 25px;position: relative;margin:auto;" alt="Welcome handshake">
            </div>
            <div style="padding: 0px 30px;">
                @if(!empty($content))
                    {!! $content !!}
                @else
                    <div style="margin-bottom:40px;">
                        <h2>Congratulations!<br>You have successfully purchased the subscription</h2>
                        <p><strong>Hello {{ $data->name }},</strong></p>
                        <p>
                            Your registration at <strong>adwiseri</strong> is successful. Enjoy the benefits and services.<br>
                            @if(isset($data->subscription))
                            You have successfully purchased the subscription. The subscription details are as follows:<br><br>
                            Subscription Type (Plan) :- {{$data->subscription_type ?? $data->plan_name}}<br>
                            Start Date :- {{$data->start_date ?? '-'}}<br>
                            End Date :- {{$data->end_date ?? '-'}}<br>
                            Paid Amount :- USD {{$data->paid_amount ?? $data->amount}}<br><br>
                            View invoice: <a href="{{route('invoice_preview', $data->invoice_id .'/'. $data->token)}}">Click here</a>
                            @else
                            Your <strong>Free Plan</strong> is activated successfully. The plan details are as follows:<br><br>
                            Plan Name : {{$data->plan_name}}<br>
                            Duration : {{$data->duration}}<br>
                            Paid Amount : $0<br>
                            @endif
                        </p>
                    </div>

                    <div style="margin-bottom:40px;">
                        <p><strong>Have a question?</strong></p>
                        <p>Check our <strong><a href="https://adwiseri.com/faqs">FAQ Page</a></strong> for a quick answer.</p>
                        <p>
                            You can always contact our support team via live chat and email.<br>
                            We will be happy to help you!<br><br>
                            Thanks,<br>
                            <b>The Adwiseri Team</b>
                        </p>
                    </div>
                @endif
            </div>
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
