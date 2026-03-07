@extends('web.layout.main')

@section('main-section')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center mt-5">
        <div class="col-lg-8 col-md-10 col-sm-12">
            <form id="registration_form" class="register-box login-box mt-5" method="POST" action="{{ route('demo_post') }}">
                @csrf
                <h3 class="mb-2 mt-4 text-center">Request A Demo</h3>
                <div class="log-img mb-5 text-center">
                    {{-- <img src="{{ asset('web_assets/images/loginimg.png') }}" width="60" height="60" alt=""> --}}
                </div>

                <div class="row ">
                    <div class="col-md-6  mb-4">
                        <input name="name" minlength="3" maxlength="100" required type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Name" autocomplete="name">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-4">
                        <input name="phone" type="text" pattern="\d*" minlength="9" maxlength="12" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required placeholder="Phone Number" autocomplete="phone">
                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <input name="email" minlength="3" maxlength="100" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="Email ID" autocomplete="email">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-4">
                        <select name="country" class="form-control form-select @error('country') is-invalid @enderror" required>
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option {{ (old('country') == $country->country_name) ? 'selected' : '' }} value="{{ $country->country_name }}">{{ $country->country_name }}</option>
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
                        <input name="company_name" minlength="3" maxlength="1000" required type="text" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name') }}" placeholder="Company/Business Name" autocomplete="Company/Business Name">
                        @error('company_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-4">
                        <input name="job_title" minlength="3" maxlength="1000" required type="text" class="form-control @error('job_title') is-invalid @enderror" value="{{ old('job_title') }}" placeholder="Job Title" autocomplete="Job Title">
                        @error('job_title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <select name="how_did_hear" id="how_did_hear" class="form-control form-select @error('how_did_hear') is-invalid @enderror" required>
                        <option value="">How did you hear about us?</option>
                        <option {{ (old('how_did_hear') == "LinkedIn") ? 'selected' : ''}} value="LinkedIn">LinkedIn</option>
                        <option {{ (old('how_did_hear') == "Twitter") ? 'selected' : ''}} value="Twitter">Twitter</option>
                        <option {{ (old('how_did_hear') == "YouTube") ? 'selected' : ''}} value="YouTube">YouTube</option>
                        <option {{ (old('how_did_hear') == "Industry friend") ? 'selected' : ''}} value="Industry friend">Industry friend</option>
                        <option {{ (old('how_did_hear') == "Google") ? 'selected' : ''}} value="Google">Google</option>
                    </select>
                    @error('how_did_hear')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>



                <div class="col-lg-12 mb-4">
                    {!! NoCaptcha::renderJs() !!}
                    {!! NoCaptcha::display() !!}
                    {{-- @error('g-recaptcha-response')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror --}}
                </div>
                <div class="form-check mb-5">
                    <input class="form-check-input @error('terms') is-invalid @enderror"      data-error="Please check this box to proceed." name="terms" type="checkbox"  id="flexCheckDefault"   >
                    <p class="t small">
                        Yes, I understand and agree to the
                        <a href="{{ route('terms_use') }}" target="_blank">Adwiseri Terms of Service</a>,
                        including the <a href="{{ route('terms_use') }}" target="_blank">User Agreement</a> and
                        <a href="{{ route('privacy_policy') }}" target="_blank">Privacy Policy</a>.
                    </p>
                    {{-- <label class="form-check-label" for="flexCheckDefault">
                        I agree to the <a href="{{route('terms_use')}}" target="_blank">Terms and Conditions</a> and <a href="{{ route('privacy_policy')}}" target="_blank">Privacy Policy</a></label>
                    </label> --}}
                    @error('terms')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                </div>

                <input type="hidden" name="local_time" class="localtime" />
                <div class="row justify-content-center">
                    <div class="col-lg-6 d-flex justify-content-center">
                        <button type="submit" class="btn btn-sm btn-primary w-100 mb-5">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
</script>
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
    $(document).ready(() => {
      $("#category").change(function(){
          var category = $(this).val();
          $.ajax({
            url: "{{ route('get_sub_category') }}",
            method: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                category: category,
            },
            cache:false,
            success: function(data){
              console.log(data);
                $("#subcategory").html(data);
            }
          });
        });
        $("#subcategory").change(function(){
          var subcategory = $(this).val();
          if(subcategory == "Other"){
            $("#other_field").css('display','block');
            $("#other").attr('required','true');
          }
          else{
            $("#other_field").css('display','none');
            $("#other").removeAttr("required");
          }
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
    const checkbox = document.querySelector('#flexCheckDefault');
    const form = document.querySelector('#registration_form');

    form.addEventListener('submit', function (event) {
        console.log('Checkbox checked:', checkbox.checked); // Always log the checkbox state

        const recaptchaResponse = grecaptcha.getResponse(); // Get the reCAPTCHA response
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
@if(session()->has('submitted'))
<script>
  Swal.fire({
    icon: 'success',
    title: 'We received your demo request.',
    text: 'A member of our team will be in touch with you soon.'
  })
</script>

@endif

@endsection()
