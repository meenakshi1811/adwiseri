<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>adwiseri</title>
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

<body style="background: #F5F5F5; margin: 0px;">
    <h1 style="text-align: center; padding: 20px;">adwiseri</h1>
    <div style="margin: 40px 0px;">
        <div style="border-radius: 10px; width: 50%; background: white; padding-bottom: 40px; position: relative; margin: auto;">
            <h2 style="text-align: center; padding: 20px 0px;">Subscription Plan Details</h2>

            <div style="padding: 0px 30px;">
                <div style="margin-bottom: 40px;">
                    <p><strong>Hello {{ $subscriberName }}</strong></p>
                    <p>
                        We are pleased to inform you that {{ $title}}.
                    </p>
                    <p>
                        Plan Details: <strong>{{ $planDetails }}</strong>
                    </p>
                    <p>
                        Validity Duration: <strong>{{ $validityDuration }} Days starting from today</strong>
                    </p>
                </div>
                <div style="margin-bottom: 40px;">
                    <p><strong>Have a question?</strong></p>
                    <p>Check our <strong><a href="https://adwiseri.com/faqs">FAQ Page</a></strong> for a quick answer.</p>
                    <p>
                        You can always contact our support via email - care@adwiseri.com<br><br>
                        We will be happy to help you.<br><br>
                        Thank You,<br>
                        The Adwiseri Team
                    </p>
                </div>
            </div>
        </div>
    </div>

    <footer style="text-align: center; background: #695EEE; margin: 20px 0px; color: white;">
        <p style="text-align: center;">&copy; {{ date('Y') }} Adwiseri. All rights reserved. </p>
    </footer>
</body>

</html>
