@extends('web.layout.main')

@section('main-section')
    @if (session()->has('password_changed'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Password Changed',
                text: 'You can now login using new password.'
            })
        </script>
    @endif
    <main class="py-4" style="padding-top:4.5rem!important;">
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 loginouter-box">
                <form id="loginform" class="login-box" method="POST" action="{{ route('Affiliates.store') }}">
                    @csrf
                    <input type="hidden" name="local_time" class="localtime" />
                    <h3 class="mb-5 text-center">Affiliate - Registration</h3>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <input type="text" name="name" required
                                class="form-control @error('name') is-invalid @enderror" placeholder="Name"
                                value="{{ old('name') }}">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <input type="tel" name="phone" required
                                class="form-control @error('phone') is-invalid @enderror" placeholder="Phone"
                                value="{{ old('phone') }}">
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <input type="email" name="email" required
                                class="form-control @error('email') is-invalid @enderror" placeholder="Email"
                                value="{{ old('email') }}">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <input type="password" name="password" required
                                class="form-control @error('password') is-invalid @enderror" placeholder="Password"
                                value="{{ old('password') }}">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <select name="type" class="form-select">
                                <option value="" {{ old('type') == '' ? 'selected' : '' }}>Select Type</option>
                                <option value="Individual" {{ old('type') == 'Individual' ? 'selected' : '' }}>Individual
                                </option>
                                <option value="Agency" {{ old('type') == 'Agency' ? 'selected' : '' }}>Agency</option>
                                <option value="Corporate" {{ old('type') == 'Corporate' ? 'selected' : '' }}>Corporate
                                </option>
                            </select>
                            @error('type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <select name="country" id="country" required class="form-select">
                                <option value="" {{ old('country') == '' ? 'selected' : '' }}>Country</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->country_name }}"
                                        {{ old('country') == $country->country_name ? 'selected' : '' }}>
                                        {{ $country->country_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('country')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <input type="text" name="city" required
                                class="form-control @error('city') is-invalid @enderror" placeholder="City/Town"
                                value="{{ old('city') }}">
                            @error('city')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            {!! NoCaptcha::renderJs() !!}
                            {!! NoCaptcha::display() !!}
                            {{-- @error('g-recaptcha-response') --}}
                                {{-- <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror --}}
                            </div>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input @error('terms') is-invalid @enderror" name="terms" type="checkbox"
                                data-error="Please check this box to proceed." id="flexCheckDefault">
                            {{-- <label class="form-check-label" for="flexCheckDefault">
                        I agree to the <a href="{{route('terms_use')}}" target="_blank">Terms and Conditions</a>
                        and <a href="{{ route('privacy_policy')}}" target="_blank">Privacy Policy</a>
                    </label> --}}
                            <p class=" register-box t small text-dark">
                                Yes, I understand and agree to the
                                <a href="{{ route('terms_use') }}" target="_blank">Adwiseri Terms of Service</a>,
                                including the <a href="{{ route('terms_use') }}" target="_blank">User Agreement</a> and
                                <a href="{{ route('privacy_policy') }}" target="_blank">Privacy Policy</a>.
                            </p>

                            @error('terms')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-lg-6 d-flex justify-content-center mb-4">
                                <button type="submit" class="btn btn-sm btn-primary w-100">Submit</button>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col-lg-6 offset-lg-6 d-flex justify-content-end">
                                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                            </div>
                        </div> --}}
                        {{-- <button type="submit" class="btn btn-primary form-control mt-2">Submit</button> --}}
                    </form>
                </div>
            </div>
        </div>
        </main>
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
        @if (session()->has('g-recaptcha-response'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Please complete the reCAPTCHA to proceed.',
                });
            </script>
        @endif

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const checkbox = document.querySelector('#flexCheckDefault');
                const form = document.querySelector('#loginform');

                form.addEventListener('submit', function(event) {
                    console.log('Checkbox checked:', checkbox.checked); // Always log the checkbox state
                    const recaptchaResponse = grecaptcha.getResponse(); // Get the reCAPTCHA response
                    if (!checkbox.checked) {
                        event.preventDefault(); // Prevent form submission
                        checkbox.setCustomValidity(checkbox.getAttribute('data-error')); // Set error message
                        checkbox.reportValidity(); // Display the error message
                    } else if (!recaptchaResponse) {
                        event.preventDefault(); // Prevent form submission
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops!',
                            text: 'Please complete the reCAPTCHA to proceed.',
                        });
                        return false; // Stop form submission
                    } else {
                        checkbox.setCustomValidity(''); // Clear the error message
                        checkbox.reportValidity(); // Ensure no residual error message
                    }
                });
            });

            // Reset custom validity whenever the checkbox is toggled
            checkbox.addEventListener('change', function() {
            if (checkbox.checked) {
                checkbox.setCustomValidity(''); // Clear error
            }
            });
        </script>
    @endsection
