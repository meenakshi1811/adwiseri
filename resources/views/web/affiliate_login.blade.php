@extends('web.layout.main')

@section('main-section')
   <main class="py-4" style="padding-top:4.5rem!important;">
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4 loginouter-box">
                <form id="loginform" class="login-box" method="POST" action="{{ route('affiliate.storeLogin') }}">
                    @csrf
                    <input type="hidden" name="local_time" class="localtime" />
                    <h3 class="mb-5 text-center">Affiliate  Login</h3>


                    <div class="log-img mb-5">
                        <img src="{{ asset('web_assets/images/loginimg.png') }}" width="60" height="60"
                            class="log-img" alt="">
                    </div>

                    <div class="mb-4">
                        <input type="email" name="email" required
                            class="form-control @error('email') is-invalid @enderror" id="exampleInputEmail1"
                            aria-describedby="emailHelp" value="{{ old('email') }}" placeholder="Email">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <!---  <img src="{{ asset('web_assets/images/user.png') }}" width="20" height="20" class="useimg" alt=""> --->
                    </div>

                    <div class="mb-4">
                        <div style="position: relative;">
                            <input type="password" name="password" required
                                class="form-control @error('password') is-invalid @enderror"
                                id="affiliate-login-password" aria-describedby="passwordHelp"
                                value="{{ old('password') }}" placeholder="password">
                            <button type="button" class="toggle-password-visibility"
                                style="position:absolute;top:50%;right:12px;transform:translateY(-50%);border:none;background:transparent;padding:0;cursor:pointer;font-size:18px;"
                                aria-label="Show password" data-target="affiliate-login-password">👁</button>
                        </div>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <!---  <img src="{{ asset('web_assets/images/user.png') }}" width="20" height="20" class="useimg" alt=""> --->
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


                    <button type="submit" class="btn btn-primary form-control mt-2">Login</button>

                    <p class="register-button mt-3 text-center">New here? <a style="color:#0d6efd" href="{{ route('Affiliates.create') }}">Register Now</a>
                    </p>
                    <p class="text-center"><a style="color:#0d6efd; text-decoration: none;background:none;border:none;font-weight:400;"
                        href="{{ route('forget_password') }}">Forgot Password?</a></p>

                </form>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </div>
    </main>
    @if(session()->has('g-recaptcha-response'))
<script>
Swal.fire({
  icon: 'error',
  title: 'Oops!',
  text: 'Please complete the reCAPTCHA to proceed.',
});
</script>
@endif
  @if(session()->has('password_changed'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Password Changed',
      text: 'You can now login using new password.'
    })
  </script>
  @endif
  @if(session()->has('deactivated'))
  <script>
    Swal.fire({
      icon: 'warning',
      title: 'Account Deactivated',
      text: 'Your Account is Deactivated for some reason. Please contact your branch manager.'
    })
  </script>
  @endif
  <script>
    document.querySelectorAll('.toggle-password-visibility').forEach(function(toggleButton) {
      toggleButton.addEventListener('click', function() {
        const passwordField = document.getElementById(this.getAttribute('data-target'));
        if (!passwordField) return;

        const isPassword = passwordField.type === 'password';
        passwordField.type = isPassword ? 'text' : 'password';
        this.textContent = isPassword ? '🙈' : '👁';
        this.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
      });
    });
  </script>
@endsection()
