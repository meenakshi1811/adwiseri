@extends('web.layout.main')

@section('main-section')
<div id="loading" style="display: none;">
    <div class="loader"></div>&nbsp;Sending
</div>
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-4 loginouter-box">
            <form id="verifyotp" class="otp-box login-box" method="POST">
                @csrf
                <input type="hidden" name="local_time" class="localtime" />
                <h3 class="mb-5 text-center">Recover Password</h3>

                <!-- Email Input -->
                <div class="mb-4">
                    <input id="email" type="email" minlength="3" maxlength="100" name="email" value="{{ old('email') }}" class="form-control mb-2" placeholder="Enter Email">
                    @error('email')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <!-- Google Captcha for Send OTP -->
                <div class="form-group mb-4 send_otp_captcha">
                    {!! NoCaptcha::renderJs() !!}
                    <div id="captcha_send"></div>
                    @error('g-recaptcha-response')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <!-- Send OTP Button -->
                <button type="button" id="send_otp" class="btn btn-primary mb-4 form-control">Send OTP</button>

                <!-- OTP Section -->
                <div id="otp_section" style="display: none;">
                    <input id="email_otp" type="text" name="email_otp" class="form-control mb-2" placeholder="Enter OTP">
                    @if(session()->has('emailerror'))
                    <span class="text-danger" role="alert">
                        <strong>Incorrect OTP</strong>
                    </span>
                    @endif

                    <!-- Google Captcha for Confirm OTP -->
                    <div class="form-group mb-4 ">
                        <div id="captcha_verify"></div>
                        @error('g-recaptcha-response')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <!-- Confirm OTP Button -->
                    <button id="verify_otp" type="button" class="btn btn-primary mb-4 form-control">Confirm</button>
                </div>
            </form>
        </div>
        <div class="col-lg-4"></div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<script>
let sendCaptcha, verifyCaptcha;

function onloadCallback() {
    sendCaptcha = grecaptcha.render('captcha_send', {
        'sitekey': '{{ config("captcha.sitekey") }}'
    });
    verifyCaptcha = grecaptcha.render('captcha_verify', {
        'sitekey': '{{ config("captcha.sitekey") }}'
    });
}

$(document).ready(function () {
    // Send OTP
    $("#send_otp").on("click", function () {
        const email = $("#email").val();
        const recaptcha = grecaptcha.getResponse(sendCaptcha);

        if (!email) {
            Swal.fire({ icon: "error", title: "Oops...", text: "Please enter your email." });
            return;
        }

        if (!recaptcha) {
            Swal.fire({ icon: "error", title: "Oops...", text: "Please complete the reCAPTCHA." });
            grecaptcha.reset(sendCaptcha);
            return;
        }

        $.ajax({
            url: "{{ route('verify_password_otp') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                email: email,
                "g-recaptcha-response": recaptcha,
                action: "send_otp",
            },
            success: function (response) {
                if (response.status === "success") {
                    Swal.fire({ icon: "success", title: "Success", text: response.message });

                    $("#otp_section").show();
                    $("#send_otp").hide();
                    $('.send_otp_captcha').hide();

                    // Reset confirm OTP captcha when section is shown
                    grecaptcha.reset(verifyCaptcha);
                } else {
                    Swal.fire({ icon: "error", title: "Error", text: response.message });
                    grecaptcha.reset(sendCaptcha);
                }
            },
        });
    });

    // Verify OTP
    $("#verify_otp").on("click", function () {
        const email = $("#email").val();
        const emailOtp = $("#email_otp").val();
        const recaptcha = grecaptcha.getResponse(verifyCaptcha);

        if (!emailOtp) {
            Swal.fire({ icon: "error", title: "Oops...", text: "Please enter the OTP." });
            return;
        }

        if (!recaptcha) {
            Swal.fire({ icon: "error", title: "Oops...", text: "Please complete the reCAPTCHA." });
            grecaptcha.reset(verifyCaptcha);
            return;
        }

        $.ajax({
            url: "{{ route('verify_password_otp') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                email: email,
                email_otp: emailOtp,
                "g-recaptcha-response": recaptcha,
                action: "verify_otp",
            },
            success: function (response) {
                if (response.status === "success") {
                    Swal.fire({ icon: "success", title: "Success", text: response.message })
                        .then(() => { window.location.href = response.redirect_url; });
                } else {
                    Swal.fire({ icon: "error", title: "Error", text: response.message });
                    grecaptcha.reset(verifyCaptcha);
                }
            },
        });
    });
});
</script>

<!-- Explicit reCAPTCHA render -->
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
@endsection
