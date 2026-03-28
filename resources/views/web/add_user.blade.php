@extends('web.layout.main')

@section('main-section')
  
        <div class="col-lg-10 column-client">
            <h3 class="text-primary px-2">Add User</h3>
            <div class="col">
                <form id="registration_form" class="register-box login-box" method="POST" action="{{ route('add_new_user') }}" autocomplete="off">
                    @csrf
                    <input type="hidden" name="local_time" class="localtime" />
                    <div class="row">
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
                            <select name="designation" class="form-control form-select @error('designation') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required autocomplete="designation">
                                <option value="">Select Designation/Role</option>
                                <option {{(old('designation') == "Branch Manager") ? 'selected':''}} value="Branch Manager">Branch Manager</option>
                                <option {{(old('designation') == "Consultant/Advisor") ? 'selected':''}} value="Consultant/Advisor">Consultant/Advisor</option>
                                <option {{(old('designation') == "Administrator") ? 'selected':''}} value="Administrator">Administrator</option>
                                <option {{(old('designation') == "HR Executive") ? 'selected':''}} value="HR Executive">HR Executive</option>
                                <option {{(old('designation') == "Sales Team Member") ? 'selected':''}} value="Sales Team Member">Sales Team Member</option>
                                <option {{(old('designation') == "Accounts Team Member") ? 'selected':''}} value="Accounts Team Member">Accounts Team Member</option>
                                <option {{(old('designation') == "Support Team Member") ? 'selected':''}} value="Support Team Member">Support Team Member</option>
                                <option {{(old('designation') == "Other") ? 'selected':''}} value="Other">Other</option>
                            </select>
                            @error('designation')
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
                                <option {{($country->id == old('country')) ? 'selected':''}} value="{{ $country->id }}">{{ $country->country_name }}</option>
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
                            <input name="city" type="city" minlength="3" maxlength="100" class="form-control @error('city') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('city') }}" required placeholder="City" autocomplete="city">
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
                            <input name="pincode" minlength="3" maxlength="10" style="text-transform:uppercase" type="text" class="form-control @error('pincode') is-invalid @enderror" id="pincode"  value="{{ old('pincode') }}" required placeholder="Postcode" autocomplete="postcode">
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
                                <option {{($zone == old('timezone')) ? 'selected':''}} value="{{ $zone }}">{{ $zone }}</option>
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
                            <input id="password" name="password" value="{{old('password')}}" type="text" onfocus="this.type ='password'" class="form-control @error('password') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required placeholder="Password" autocomplete="password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col text-start p-1">
                            <button type="submit" class="form-control btn btn-primary" style="width: fit-content;">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            
        </div>
        
    </div>

  </div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
</script>
<script>
    $(document).ready(() => {
          
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
                    // console.log(data);
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
@if(session()->has('success'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Congratulations',
      text: 'User Added Successfully.'
    })
  </script>

@endif

@endsection()
