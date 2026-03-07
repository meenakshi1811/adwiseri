@extends('web.layout.main')

@section('main-section')
<div id="loading" style="display: none;">
    <div class="loader"></div>&nbsp;Sending
</div>
  <div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-4 loginouter-box">
            <form id="verifyotp" class="otp-box login-box" method="POST" action="{{ route('verify_password_otp_affiliate') }}">
              @csrf
              <input type="hidden" name="local_time" class="localtime" />
              {{-- <input type="hidden" name="mail" value="{{ $email }}" /> --}}
                <h3 class="mb-5 text-center">Recover Password</h3>
                {{-- <div class="mb-4"> --}}
                  <div class="mb-4">
                    <input id="email" type="email" minlength="3" maxlength="100" name="email" value="{{ old('email') }}" class="form-control mb-2" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Email">

                    <label style="cursor: pointer;" id="send_otp" for="exampleInputmember1" class="form-label mb-4 col-12">Send OTP</label>
                  <!---  <img src="images/pass.png" width="16" height="18" class="useimg" alt=""> --->

                  </div>
                  <input style="display: none;" id="email_otp" type="text" name="email_otp" class="form-control mb-2" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter OTP Sent on Email">
                  <span id="error" style="display: none" class="text-danger" role="alert">
                    <strong>No user found with given email</strong>
                 </span>
                  @if(session()->has('emailerror'))
                        <span class="text-danger" role="alert">
                            <strong>Incorrect OTP</strong>
                        </span>
                    @endif
                  <button style="display: none;" id="submit" type="submit" class="btn btn-primary mb-4 form-control">Confirm</button>
            </form>
        </div>
        <div class="col-lg-4"></div>
    </div>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script>
      $(document).ready(function (){
        @if(session()->has('emailerror'))
            $("#email_otp").css("display","block");
            $("#submit").css("display","block");
        @endif
          $("#send_otp").on('click', function(){
            var email = $("#email").val();
            if(email == ""){
                Swal.fire({
                icon: 'error',
                title: 'Oops..',
                text: 'Please Enter Email.'
                });
            }
            else{
                $.ajax({
                    url: 'password_otp_affiliate',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        email: email,
                    },
                    cache:false,
                    beforeSend: function(){
                        $("#loading").css("display","flex");
                    },
                    success: function(data){
                        if(data == "success"){
                            $("#email_otp").css("display","block");
                            $("#submit").css("display","block");
                            $("#loading").css("display","none");
                        }
                        else
                        {
                          $("#loading").css("display","none");
                          $("#error").css("display","block");

                        }
                    }
                });
            }
          });
      });
  </script>
  @if(session()->has('nouser'))
  <script>
    Swal.fire({
      icon: 'error',
      title: 'Oops..',
      text: 'No user found.'
    })
  </script>
  @endif
@endsection()
