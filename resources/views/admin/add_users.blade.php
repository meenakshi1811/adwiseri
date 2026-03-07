@extends('admin.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
            @if(isset($siteuser))
            <div class="client-dashboard">
                <div class="client-btn d-flex mb-2 ">
                    <form class="form-inline d-flex justify-content-between w-100">
                        <h3 class="text-primary">Update User</h3>
                    </form>
                </div>
                <div class="col">
                    <form id="registration_form" class="register-box login-box" method="POST" action="{{ route('register_new_user') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ $siteuser->id }}" />
                        <div class="row">
                            <div class="col-md-4 p-1">
                                <label>Name<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="name" minlength="3" maxlength="100" required type="text" class="form-control @error('name') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ $siteuser->name }}" placeholder="Name" autocomplete="name">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Phone<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="phone" type="text" pattern="\d*" minlength="9" maxlength="12" class="form-control @error('phone') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ $siteuser->phone }}" required placeholder="Phone Number" autocomplete="phone">
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Date of Birth<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="dob" type="date" max="{{ date('Y-m-d') }}" class="form-control date @error('dob') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ $siteuser->dob }}" required placeholder="Date of Birth" autocomplete="dob">
                                @error('dob')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Designation/Role<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                {{-- <input name="designation" type="text" class="form-control @error('designation') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ $siteuser->designation }}" required placeholder="Designation" autocomplete="designation"> --}}
                                <select name="designation" class="form-control form-select @error('designation') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required autocomplete="designation">
                                    <option value="">Select Designation/Role</option>
                                    <option {{ ($siteuser->designation == "Branch Manager") ? 'selected' : '' }} value="Branch Manager">Branch Manager</option>
                                    <option {{ ($siteuser->designation == "Consultant/Advisor") ? 'selected' : '' }} value="Consultant/Advisor">Consultant/Advisor</option>
                                    <option {{ ($siteuser->designation == "Administrator") ? 'selected' : '' }} value="Administrator">Administrator</option>
                                    <option {{ ($siteuser->designation == "HR Executive") ? 'selected' : '' }} value="HR Executive">HR Executive</option>
                                    <option {{ ($siteuser->designation == "Sales Team Member") ? 'selected' : '' }} value="Sales Team Member">Sales Team Member</option>
                                    <option {{ ($siteuser->designation == "Accounts Team Member") ? 'selected' : '' }} value="Accounts Team Member">Accounts Team Member</option>
                                    <option {{ ($siteuser->designation == "Support Team Member") ? 'selected' : '' }} value="Support Team Member">Support Team Member</option>
                                    <option {{ ($siteuser->designation == "Other") ? 'selected' : '' }} value="Other">Other</option>
                                </select>
                                @error('designation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Address<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="address_line" minlength="3" maxlength="150" type="text" class="form-control @error('address_line') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ $siteuser->address_line }}" required placeholder="Address" autocomplete="address_line">
                                @error('address_line')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Country<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <select name="country" id="country" class="form-control form-select @error('country') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                    <option value="">Select Country</option>
                                    @foreach($countries as $country)
                                    <option {{ ($siteuser->country == $country->country_name) ? 'selected' : '' }} value="{{ $country->id }}">{{ $country->country_name }}</option>
                                    @endforeach
                                </select>
                                @error('country')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>State/County<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <select name="state" id="state" class="form-control form-select @error('state') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                    <option value="">Select State/County</option>
                                    @foreach ($states as $state)
                                        <option {{ $siteuser->state == $state->name ? 'selected' : '' }}
                                            value="{{ $state->name }}">{{ $state->name }}</option>
                                    @endforeach
                                </select>
                                @error('state')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>City/Town<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="city" type="text" minlength="3" maxlength="100" class="form-control @error('city') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ $siteuser->city }}" required placeholder="City/Town" autocomplete="city">
                                @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Postcode<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="pincode" minlength="3" maxlength="10" style="text-transform:uppercase" type="text" class="form-control @error('pincode') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ $siteuser->pincode }}" required placeholder="Postcode" autocomplete="pincode">
                                @error('pincode')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Timezone<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <select name="timezone" id="timezone" class="form-control form-select @error('timezone') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                    <option value="">Select Timezone</option>
                                    @foreach($tzlist as $zone)
                                    <option {{ ($siteuser->timezone == $zone) ? 'selected' : '' }} value="{{ $zone }}">{{ $zone }}</option>
                                    @endforeach
                                </select>
                                @error('timezone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                            </div>
                            <div class="col-md-8 text-left p-1">
                                <button type="submit" class="form-control btn btn-primary" style="width: fit-content;">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            @else
            <div class="client-dashboard">
                <div class="client-btn d-flex mb-2 ">
                    <form class="form-inline d-flex justify-content-between w-100">
                        <h3 class="text-primary">Add New User</h3>
                    </form>
                </div>
                <div class="col">
                    <form id="registration_form" class="register-box login-box" method="POST" action="{{ route('register_new_user') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 p-1">
                                <label>Subscriber<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <select name="subscriber" required class="form-control form-select @error('subscriber') is-invalid @enderror" id="sel_subscriber" aria-describedby="emailHelp">
                                    <option value="">Select Subscriber</option>
                                    @foreach($subscribers as $sub)
                                    <option {{ (old('subscriber') == $sub->id) ? 'selected':'' }} value="{{ $sub->id }}">{{ $sub->name."(".$sub->id.")" }}</option>
                                    @endforeach
                                </select>
                                @error('subscriber')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Name<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="name" minlength="3" maxlength="100" required type="text" class="form-control @error('name') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('name') }}" placeholder="Name" autocomplete="name">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Phone<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="phone" type="text" pattern="\d*" minlength="9" maxlength="12" class="form-control @error('phone') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('phone') }}" required placeholder="Phone Number" autocomplete="phone">
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Email<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="email" minlength="3" maxlength="100" type="email" class="form-control @error('email') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('email') }}" required placeholder="Email ID" autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Date of Birth<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="dob" type="date" max="{{ date('Y-m-d') }}" class="form-control date @error('dob') is-invalid @enderror" id="exampleInputdob1" aria-describedby="dobHelp" value="{{ old('dob') }}" required placeholder="Date of Birth" autocomplete="dob">
                                @error('dob')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Designation/Role<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                {{-- <input name="designation" type="text" class="form-control @error('designation') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('designation') }}" required placeholder="Designation" autocomplete="designation"> --}}
                                <select name="designation" class="form-control form-select @error('designation') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required autocomplete="designation">
                                    <option value="">Select Designation/Role</option>
                                    <option {{ (old('designation') == "Branch Manager") ? 'selected':'' }} value="Branch Manager">Branch Manager</option>
                                    <option {{ (old('designation') == "Consultant/Advisor") ? 'selected':'' }} value="Consultant/Advisor">Consultant/Advisor</option>
                                    <option {{ (old('designation') == "Administrator") ? 'selected':'' }} value="Administrator">Administrator</option>
                                    <option {{ (old('designation') == "HR Executive") ? 'selected':'' }} value="HR Executive">HR Executive</option>
                                    <option {{ (old('designation') == "Sales Team Member") ? 'selected':'' }} value="Sales Team Member">Sales Team Member</option>
                                    <option {{ (old('designation') == "Accounts Team Member") ? 'selected':'' }} value="Accounts Team Member">Accounts Team Member</option>
                                    <option {{ (old('designation') == "Support Team Member") ? 'selected':'' }} value="Support Team Member">Support Team Member</option>
                                    <option {{ (old('designation') == "Other") ? 'selected':'' }} value="Other">Other</option>
                                </select>
                                @error('designation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Address<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="address_line" minlength="3" maxlength="150" type="text" class="form-control @error('address_line') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('address_line') }}" required placeholder="Address" autocomplete="address_line">
                                @error('address_line')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Country<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <select name="country" id="country" class="form-control form-select @error('country') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                    <option value="">Select Country</option>
                                    @foreach($countries as $country)
                                    <option {{ (old('country') == $country->id) ? 'selected':'' }} value="{{ $country->id }}">{{ $country->country_name }}</option>
                                    @endforeach
                                </select>
                                @error('country')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>State/County<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <select name="state" id="state" class="form-control form-select @error('state') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                    <option value="">Select State/County</option>
                                    @if(old('state'))
                                    <option value="{{old('state')}}" selected>{{old('state')}}</option>
                                    @endif
                                </select>
                                @error('state')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>City/Town<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="city" type="text" minlength="3" maxlength="100" class="form-control @error('city') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('city') }}" required placeholder="City/Town" autocomplete="city">
                                @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Postcode<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="pincode" minlength="3" maxlength="10" style="text-transform:uppercase" type="text" class="form-control @error('pincode') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('pincode') }}" required placeholder="Postcode" autocomplete="pincode">
                                @error('pincode')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Timezone<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <select name="timezone" id="timezone" class="form-control form-select @error('timezone') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                    <option value="">Select Timezone</option>
                                    @foreach($tzlist as $zone)
                                    <option {{ (old('timezone') == $zone) ? 'selected':'' }} value="{{ $zone }}">{{ $zone }}</option>
                                    @endforeach
                                </select>
                                @error('timezone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Password<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required placeholder="Password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                            </div>
                            <div class="col-md-8 text-left p-1">
                                <button type="submit" class="form-control btn btn-primary" style="width: fit-content;">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            @endif
        </div>
    </div>

  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
  </script>
  <script>
      $(document).ready(() => {
        $('#registration_form').on('submit', function (e) {
    // Prevent form submission until validation is complete
    e.preventDefault();

    // Get the date of birth value
    const dob = $('input[name="dob"]').val();
    if (!dob) {
        alert('Please select your Date of Birth.');
        return; // Exit if DOB is not provided
    }

    // Calculate age
    const dobDate = new Date(dob);
    const today = new Date();
    let age = today.getFullYear() - dobDate.getFullYear();
    const monthDiff = today.getMonth() - dobDate.getMonth();

    // Adjust age if the current month is before the birth month or if it's the same month and the day hasn't passed yet
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dobDate.getDate())) {
        age--;
    }

    // Check if the user is at least 18 years old
    if (age < 18) {
        Swal.fire({
            icon: 'warning', // Warning icon
            title: 'Oops!',
            text: 'User (staff member) seems to be younger than 18. Do you want to proceed?',
            showCancelButton: true,
            confirmButtonText: 'Yes, proceed',
            cancelButtonText: 'No, cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // User clicked "Yes, proceed"
                console.log('Proceeding...');
                $('#registration_form')[0].submit(); // Submit the form programmatically
            } else {
                // User clicked "No, cancel"
                console.log('Cancelled.');
                return; // Do nothing, form stays on the page
            }
        });
    } else {
        // If the user is 18 or older, allow the form submission
        $('#registration_form')[0].submit();
    }
});
        $("#sel_subscriber").change(function(){
          var subscriber = $(this).val();
          $.ajax({
            url: 'check_user_limit',
            method: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                subscriber: subscriber,
            },
            cache:false,
            success: function(data){
            //   console.log(data);
                if(data.limit == 'full'){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops..',
                        text: 'User limit reached for this Subscriber!'
                      });
                      setTimeout(function(){
                          window.location.reload();
                      }, 2000);
                }
            }
          });
        });
        $("#category").change(function(){
          var category = $(this).val();
          $.ajax({
            url: 'get_sub_category',
            method: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                category: category,
            },
            cache:false,
            success: function(data){
            //   console.log(data);
                $("#subcategory").html(data);
            }
          });
        });
        $("#subcategory").change(function(){
          var subcategory = $(this).val();
          if(subcategory == "Other"){
            $(".other_field").css('display','block');
            $("#other").attr('required','true');
          }
          else{
            $(".other_field").css('display','none');
            $("#other").removeAttr("required");
          }
        });
        $("#country").change(function(){
            var country = $(this).val();
            // console.log(counrty);
            $.ajax({
                url: "{{route('get_states')}}",
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    country: country,
                },
                cache:false,
                success: function(data){
                  console.log(data);
                    $("#state").html(data);
                }
            });
            $.ajax({
                url: "{{route('get_timezone')}}",
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    country: country,
                },
                cache:false,
                success: function(data){
                    // console.log("zones = "+data);
                    $("#timezone").html(data);
                }
            });
        });
      });
  </script>
  <script>
      function deleteuser(id){
          var conf = confirm('Delete User');
          if(conf == true){
              window.location.href = "delete_user/"+id+"";
          }
      }
  </script>

  @if(session()->has('deleted'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'User Deleted Successfully!'
      })
    </script>

  @endif

@endsection()
