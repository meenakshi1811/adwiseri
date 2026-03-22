@extends('web.layout.main')

@section('main-section')
  <div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="row w-100">
        <div class="col-lg-6 mx-auto">
            <form id="registration_form" class="register-box login-box" method="POST" action="{{ route('check_registration') }}">
              @csrf
                <h3 class="mb-3 text-center">Register</h3>
                <div class="text-center mb-4">
                  <img src="{{ asset('web_assets/images/loginimg.png') }}" width="60" height="60" class="log-img" alt="" style="margin: 0 auto;">
                </div>

                <!-- First Row -->
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <input name="name" minlength="3" maxlength="100" required type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Name" value="{{ old('name') }}">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mb-3">
                        <input name="phone" type="text" pattern="\d*" minlength="9" maxlength="12" class="form-control @error('phone') is-invalid @enderror" placeholder="Phone Number" value="{{ old('phone') }}" required>
                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <!-- Second Row -->
                <div class="row">
                    <div class="col-lg-6 mb-3">
                    <input 
                        name="email"
                        type="email"
                        pattern="^[^\s@]+@[^\s@]+\.[^\s@]+$"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="Email ID"
                        value="{{ old('email') }}"
                        required
                        >    
                    @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mb-3">
                        <select id="category" name="category" class="form-control form-select @error('category') is-invalid @enderror" required>
                            <option value="">Select Category</option>
                            @foreach($subscriber_categories as $subs_category)
                            <option {{ (old('category') == $subs_category->category_name) ? 'selected' : '' }} value="{{ $subs_category->category_name }}">{{ $subs_category->category_name }}</option>
                            @endforeach
                        </select>
                        @error('category')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <!-- Other Fields -->
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <select id="subcategory" name="subcategory" class="form-control form-select @error('subcategory') is-invalid @enderror" required>
                          @if(old('subcategory'))
                          <option value="{{ old('subcategory') }}">{{ old('subcategory') }}</option>
                          @else
                          <option value="">Select Sub-Category</option>
                          @endif
                        </select>
                        @error('subcategory')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mb-3">
                        <select name="membership" class="form-select" id="membership" aria-label="Default select example" >
                            @foreach($membership as $plan)
                              @if($splan !=null)
                              <option {{ ($splan == $plan->plan_name) ? 'selected' : '' }} value="{{  $plan->plan_name }}">{{  $plan->plan_name." - (US $".$plan->price_per_year.", ".$plan->validity." days)" }}</option>
                              @else

                              <option {{ (old('membership') == $plan->plan_name) ? 'selected' : '' }} value="{{  $plan->plan_name }}">{{  $plan->plan_name." - (US $".$plan->price_per_year.", ".$plan->validity." days)" }}</option>
                              @endif
                            @endforeach
                            </select>


                    </div>

                    <div class="col-lg-6 mb-3" id="other_field" style="display: none;">
                        <input id="other" minlength="3" maxlength="100" name="other" type="text" value="{{ old('other') }}" class="form-control @error('other') is-invalid @enderror" placeholder="Enter other category">
                        @error('other')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <!-- Password Fields -->
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mb-3">
                        <input id="confirm_password" name="password_confirmation" type="password" class="form-control" required placeholder="Confirm Password">
                    </div>
                </div>

                <!-- Referral and Google Recaptcha -->
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <input id="referral" maxlength="100" name="referral" type="text" value="{{ $referral ?? old('referral') }}" class="form-control" placeholder="Enter Referral Code">
                        <input type="hidden" name="local_time" class="localtime" />
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
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-6 d-flex justify-content-center">
                    <button type="submit" class="btn btn-sm btn-primary w-100">Submit</button>
                </div>
            </div>
                <p class="text-center mt-3 reg-logbtn">Already have an account? <a href="{{ route('login') }}" ><strong>Login</strong></a></p>
            </form>
        </div>
    </div>
  </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
    $(document).ready(() => {
      $("#category").change(function(){
          var category = $(this).val();
          console.log(category);
          $.ajax({
            url: "{{ route('get_sub_category') }}",
            method: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                category: category,
            },
            success: function(data){
                $("#subcategory").html(data);
            }
          });
        });

        $("#subcategory").change(function(){
          var subcategory = $(this).val();
          if(subcategory == "Other"){
            $("#other_field").show();
            $("#other").attr('required', true);
          } else {
            $("#other_field").hide();
            $("#other").removeAttr('required');
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
     
@endsection
