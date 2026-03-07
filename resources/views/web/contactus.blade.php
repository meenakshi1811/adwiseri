@extends('web.layout.main')

@section('main-section')

<style>
    .banner {
    width: 100%;
    height: 450px;
    overflow: hidden;
    }

    .banner img {
    width: 100%;
    height: 100%;
    object-fit: cover;     /* Fill area without distortion */
    object-position: center; /* Center the image */
    }

</style>

  <div class="contactbanner banner">
    <img src="{{ asset('admin_assets/contactus/'.$contact->banner) }}">
  </div>

  <div class="container contact-form mb-5">
    <h1 class="mb-5 mt-5">Contact Us</h1>
    <div class="row contact-row">
        <div class="col-lg-1"></div>
        <div class="col-lg-10 form-box">
            <form method="post" action="{{ route('post_contact') }}" id="contact_us">
                @csrf
                <input type="hidden" name="local_time" class="localtime" />

                <div class="row mb-4">
                    <div class="col-md-6">
                        <input type="text" minlength="3" maxlength="100" name="name"
                               @if($user) value="{{$user->name}}" @endif
                               class="form-control"
                               placeholder="Enter Your Name" required>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="phone" pattern="\d*" minlength="9" maxlength="12"
                               @if($user) value="{{$user->phone}}" @endif
                               class="form-control"
                               placeholder="Enter Your Phone No." required>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <select style="font-size: 14px;" name="country"
                                class="form-control form-select" required>
                            <option value="">Select Your Country</option>
                            @foreach($countries as $country)
                            <option @if($user)
                                    {{($user->country == $country->country_name) ? 'selected':''}}
                                    @endif
                                    value="{{ $country->country_name }}">
                                {{ $country->country_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="text" minlength="3" maxlength="100" name="city"
                               @if($user) value="{{$user->city}}" @endif
                               class="form-control"
                               placeholder="Enter Your City" required>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <input type="email" minlength="3" maxlength="100" name="email"
                               @if($user) value="{{$user->email}}" @endif
                               class="form-control"
                               placeholder="Enter a Valid Email Address" required>
                    </div>
                    <div class="col-md-6">
                        <textarea class="form-control" name="message"
                                  rows="4" placeholder="Enter Your Message" required></textarea>
                    </div>
                </div>

                <div class="form-group mb-4">
                    {!! NoCaptcha::renderJs() !!}
                    {!! NoCaptcha::display() !!}
                    {{-- @error('g-recaptcha-response')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror --}}
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input @error('terms') is-invalid @enderror"
                           name="terms" type="checkbox"
                         data-error="Please check this box to proceed."
                           id="flexCheckDefault" >
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
                    <div class="col-lg-6 d-flex justify-content-center">
                        <button type="submit" class="btn btn-sm btn-primary w-100">Submit</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-1"></div>
    </div>
</div>

@if(session()->has('g-recaptcha-response'))
<script>
Swal.fire({
  icon: 'error',
  title: 'Oops!',
  text: 'Please complete the reCAPTCHA to proceed.',
});
</script>
@endif
<script>

  @if(session()->has('message_sent'))
  
    Swal.fire({
        icon: 'success',
        title: 'Thanks for getting in touch.',
        text: 'We will serve with your query at the earliest.'
    })

document.querySelector('#contact_us').addEventListener('submit', function (event) {
        const checkbox = document.querySelector('#flexCheckDefault');
        if (!checkbox.checked) {
            event.preventDefault(); // Prevent form submission
            checkbox.setCustomValidity(checkbox.getAttribute('data-error')); // Set custom error
            checkbox.reportValidity(); // Display the error
        } else {
            checkbox.setCustomValidity(''); // Clear custom error if valid
        }
    });
  </script>
  @endif
  <script>

    document.addEventListener('DOMContentLoaded', function () {
    const checkbox = document.querySelector('#flexCheckDefault');
    const form = document.querySelector('#contact_us');

    form.addEventListener('submit', function (event) {
        console.log('Checkbox checked:', checkbox.checked); // Always log the checkbox state
        const recaptchaResponse = grecaptcha.getResponse(); // Get the reCAPTCHA response

// Check if reCAPTCHA is completed

        if (!checkbox.checked) {
            event.preventDefault(); // Prevent form submission
            checkbox.setCustomValidity(checkbox.getAttribute('data-error')); // Set error message
            checkbox.reportValidity(); // Display the error message
        }else if (!recaptchaResponse) {
            event.preventDefault(); // Prevent form submission
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: 'Please complete the reCAPTCHA to proceed.',
            });
            return false; // Stop form submission
        }else {
            checkbox.setCustomValidity(''); // Clear the error message
            checkbox.reportValidity(); // Ensure no residual error message
        }
    });

    // Reset custom validity whenever the checkbox is toggled
    checkbox.addEventListener('change', function () {
        if (checkbox.checked) {
            checkbox.setCustomValidity(''); // Clear error
        }
    });
});
  </script>

@endsection()
