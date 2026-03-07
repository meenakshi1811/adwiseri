@extends('web.layout.main')

@section('main-section')
  <div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-lg-4"></div>
        <div style="margin-top: 50px;" class="col-lg-4 loginouter-box">
            <form id="verifyotp" class="otp-box login-box" method="POST" action="{{ route('verify_otp') }}">
              @csrf
              <input type="hidden" name="local_time" class="localtime" />
              <input type="hidden" name="email" id="email" value="{{ $email }}" />
                <h3 class="mb-5 text-center">OTP Verification</h3>

                <div class="mb-4">
                    <input type="text" name="otp" class="form-control mb-2" id="exampleInputOTP" aria-describedby="otpHelp" placeholder="Enter OTP">
                    <p id="suc_msg" class="bg-success text-white" style="display: none;">OTP Sent Successfully.</p>
                    <p id="err_msg" class="bg-danger text-white" style="display: none;">OTP Not Sent.</p>
                    @if(session('emailerror'))
                        <span class="text-danger" role="alert">
                            <strong>{{ session('emailerror')}}</strong>
                        </span>
                    @endif
                    <div class="d-flex justify-content-between mt-2">
                        <span id="timer" class="text-muted" style="font-size: 12px;"></span>
                        <button type="button" class="btn btn-link p-0" id="otp_btn" onclick="send_email()" style="font-size:12px;">Resend OTP</button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mb-4 form-control">Confirm</button>
            </form>
        </div>
        <div class="col-lg-4"></div>
    </div>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script>
    let timerOn = true;

    function timer(remaining) {
        document.getElementById('timer').innerHTML = ` ${remaining} sec`;
        remaining -= 1;

        if (remaining >= 0 && timerOn) {
            setTimeout(function () {
                timer(remaining);
            }, 1000);
            return;
        }

        if (!timerOn) return;

        document.getElementById('otp_btn').style.display = 'none';
        document.getElementById('timer').innerHTML = '';
    }

    // Start timer with 120 seconds
    timer(120);

    function send_email() {
        var email = $("#email").val();
        $('#otp_btn').hide();

        $.ajax({
            url: "{{ route('send_otp') }}",
            method: 'GET',
            data: { email: email },
            cache: false,
            success: function(data) {
                if (data == "Success") {
                    $('#suc_msg').show();
                    setTimeout(() => $('#suc_msg').hide(), 2000);
                } else if (data == "Error") {
                    $('#err_msg').show();
                    setTimeout(() => $('#err_msg').hide(), 2000);
                }
                timer(120);
            }
        });
    }
  </script>

  @if(session()->has('nouser'))
  <script>
    Swal.fire({
      icon: 'error',
      title: 'Oops..',
      text: 'No user found.'
    });
  </script>
  @endif
@endsection()
