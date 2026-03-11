<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- <title>adwiseri</title> -->
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
            body {
                font-family: 'Lato', sans-serif!important;
            }
        </style>

</head>
<style>
      body{
          background: #F5F5F5;
          font-family: 'Lato';
      }
      
  </style>

<body style="background: #F5F5F5;margin:0px;">
    <div style="text-align: center;">
  <a class="navbar-brand text-white" href="https://adwiseri.com/">
    <img width="170" src="{{ url('web_assets/images/Style2_blue.png') }}" />
  </a>
</div>

    <div style="margin:40px 0px;">
        <div style="border-radius: 10px;width:50%;background:white;padding-bottom:40px;position:relative;margin:auto;">
            <!-- <h2 style="text-align:center;padding:20px 0px;">Discount & Offers</h2> -->
             <h2 style="text-align:center;padding:20px 0px;">Credits sent your way</h2>
            {{-- <div style="margin-bottom:20px;">
                <img src="{{ asset('web_assets/images/handshake.png') }}" width="150px" height="auto"
                    style="border:1px solid lightgrey;border-radius:7px;padding:15px 25px;position: relative;margin:auto;" alt="">
            </div> --}}
            <div style="padding: 0px 30px;">
                <div style="margin-bottom:40px;">
                    <p><strong>Dear {{ $name }},</strong></p>
                    <p>
                        <!-- We are excited to inform you about a new offer tailored just for you: -->
                            We are excited to inform you that you have been rewarded with credits, discounts, or additional term benefits on your subscription account. The details are as follows:
                    </p>
                        <p><strong>Transaction Type:</strong> {{ ucwords(str_replace('_', ' ', $type)) }}</p>
                        @if ($type == 'double_term')
                            <p><strong>Description:</strong> Double Term</p>
                            <p><strong>Details:</strong> One additional year has been added to your subscription expiry date.</p>
                        @else
                            <p><strong>Credit Amount:</strong> USD {{ number_format((float) ($credit_amount ?? $value ?? 0), 2) }}</p>
                            <p><strong>Description:</strong> {{ $description ?? 'One-off Credit / Offer / Dispute Resolution' }}</p>
                        @endif
                </div>
                {{-- <div class="col text-center mb-5">
                                <button class="btn btn-primary">Login to your Account</button>
                            </div> --}}
                <div style="margin-bottom:40px;">
                    <p><strong>Have a question?</strong></p>
                    <p>Check our <strong><a href="https://adwiseri.com/faqs">FAQ Page</a></strong> for a quick answer.</p>
                    <p>
                        You can always contact our support team via email - care@adwiseri.com<br><br>
                        We will be happy to help you.<br><br>
                        Thanks,<br>
                        <b>The Adwiseri Team</b>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <footer style="text-align:center;background:#695EEE;padding:20px 0px;color:white;">
        <p style="text-align:center">&copy; {{ date('Y') }} adwiseri. All rights reserved.</p>
        <div style="text-align:center"  class="footer-links">
            <a style="text-align:center; color:white;" href="https://adwiseri.com/terms_of_use">Terms of Use</a> |
<a style="text-align:center; color:white;" href="https://adwiseri.com/privacy_policy">Privacy Policy</a> |
<a style="text-align:center; color:white;" href="https://adwiseri.com/contactus">Contact Support</a>
        </div>
    </footer>
</body>

</html>
