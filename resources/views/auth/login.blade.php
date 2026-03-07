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
                    <input type="password" name="password" required class="form-control @error('password') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Password">
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
@endsection

