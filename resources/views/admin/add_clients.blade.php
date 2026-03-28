@extends('admin.layout.main')

@section('main-section')

    <div class="col-lg-10 column-client">
        @if (isset($client))
            <div class="client-dashboard">
                <div class="client-btn d-flex mb-2 ">
                    <form class="form-inline d-flex justify-content-between w-100">
                        <h3 class="text-primary">Update Client</h3>
                    </form>
                </div>
                <div class="col">
                    <form id="registration_form" class="register-box login-box" method="POST"
                        action="{{ route('register_new_client') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ $client->id }}" />
                        <div class="row">
                            <div class="col-md-4 p-1">
                                <label>Client Name<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="name" required type="text" minlength="3" maxlength="100"
                                    class="form-control @error('name') is-invalid @enderror" id="exampleInputEmail1"
                                    aria-describedby="emailHelp" value="{{ $client->name }}" placeholder="Client Name"
                                    autocomplete="name">
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
                                <input name="phone" type="text" pattern="\d*" minlength="9" maxlength="12"
                                    class="form-control @error('phone') is-invalid @enderror" id="exampleInputEmail1"
                                    aria-describedby="emailHelp" value="{{ $client->phone }}" required
                                    placeholder="Phone Number" autocomplete="phone">
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Alternate No.</label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="alternate_no" type="text" pattern="\d*" minlength="9" maxlength="12"
                                    class="form-control @error('alternate_no') is-invalid @enderror" id="exampleInputEmail1"
                                    aria-describedby="emailHelp" value="{{ $client->alternate_no }}"
                                    placeholder="Alternate Number" autocomplete="alternate_no">
                                @error('alternate_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Nationality<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                {{-- <input name="nationality" type="text" class="form-control @error('nationality') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('nationality') }}" required placeholder="Nationality" autocomplete="nationality"> --}}
                                <select name="nationality" id="nationality"
                                    class="form-control form-select @error('nationality') is-invalid @enderror"
                                    id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                    <option value="">Select Nationality</option>
                                    @foreach ($countries as $country)
                                        <option {{ $client->nationality == $country->country_name ? 'selected' : '' }}
                                            value="{{ $country->id }}">{{ $country->country_name }}</option>
                                    @endforeach
                                </select>
                                @error('nationality')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Passport No.
                                    {{-- <span class="text-danger" style="font-size: 18px;">*</span> --}}
                                </label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="passport_no" minlength="6" maxlength="14"
                                    onkeyup="this.value = this.value.toUpperCase();" pattern="^[A-Z0-9]+$" type="text"
                                    class="form-control @error('passport_no') is-invalid @enderror" id="exampleInputEmail1"
                                    aria-describedby="emailHelp" value="{{ $client->passport_no }}"
                                    placeholder="Passport No" autocomplete="passport_no">
                                @error('passport_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Date Of Birth
                                    {{-- <span class="text-danger" style="font-size: 18px;">*</span> --}}
                                </label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="dob" type="date" max="{{ date('Y-m-d') }}"
                                    class="form-control date @error('dob') is-invalid @enderror" id="exampleInputEmail1"
                                    aria-describedby="emailHelp" value="{{ date('Y-m-d', strtotime($client->dob)) }}"
                                    placeholder="Date Of Birth" autocomplete="dob">
                                @error('dob')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Country<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <select name="country" id="country"
                                    class="form-control form-select @error('country') is-invalid @enderror"
                                    id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $country)
                                        <option {{ $client->country == $country->country_name ? 'selected' : '' }}
                                            value="{{ $country->id }}">{{ $country->country_name }}</option>
                                    @endforeach
                                </select>
                                @error('country')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Address<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="address" minlength="3" maxlength="150" type="text"
                                    class="form-control @error('address') is-invalid @enderror" id="exampleInputEmail1"
                                    aria-describedby="emailHelp" value="{{ $client->address }}" required
                                    placeholder="Address" autocomplete="address">
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>City/Town<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="city" minlength="3" maxlength="100" type="city"
                                    class="form-control @error('city') is-invalid @enderror" id="exampleInputEmail1"
                                    aria-describedby="emailHelp" value="{{ $client->city }}" required
                                    placeholder="City/Town" autocomplete="city">
                                @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>State/County<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <select name="state" id="state"
                                    class="form-control form-select @error('state') is-invalid @enderror"
                                    id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                    <option value="">Select State/County</option>
                                    @foreach ($states as $state)
                                        <option {{ $client->state == $state->name ? 'selected' : '' }}
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
                                <label>Postcode<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="pincode" minlength="3" maxlength="10" style="text-transform:uppercase"
                                    type="text" class="form-control @error('pincode') is-invalid @enderror"
                                    value="{{ $client->pincode }}" required placeholder="Postcode" autocomplete="off">
                                @error('pincode')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col text-start p-1">
                                <button type="submit" class="form-control btn btn-primary"
                                    style="width: fit-content;">Submit</button>
                            </div>
                        </div>
                    </form>


                </div>


            </div>
        @else
            <div class="client-dashboard">
                <div class="client-btn d-flex mb-2 ">
                    <form class="form-inline d-flex justify-content-between w-100">
                        <h3 class="text-primary">Add New Client</h3>
                    </form>
                </div>
                <div class="col">
                    <form id="registration_form" class="register-box login-box" method="POST"
                        action="{{ route('register_new_client') }}">
                        @csrf
                        <div class="row">
                            <!-- First Column -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="subscriber" class="form-label">Subscriber<span
                                            class="text-danger">*</span></label>
                                    <select name="subscriber" id="subscriber" required
                                        class="form-control form-select @error('subscriber') is-invalid @enderror">
                                        <option value="">Select Subscriber</option>
                                        @foreach ($subscribers as $sub)
                                            <option {{ old('subscriber') == $sub->id ? 'selected' : '' }}
                                                value="{{ $sub->id }}">{{ $sub->name . '(' . $sub->id . ')' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('subscriber')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Second Column -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Client Name<span
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
                        </div>
                        <div class="row">
                            <!-- First Column -->


                            <!-- Second Column -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Email<span class="text-danger" style="font-size: 18px;">*</span></label>

                                    <input name="email" minlength="3" maxlength="100" type="email"
                                        class="form-control @error('email') is-invalid @enderror" id="exampleInputEmail1"
                                        aria-describedby="emailHelp" value="{{ old('email') }}" required
                                        placeholder="Email ID" autocomplete="email">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Country<span class="text-danger" style="font-size: 18px;">*</span></label>
                                    <select name="country" id="country"
                                        class="form-control form-select @error('country') is-invalid @enderror"
                                        id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                        <option value="">Select Country</option>
                                        @foreach ($countries as $country)
                                            <option {{ old('country') == $country->id ? 'selected' : '' }}
                                                value="{{ $country->id }}">{{ $country->country_name }}
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

                            <!-- Second Column -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Nationality<span class="text-danger" style="font-size: 18px;">*</span></label>
                                    <select name="nationality" id="nationality"
                                        class="form-control form-select @error('nationality') is-invalid @enderror"
                                        id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                        <option value="">Select Nationality</option>
                                        @foreach ($countries as $country)
                                            <option {{ old('nationality') == $country->id ? 'selected' : '' }}
                                                value="{{ $country->id }}">{{ $country->country_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('nationality')
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
                                    <label>Address<span class="text-danger" style="font-size: 18px;">*</span></label>
                                    <input name="address" minlength="3" maxlength="150" type="text"
                                        class="form-control @error('address') is-invalid @enderror"
                                        id="exampleInputEmail1" aria-describedby="emailHelp"
                                        value="{{ old('address') }}" required placeholder="Address"
                                        autocomplete="address">
                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>City/Town<span class="text-danger" style="font-size: 18px;">*</span></label>
                                    <input name="city" minlength="3" maxlength="100" type="city"
                                        class="form-control @error('city') is-invalid @enderror" id="exampleInputEmail1"
                                        aria-describedby="emailHelp" value="{{ old('city') }}" required
                                        placeholder="City/Town" autocomplete="city">
                                    @error('city')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Second Column -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>State/County<span class="text-danger" style="font-size: 18px;">*</span></label>
                                    <select name="state" id="state"
                                        class="form-control form-select @error('state') is-invalid @enderror"
                                        id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                        <option value="">Select State/County</option>
                                        @if (old('state'))
                                            <option value="{{ old('state') }}" selected>
                                                {{ old('state') }}</option>
                                        @endif
                                    </select>
                                    @error('state')
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
                            {{-- <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Application Type<span class="text-danger"
                                            style="font-size: 18px;">*</span></label>

                                    <select name="job_role" id="job_role"
                                        class="form-control form-select @error('job_role') is-invalid @enderror"
                                        id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                        <option value="">Select Application Type</option>
                                        @if (old('job_role'))
                                            <option value="{{ old('job_role') }}" selected>
                                                {{ old('job_role') }}</option>
                                        @endif
                                    </select>
                                    @error('job_role')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div> --}}
                            {{-- <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Visa Country<span class="text-danger" style="font-size: 18px;">*</span></label>
                                    <select name="visa_country" id="visa_country"
                                        class="form-control form-select @error('visa_country') is-invalid @enderror"
                                        id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                        <option value="">Select Visa Country</option>
                                        @foreach ($countries as $country)
                                            <option {{ old('visa_country') == $country->country_name ? 'selected' : '' }}
                                                value="{{ $country->country_name }}">
                                                {{ $country->country_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('country')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>
                            </div> --}}
                        </div>


                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label>Postcode<span class="text-danger" style="font-size: 18px;">*</span></label>

                                    <input name="pincode" minlength="3" maxlength="10" style="text-transform:uppercase"
                                        type="text" class="form-control @error('pincode') is-invalid @enderror"
                                        value="{{ old('pincode') }}" required placeholder="Postcode" autocomplete="off">
                                    @error('pincode')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- First Column -->
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label>Alternate No.</label>
                                    <input name="alternate_no" type="text" pattern="\d*" minlength="9"
                                        maxlength="12" class="form-control @error('alternate_no') is-invalid @enderror"
                                        id="exampleInputEmail1" aria-describedby="emailHelp"
                                        value="{{ old('alternate_no') }}" placeholder="Alternate Number"
                                        autocomplete="alternate_no">
                                    @error('alternate_no')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label>Passport No.
                                        {{-- <span class="text-danger" style="font-size: 18px;">*</span> --}}
                                    </label>
                                    <input name="passport_no" minlength="6" maxlength="14"
                                        onkeyup="this.value = this.value.toUpperCase();" pattern="^[A-Z0-9]+$"
                                        type="text" class="form-control @error('passport_no') is-invalid @enderror"
                                        id="exampleInputEmail1" aria-describedby="emailHelp"
                                        value="{{ old('passport_no') }}" placeholder="Passport No"
                                        autocomplete="passport_no">
                                    @error('passport_no')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Second Column -->
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label>Date Of Birth
                                        {{-- <span class="text-danger" style="font-size: 18px;">*</span> --}}
                                    </label>
                                    <input name="dob" type="date" max="{{ date('Y-m-d') }}"
                                        class="form-control date @error('dob') is-invalid @enderror"
                                        id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('dob') }}"
                                        placeholder="Date Of Birth" autocomplete="dob">
                                    @error('dob')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        {{-- <div class="row">
                            <!-- First Column -->


                            <!-- Second Column -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Application Start Date<span class="text-danger"
                                            style="font-size: 18px;">*</span></label>

                                    <input name="job_open_date" type="date" max="{{ date('Y-m-d') }}"
                                        class="form-control date @error('job_open_date') is-invalid @enderror"
                                        id="app_start_date"
                                        onchange="document.getElementById('app_end_date').setAttribute('min',this.value);"
                                        aria-describedby="emailHelp" value="{{ old('job_open_date') }}" required
                                        placeholder="Applicaiton Start Date" autocomplete="job_open_date">
                                    @error('job_open_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- First Column -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Application Status<span class="text-danger"
                                            style="font-size: 18px;">*</span></label>
                                    <select name="job_status"
                                        class="form-control form-select @error('job_status') is-invalid @enderror"
                                        id="exampleInputEmail1" aria-describedby="emailHelp"
                                        value="{{ old('job_status') }}" required>
                                        <option value="">Select Application Status
                                        </option>
                                        <option {{ old('job_status') == 'Pending' ? 'selected' : '' }} value="Pending">
                                            Pending</option>
                                        <option {{ old('job_status') == 'In Process' ? 'selected' : '' }}
                                            value="In Process">In Process</option>
                                        <option {{ old('job_status') == 'Complete' ? 'selected' : '' }} value="Complete">
                                            Complete</option>
                                        <option {{ old('job_status') == 'Cancelled' ? 'selected' : '' }}
                                            value="Cancelled">Cancelled</option>
                                    </select>
                                    @error('job_status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>
                            </div>

                            <!-- Second Column -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Application End Date</label>
                                    <input name="job_completion_date" type="date"
                                        class="form-control date @error('job_completion_date') is-invalid @enderror"
                                        id="app_end_date" aria-describedby="emailHelp"
                                        value="{{ old('job_completion_date') }}" placeholder="Application End Date"
                                        autocomplete="job_completion_date">
                                    @error('job_completion_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>
                            </div>
                        </div> --}}

                        <div class="row">


                           
                            <div class="col text-start p-1">
                                <button type="submit" class="form-control btn btn-primary"
                                    style="width: fit-content;">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>


            </div>
        @endif
    </div>
    </div>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
        $(document).ready(() => {
            $('#registration_form').on('submit', function(e) {
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
                        text: 'Client seems to be younger than 18. Do you want to proceed?',
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
            $("#subscriber").change(function() {
                var subscriber = $(this).val();
                $.ajax({
                    url: 'check_client_limit',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        subscriber: subscriber,
                    },
                    cache: false,
                    success: function(data) {
                        //   console.log(data);
                        if (data.limit == 'full') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Oops..',
                                text: 'Client limit reached for this Subscriber!'
                            });
                            setTimeout(function() {
                                window.location.reload();
                            }, 2000);
                        }
                    }
                });
            });
            $("#country").change(function() {
                var country = $(this).val();
                // console.log(counrty);
                $.ajax({
                    url: 'get_states',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        country: country,
                    },
                    cache: false,
                    success: function(data) {
                        console.log(data);
                        $("#state").html(data);
                    }
                });
            });
            $("#subscriber").change(function() {
                var id = $(this).val();
                var name = 'subscriber';
                // console.log(counrty);
                $.ajax({
                    url: 'get_job_role',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: id,
                        name: name,
                    },
                    cache: false,
                    success: function(data) {
                        console.log(data);
                        $("#job_role").html(data);
                    }
                });
            });
        });
    </script>
    <script>
        function deleteuser(id) {
            var conf = confirm('Delete User');
            if (conf == true) {
                window.location.href = "delete_user/" + id + "";
            }
        }
    </script>

    @if (session()->has('deleted'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'User Deleted Successfully!'
            })
        </script>
    @endif

@endsection()
