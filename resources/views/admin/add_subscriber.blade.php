@extends('admin.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
            @if(isset($subscriber))
            <div class="client-dashboard">
                <div class="client-btn d-flex mb-2 ">
                    <form class="form-inline d-flex justify-content-between w-100">
                        <h3 class="text-primary">Update Subscriber</h3>
                    </form>
                </div>
                <div class="col">
                    <form id="registration_form" class="register-box login-box" method="POST" action="{{ route('register_new_subscriber') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ $subscriber->id }}" />
                        <div class="row">
                            <!-- First Column -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Name<span class="text-danger" style="font-size: 18px;">*</span></label>
                                    <input name="name" minlength="3" maxlength="100" required type="text" class="form-control @error('name') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ $subscriber->name }}" placeholder="Name" autocomplete="name">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone<span
                                            class="text-danger">*</span></label>
                                            <input name="phone" type="text" pattern="\d*" minlength="9" maxlength="12" class="form-control @error('phone') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ $subscriber->phone }}" required placeholder="Phone Number" autocomplete="phone">
                                            @error('phone')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                </div>
                            </div>
                            <!-- Second Column -->

                        </div>
                        <div class="row">
                            <!-- First Column -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                        <label>Category<span class="text-danger" style="font-size: 18px;">*</span></label>
                                        <select id="category" name="category" class="form-control form-select @error('category') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required placeholder="Email ID">
                                            <option value="">Select Category</option>
                                            @foreach($subscriber_categories as $subs_category)
                                            <option {{ ($subscriber->category == $subs_category->category_name) ? 'selected' : ''}} value="{{ $subs_category->category_name }}">{{ $subs_category->category_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                        <label>Sub Category<span class="text-danger" style="font-size: 18px;">*</span></label>
                                        <select id="subcategory" name="subcategory" class="form-control form-select @error('subcategory') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required placeholder="Email ID">
                                            <option value="">Select Sub-Category</option>
                                            @foreach($subscriber_subcategories as $subs_subcategory)
                                            @if($subscriber->category == $subs_subcategory->category_name)
                                            <option {{ ($subscriber->sub_category == $subs_subcategory->sub_category_name) ? 'selected' : ''}} value="{{ $subs_subcategory->sub_category_name }}">{{ $subs_subcategory->sub_category_name }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        @error('subcategory')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>
                            </div>

                            <!-- Second Column -->

                            <div class="col-md-4">
                                <div class="mb-3">
                                        <label>Organization<span class="text-danger" style="font-size: 18px;">*</span></label>
                                        <input name="organization" minlength="3" maxlength="100" type="text" class="form-control @error('organization') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ $subscriber->organization }}" required placeholder="Organization" autocomplete="organization">
                                        @error('organization')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- First Column -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                        <label>Designation<span class="text-danger" style="font-size: 18px;">*</span></label>
                                        <input name="designation" minlength="3" maxlength="100" type="text" class="form-control @error('designation') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ $subscriber->designation }}" required placeholder="Designation" autocomplete="designation">
                                        @error('designation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>
                            </div>

                            <!-- Second Column -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                        <label>Employee Strength<span class="text-danger" style="font-size: 18px;">*</span></label>
                                        <select name="employee_strength" required class="form-select" id="exampleInputEmail1" aria-describedby="emailHelp">
                                            <option value="">Employee Strength</option>
                                            <option {{ ($subscriber->employee_strength == "1-10") ? 'selected' : '' }} value="1-10">1-10</option>
                                            <option {{ ($subscriber->employee_strength == "10-20") ? 'selected' : '' }} value="10-20">10-20</option>
                                            <option {{ ($subscriber->employee_strength == "20-50") ? 'selected' : '' }} value="20-50">20-50</option>
                                            <option {{ ($subscriber->employee_strength == "50-100") ? 'selected' : '' }} value="50-100">50-100</option>
                                            <option {{ ($subscriber->employee_strength == "Above 100") ? 'selected' : '' }} value="Above 100">Above 100</option>
                                        </select>
                                        @error('employee_strength')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                        <label>Address<span class="text-danger" style="font-size: 18px;">*</span></label>
                                        <input name="address_line" minlength="3" maxlength="150" type="text" class="form-control @error('address_line') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ $subscriber->address_line }}" required placeholder="Address" autocomplete="address_line">
                                        @error('address_line')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- First Column -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                        <label>Country<span class="text-danger" style="font-size: 18px;">*</span></label>
                                        <select name="country" id="country" class="form-control form-select @error('country') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                            <option value="">Select Country</option>
                                            @foreach($countries as $country)
                                            <option {{ ($subscriber->country == $country->country_name) ? 'selected' : '' }} value="{{ $country->id }}">{{ $country->country_name }}</option>
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
                                        <label>State/County<span class="text-danger" style="font-size: 18px;">*</span></label>
                                        <select name="state" id="state" class="form-control form-select @error('state') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                            <option value="">Select State/County</option>
                                            @foreach ($states as $state)
                                                <option {{ $subscriber->state == $state->name ? 'selected' : '' }}
                                                    value="{{ $state->name }}">{{ $state->name }}</option>
                                            @endforeach
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
                                        <label>City/Town<span class="text-danger" style="font-size: 18px;">*</span></label>
                                        <input name="city" type="text" minlength="3" maxlength="100" class="form-control @error('city') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ $subscriber->city }}" required placeholder="City/Town" autocomplete="city">
                                        @error('city')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- First Column -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                        <label>Postcode<span class="text-danger" style="font-size: 18px;">*</span></label>
                                        <input name="pincode" minlength="3" maxlength="10" style="text-transform:uppercase" type="text" class="form-control @error('pincode') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ $subscriber->pincode }}" required placeholder="Postcode" autocomplete="pincode">
                                        @error('pincode')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>
                            </div>

                            <!-- Second Column -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                        <label>Timezone<span class="text-danger" style="font-size: 18px;">*</span></label>
                                        <select name="timezone" id="timezone" class="form-control form-select @error('timezone') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                            <option value="">Select Timezone</option>
                                            @foreach($tzlist as $zone)
                                            <option {{ ($subscriber->timezone == $zone) ? 'selected' : '' }} value="{{ $zone }}">{{ $zone }}</option>
                                            @endforeach
                                        </select>
                                        @error('timezone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                        <label>Price Plan<span class="text-danger" style="font-size: 18px;">*</span></label>
                                        <select name="membership" class="form-select" aria-label="Default select example">
                                            @foreach($membership as $plan)
                                              <option {{ ($subscriber->membership == $plan->plan_name) ? 'selected' : '' }} value="{{  $plan->plan_name }}">{{  $plan->plan_name." ".$plan->price_per_year." USD, Yearly" }}</option>
                                            @endforeach
                                            </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">




                            <div class="col-md-4 p-1 other_field" style="display: none;">
                                <label>Other<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1 other_field" style="display: none;">
                                <input id="other" name="other" minlength="3" maxlength="100" type="text" class="form-control @error('other') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ $subscriber->other_subcategory }}" placeholder="Enter other category">
                                @error('other')
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
            @else
            <div class="client-dashboard">
                <div class="client-btn d-flex mb-2 ">
                    <form class="form-inline d-flex justify-content-between w-100">
                        <h3 class="text-primary">Add New Subscriber</h3>
                    </form>
                </div>
                <div class="col">
                    <form id="registration_form" class="register-box login-box" method="POST" action="{{ route('register_new_subscriber') }}">
                        @csrf
                        <div class="row">
                            <!-- First Column -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Name<span class="text-danger" style="font-size: 18px;">*</span></label>
                                    <input name="name" minlength="3" maxlength="100" required type="text" class="form-control @error('name') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('name') }}" placeholder="Name" autocomplete="name">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Second Column -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Email<span class="text-danger" style="font-size: 18px;">*</span></label>
                                    <input name="email" minlength="3" maxlength="100" type="email" class="form-control @error('email') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('email') }}" required placeholder="Email ID" autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
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
                            <div class="col-md-4">
                                <div class="mb-3">
                                <label>Category<span class="text-danger" style="font-size: 18px;">*</span></label>
                                <select id="category" name="category" class="form-control form-select @error('category') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required placeholder="Email ID">
                                    <option value="">Select Category</option>
                                    @foreach($subscriber_categories as $subs_category)
                                    <option {{ (old('category') == $subs_category->category_name) ? 'selected':'' }} value="{{ $subs_category->category_name }}">{{ $subs_category->category_name }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                            </div>

                            <!-- Second Column -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                <label>Sub Category<span class="text-danger" style="font-size: 18px;">*</span></label>
                                <select id="subcategory" name="subcategory" class="form-control form-select @error('subcategory') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required placeholder="Email ID">
                                    <option value="">Select Sub-Category</option>
                                    @if(old('subcategory'))
                                    <option value="{{old('subcategory')}}" selected>{{old('subcategory')}}</option>
                                    @endif
                                </select>
                                @error('subcategory')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                <label>Organization<span class="text-danger" style="font-size: 18px;">*</span></label>
                                <input name="organization" minlength="3" maxlength="150" type="text" class="form-control @error('organization') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('organization') }}" required placeholder="Organization" autocomplete="organization">
                                @error('organization')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                               {{-- <label>Other<span class="text-danger" style="font-size: 18px;">*</span></label>
                               <input id="other" name="other" type="text" class="form-control @error('other') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter other category">
                               @error('other')
                                   <span class="invalid-feedback" role="alert">
                                       <strong>{{ $message }}</strong>
                                   </span>
                               @enderror --}}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- First Column -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                        <label>Designation<span class="text-danger" style="font-size: 18px;">*</span></label>
                                        <input name="designation" minlength="3" maxlength="100" type="text" class="form-control @error('designation') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('designation') }}" required placeholder="Designation" autocomplete="designation">
                                        @error('designation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>
                            </div>

                            <!-- Second Column -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                        <label>Employee Strength<span class="text-danger" style="font-size: 18px;">*</span></label>
                                        <select name="employee_strength" required class="form-select" id="exampleInputEmail1" aria-describedby="emailHelp">
                                            <option value="">Employee Strength</option>
                                            <option {{ (old('employee_strength') == "1-10") ? 'selected':'' }} value="1-10">1-10</option>
                                            <option {{ (old('employee_strength') == "10-20") ? 'selected':'' }} value="10-20">10-20</option>
                                            <option {{ (old('employee_strength') == "20-50") ? 'selected':'' }} value="20-50">20-50</option>
                                            <option {{ (old('employee_strength') == "50-100") ? 'selected':'' }} value="50-100">50-100</option>
                                            <option {{ (old('employee_strength') == "Above 100") ? 'selected':'' }} value="Above 100">Above 100</option>
                                        </select>
                                        @error('designation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                        <label>Address<span class="text-danger" style="font-size: 18px;">*</span></label>
                                        <input name="address_line" minlength="3" maxlength="150" type="text" class="form-control @error('address_line') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('address_line') }}" required placeholder="Address" autocomplete="address_line">
                                        @error('address_line')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- First Column -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                        <label>Country<span class="text-danger" style="font-size: 18px;">*</span></label>
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
                            </div>

                            <!-- Second Column -->
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
                                        <label>City/Town<span class="text-danger" style="font-size: 18px;">*</span></label>
                                        <input name="city" type="text" minlength="3" maxlength="100" class="form-control @error('city') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('city') }}" required placeholder="City/Town" autocomplete="city">
                                        @error('city')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- First Column -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                        <label>Postcode<span class="text-danger" style="font-size: 18px;">*</span></label>
                                        <input name="pincode" style="text-transform:uppercase" minlength="3" max="10" type="text" class="form-control @error('pincode') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('pincode') }}" required placeholder="Postcode" autocomplete="pincode">
                                        @error('pincode')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>
                            </div>

                            <!-- Second Column -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                        <label>Timezone<span class="text-danger" style="font-size: 18px;">*</span></label>
                                        <select name="timezone" id="timezone" class="form-control form-select @error('timezone') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                            <option value="">Select Timezone (Asia/Kolkata)</option>
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
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                        <label>Price Plan<span class="text-danger" style="font-size: 18px;">*</span></label>
                                        <select name="membership" class="form-select" aria-label="Default select example">
                                            @foreach($membership as $plan)
                                              <option {{ (old('membership') == $plan->plan_name) ? 'selected':'' }} value="{{  $plan->plan_name }}">{{  $plan->plan_name." ".$plan->price_per_year." USD, ".$plan->validity." days " }}</option>
                                            @endforeach
                                            </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Password<span class="text-danger" style="font-size: 18px;">*</span></label>

                                <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required placeholder="Password">
                                @error('password')
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
            @endif
        </div>
    </div>

  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
  </script>
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
            //   console.log(data);
                $("#subcategory").html(data);
            }
          });
        });
        $("#subcategory").change(function(){
          var subcategory = $(this).val();
          if(subcategory == "Other" || subcategory == "Others"){
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
                //   console.log(data);
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
                    console.log("zones = "+data);
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
