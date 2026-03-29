@extends('layouts.app')

@section('content')
<!-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-4 loginouter-box p-4" style="border: 1.27184px solid #8D2E28;
    box-shadow: 0px 0px 6.35922px 2.54369px rgba(0, 0, 0, 0.25);
    border-radius: 5px;">
            <form id="loginform" class="login-box" method="POST" action="{{ route('login') }}">
                @csrf
                <h3 class="mb-5 text-center">Login</h3>
                <div class="log-img mb-5 d-flex justify-content-center align-items-center">
                    <img src="{{ asset('web_assets/images/loginimg.png') }}" width="60" height="60" class="log-img" alt="">
                </div>
                <div class="mb-4">
                    <input type="email" name="email" required class="form-control @error('email') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('email') }}" placeholder="Email">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  <!---  <img src="{{ asset('web_assets/images/user.png') }}" width="20" height="20" class="useimg" alt=""> --->
                  </div>
                  <div class="mb-4">
                    <div style="position: relative;">
                        <input type="password" name="password" required class="form-control @error('password') is-invalid @enderror" id="auth-login-password" aria-describedby="emailHelp" placeholder="Password">
                        <button type="button" class="toggle-password-visibility"
                            style="position:absolute;top:50%;right:12px;transform:translateY(-50%);border:none;background:transparent;padding:0;cursor:pointer;font-size:18px;"
                            aria-label="Show password" data-target="auth-login-password">
                            <svg class="password-eye-icon eye-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="18" height="18" aria-hidden="true">
                                    <path fill="currentColor" d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13.1 13.1 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13.1 13.1 0 0 1 14.828 8q-.087.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13.1 13.1 0 0 1 1.172 8z"></path>
                                    <path fill="currentColor" d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"></path>
                                </svg>
                                <svg class="password-eye-icon eye-closed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="18" height="18" aria-hidden="true" style="display:none;">
                                    <path fill="currentColor" d="M13.359 11.238 15.646 13.525l-.708.707-2.325-2.325A8.6 8.6 0 0 1 8 13.5C3 13.5 0 8 0 8a15.7 15.7 0 0 1 3.083-3.807L.354 1.464l.708-.707 14 14-.707.707zM11.297 10.63 9.772 9.106A2.5 2.5 0 0 1 6.894 6.23L5.369 4.704A6.4 6.4 0 0 0 2.11 8c.74 1.188 2.615 3.5 5.89 3.5 1.235 0 2.341-.329 3.297-.87m-3.766-4.47 2.31 2.31A1.5 1.5 0 0 0 7.53 6.16"></path>
                                    <path fill="currentColor" d="m10.79 7.76 1.99 1.99c.596-.55 1.087-1.152 1.454-1.75-.74-1.188-2.615-3.5-5.89-3.5-.794 0-1.525.136-2.187.376l1.607 1.607A3.5 3.5 0 0 1 10.79 7.76"></path>
                                </svg>
                        </button>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  <!---  <img {{ asset('web_assets/images/pass.png') }}" width="16" height="18" class="useimg" alt=""> --->
                  </div>

                  <div class="form-group mb-4" style="margin-bottom: 50px;">
                    {!! NoCaptcha::renderJs() !!}
                    {!! NoCaptcha::display() !!}
                    {{-- @error('g-recaptcha-response')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror --}}
                </div>
                  <button type="submit" class="btn btn-primary form-control">Login</button>

                  <!-- <p class="register-button text-center">New here? <a href="{{ route('user_register') }}">Register Now</a> </p>-->
                  <p class="register-button mt-3 text-center"><strong>New here?</strong> <a href="{{ route('user_register') }}" style="text-decoration: none;background:none;border:none;font-weight:400;">Register
                    Now</a>
            </p>
            <p class="text-center"><a style="text-decoration: none;background:none;border:none;font-weight:400;"
                    href="{{ route('forget_password') }}">Forgot Password?</a></p>
                </form>
        </div>
        <div class="col-lg-4"></div>
    </div>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  @error('g-recaptcha-response')
      <script>
         Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: 'Please complete the reCAPTCHA to proceed.',
            });
      </script>
      @enderror
  <script>
    document.querySelectorAll('.toggle-password-visibility').forEach(function(toggleButton) {
        toggleButton.addEventListener('click', function() {
            const passwordField = document.getElementById(this.getAttribute('data-target'));
            if (!passwordField) return;

            const isPassword = passwordField.type === 'password';
            passwordField.type = isPassword ? 'text' : 'password';
            const eyeOpenIcon = this.querySelector('.eye-open');
            const eyeClosedIcon = this.querySelector('.eye-closed');
            if (eyeOpenIcon && eyeClosedIcon) {
                eyeOpenIcon.style.display = isPassword ? 'none' : 'inline-block';
                eyeClosedIcon.style.display = isPassword ? 'inline-block' : 'none';
            }
            this.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
        });
    });
  </script>
@endsection
