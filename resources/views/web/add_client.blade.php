@extends('web.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
            <h3 class="text-primary px-2">Add Client</h3>
            <div class="col">
                <form id="registration_form" class="register-box login-box" method="POST" action="{{ route('add_new_client') }}">
                    @csrf

                    <div class="row">
                        <div class="col-12 p-1">
                          Client Detail
                        </div>
                    </div>
                    <div class="row">
                        <input type="hidden" name="local_time" class="localtime" />
                        <!-- First Column -->

                        <!-- Second Column -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="name" class="form-label"> Name<span
                                        class="text-danger">*</span></label>
                                <input name="name" minlength="3" maxlength="100" required type="text"
                                    class="form-control @error('name') is-invalid @enderror" id="name"
                                    value="{{ old('name') }}" placeholder="Client Name">
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone<span
                                        class="text-danger">*</span></label>
                                <input name="phone" type="text" pattern="\d*" minlength="9" maxlength="12"
                                    class="form-control @error('phone') is-invalid @enderror" id="exampleInputEmail1"
                                    aria-describedby="emailHelp" value="{{ old('phone') }}" required
                                    placeholder="Phone Number" autocomplete="phone">
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                            <label>Email<span class="text-danger" style="font-size: 18px;">*</span></label>
                            <input name="email" type="email" minlength="3" maxlength="100" class="form-control @error('email') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('email') }}" required placeholder="Email ID" autocomplete="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <!-- First Column -->

                        <!-- Second Column -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                    <label>Alternate No.</label>
                                    <input name="alternate_no" type="text" pattern="\d*" minlength="9" maxlength="12" class="form-control @error('alternate_no') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('alternate_no') }}" placeholder="Alternate Number" autocomplete="alternate_no">
                                    @error('alternate_no')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                    <label>Nationality<span class="text-danger" style="font-size: 18px;">*</span></label>
                                    {{-- <input name="nationality" type="text" class="form-control @error('nationality') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('nationality') }}" required placeholder="Nationality" autocomplete="nationality"> --}}
                                    <select name="nationality" id="nationality" class="form-control form-select @error('nationality') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                        <option value="">Select Nationality</option>
                                        @foreach($countries as $country)
                                        <option {{(old('nationality') == $country->id) ? 'selected':''}} value="{{ $country->id }}">{{ $country->country_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('nationality')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                    <label>Passport No.
                                        {{-- <span class="text-danger" style="font-size: 18px;">*</span> --}}
                                    </label>
                                    <input name="passport_no" minlength="6" maxlength="14" pattern="^[A-Z0-9]+$" onkeyup="this.value = this.value.toUpperCase();" type="text" class="form-control @error('passport_no') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('passport_no') }}" placeholder="Passport No" autocomplete="passport_no">
                                    @error('passport_no')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                        </div>

                    </div>
                    <div class="row">
                        <!-- First Column -->

                        <!-- Second Column -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                    <label>Date Of Birth
                                        {{-- <span class="text-danger" style="font-size: 18px;">*</span> --}}
                                    </label>
                                    <input name="dob" type="date" max="{{ date('Y-m-d') }}" class="form-control date @error('dob') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('dob') }}"  placeholder="Date Of Birth" autocomplete="dob">
                                    @error('dob')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                    <label>Country<span class="text-danger" style="font-size: 18px;">*</span></label>
                                    <select name="country" id="country" class="form-control form-select @error('country') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                        <option value="">Select Country</option>
                                        @foreach($countries as $country)
                                        <option {{(old('country') == $country->id) ? 'selected':''}} value="{{ $country->id }}">{{ $country->country_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('country')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                    <label>Address<span class="text-danger" style="font-size: 18px;">*</span></label>
                                    <input name="address" minlength="3" maxlength="150" type="text" class="form-control @error('address') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('address') }}" required placeholder="Address" autocomplete="address">
                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                        </div>

                    </div>

                        {{-- <div class="col-md-4 p-1">
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
                            <input name="email" type="email" minlength="3" maxlength="100" class="form-control @error('email') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('email') }}" required placeholder="Email ID" autocomplete="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div> --}}



                        <div class="row">
                            <!-- First Column -->

                            <!-- Second Column -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                        <label>City/Town<span class="text-danger" style="font-size: 18px;">*</span></label>
                                        <input name="city" type="text" minlength="3" maxlength="100" class="form-control @error('city') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('city') }}" required placeholder="City" autocomplete="city">
                                        @error('city')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                        <label>State/County<span class="text-danger" style="font-size: 18px;">*</span></label>
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
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                        <label>Postcode<span class="text-danger" style="font-size: 18px;">*</span></label>
                                        <input name="pincode" minlength="3" maxlength="10" style="text-transform:uppercase" type="text" class="form-control @error('pincode') is-invalid @enderror" value="{{ old('pincode') }}" required placeholder="Postcode" autocomplete="off">
                                        @error('pincode')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                            </div>

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
    $(document).ready(function(){
        $("#country").change(function(){
        var country = $(this).val();
        // console.log(counrty);
        $.ajax({
            url: 'get_states',
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
    });
    });
</script>

@if(session()->has('success'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Congratulations',
      text: 'Client Added Successfully.'
    })
  </script>

@endif

@endsection()
