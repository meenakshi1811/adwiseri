@extends('web.layout.main')

@section('main-section')
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4 loginouter-box">
                <form id="loginform" class="login-box" method="POST" action="{{ route('login') }}">
                    @csrf
                    <input type="hidden" name="local_time" class="localtime" />
                    <h3 class="mb-5 text-center">Login</h3>
                    <div class="log-img mb-5">
                        <img src="{{ asset('web_assets/images/loginimg.png') }}" width="60" height="60"
                            class="log-img" alt="">
                    </div>
                    @if ($errors->has('login_error'))
                        <div class="alert alert-danger text-center">
                            {{ $errors->first('login_error') }}
                        </div>
                    @endif
                    <div class="mb-4">
                        <input type="email" name="email" required
                            class="form-control @error('email') is-invalid @enderror" id="exampleInputEmail1"
                            aria-describedby="emailHelp" value="{{ old('email') }}" placeholder="email">
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
                                class="form-control @error('password') is-invalid @enderror toggle-password-input"
                                id="login-password" aria-describedby="emailHelp" placeholder="Password">
                            <button type="button" class="toggle-password-visibility"
                                style="position:absolute;top:50%;right:12px;transform:translateY(-50%);border:none;background:transparent;padding:0;cursor:pointer;font-size:18px;"
                                aria-label="Show password" data-target="login-password">👁</button>
                        </div>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <!---  <img {{ asset('web_assets/images/pass.png') }}" width="16" height="18" class="useimg" alt=""> --->
                    </div>
                    
                    {{-- Render Google reCAPTCHA --}}
                    <div class="form-group mb-4" style="margin-bottom: 50px;">
                        {!! NoCaptcha::renderJs() !!}
                        {!! NoCaptcha::display() !!}
                        @error('g-recaptcha-response')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary form-control">Login</button>

                    <p class="register-button mt-3 text-center">New here? <a href="{{ route('user_register') }}">Register
                            Now</a>
                    </p>
                    <p class="text-center"><a style="text-decoration: none;background:none;border:none;"
                            href="{{ route('forget_password') }}">Forgot Password?</a></p>
                </form>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </div>

    @if (session()->has('password_changed'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Password Changed',
                text: 'You can now login using new password.'
            })
        </script>
    @endif
    @if (session()->has('deactivated'))
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
                const targetId = this.getAttribute('data-target');
                const passwordField = document.getElementById(targetId);
                if (!passwordField) return;

                const isPassword = passwordField.type === 'password';
                passwordField.type = isPassword ? 'text' : 'password';
                this.textContent = isPassword ? '🙈' : '👁';
                this.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
            });
        });
    </script>
@endsection()
