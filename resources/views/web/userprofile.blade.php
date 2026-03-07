@extends('web.layout.main')

@section('main-section')

    <div class="col-lg-10 userdash-client column-client">
        {{-- <h3>Profile</h3> --}}
        <!-- <div class="client-btn d-flex justify-content-between mb-4"> -->
        <div class="col-12 d-flex justify-content-between align-items-center mb-3">
            <h3 style="font-size: 1.7rem;font-weight: 500;line-height: 1.2" class="text-primary text-center flex-grow-1 text-center m-0">Profile</h3>
            <a href="#" onclick="document.getElementById('change_password').style.display='flex';">Change Password</a>
        </div>
        <div class="profile-detail">
            <div class="col-lg-7 profile-data" style="border: 1px solid lightgrey;">
                <div class="row">
                    <div class="col-11"></div>
                    <div class="col-1 editss">
                        <img style="cursor: pointer;" onclick="document.getElementById('update_box').style.display='flex';"
                            src="{{ asset('web_assets/images/edit.png') }}"width="20" height="20" alt="">
                    </div>
                </div>
                <div class="row det-row">
                    <div class="col-6">
                        <p style="font-weight:550;">Name</p>
                    </div>
                    <div class="col-6">
                        <p>{{ $user->name }}</p>
                    </div>
                    <div class="col-6">
                        <p style="font-weight:550;">Phone Number</p>
                    </div>
                    <div class="col-6">
                        <p>{{ $user->phone }}</p>
                    </div>
                    <div class="col-6">
                        <p style="font-weight:550;">Email ID</p>
                    </div>
                    <div class="col-6">
                        <p>{{ $user->email }}</p>
                    </div>
                    <div class="col-6">
                        <p style="font-weight:550;">Organization</p>
                    </div>
                    <div class="col-6">
                        <p>{{ $user->organization }}</p>
                    </div>
                    <div class="col-6">
                        <p style="font-weight:550;">Designation</p>
                    </div>
                    <div class="col-6">
                        <p>{{ $user->designation }}</p>
                    </div>
                    <div class="col-6">
                        <p style="font-weight:550;">Category</p>
                    </div>
                    <div class="col-6">
                        <p>{{ $user->category }}</p>
                    </div>
                    <div class="col-6">
                        <p style="font-weight:550;">Sub-Category</p>
                    </div>
                    <div class="col-6">
                        <p>{{ $user->sub_category }}@if($user->sub_category == "Other")({{$user->oter_subcategory}})@endif</p>
                    </div>
                    <div class="col-6">
                        <p style="font-weight:550;">Country</p>
                    </div>
                    <div class="col-6">
                        <p>{{ $user->country }}</p>
                    </div>
                    <div class="col-6">
                        <p style="font-weight:550;">State/County</p>
                    </div>
                    <div class="col-6">
                        <p>{{ $user->state }}</p>
                    </div>
                    <div class="col-6">
                        <p style="font-weight:550;">City/Town</p>
                    </div>
                    <div class="col-6">
                        <p>{{ $user->city }}</p>
                    </div>
                    <div class="col-6">
                        <p style="font-weight:550;">Address</p>
                    </div>
                    <div class="col-6">
                        <p>{{ $user->address_line }}</p>
                    </div>
                    <div class="col-6">
                        <p style="font-weight:550;">Postcode</p>
                    </div>
                    <div class="col-6">
                        <p>{{ $user->pincode }}</p>
                    </div>
                    <div class="col-6">
                        <p style="font-weight:550;">Timezone</p>
                    </div>
                    <div class="col-6">
                        <p>{{ $user->timezone }}</p>

                    </div>
                </div>
            </div>
            <div class="col-lg-4 profile-pic" style="border: 1px solid lightgrey;">
                <div class="row">
                    <div class="col-10"></div>
                    <div class="col-2">
                        <img style="cursor: pointer;" onclick="document.getElementById('update_img_box').style.display='flex';"
                            src="{{ asset('web_assets/images/edit.png') }}"width="20" height="20" alt="">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-7 profilepic-row">
                        @if ($user->profile_img != '')
                            <img src="{{ asset('web_assets/users/user' . $user->id . '/' . $user->profile_img) }}" width="200"
                                height="200" alt="">
                        @else
                            <img src="{{ asset('web_assets/images/profile.jpg') }}" width="200" height="200"
                                alt="">
                        @endif
                    </div>
                    <div class="col-lg-5"></div>
                </div>
                @if ($user->user_type == 'Subscriber')
                    <hr>
                    <div class="row">
                        <div class="col-10"></div>
                        <div class="col-2">
                            <img style="cursor: pointer;" onclick="document.getElementById('update_logo_box').style.display='flex';"
                                src="{{ asset('web_assets/images/edit.png') }}"width="20" height="20"
                                alt="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-7 profilepic-row">
                            @if ($user->organization_logo != '')
                                <img src="{{ asset('web_assets/users/user' . $user->id . '/' . $user->organization_logo) }}"
                                    width="100%" height="auto" alt="">
                            @else
                                <img src="{{ asset('web_assets/images/default_logo.png') }}" width="100%" height="auto"
                                    alt="">
                            @endif
                        </div>
                        <div class="col-lg-5"></div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    </div>

    </div>
    <div id="update_box"
        style="width:100%;display: none;flex-direction: column;position: fixed;top: 0;left: 0;height: 100vh;overflow: scroll; background: rgba(0, 0, 0, 0.3);">
        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4 loginouter-box">
                <div class="col text-end"><button class="btn btn-danger" style="width:fit-content;"
                        onclick="document.getElementById('update_box').style.display='none';">Close</button></div>
                <form class="details-box login-box" method="POST" action="{{ route('update_user') }}">
                    @csrf
                    <input type="hidden" name="profile" value="profile">
                    <input type="hidden" name="local_time" class="localtime" />
                    <h3 class="mb-5 pt-3 text-center">Update Profile</h3>
                    <div class="log-img mb-5">
                        @if ($user->profile_img == '')
                            <img src="{{ asset('web_assets/images/loginimg.png') }}" width="60" height="60"
                                alt="">
                        @else
                            <img src="{{ asset('web_assets/users/user' . $user->id . '/' . $user->profile_img) }}" width="60"
                                height="60" alt="">
                        @endif
                    </div>
                    <div class="mb-4">
                        <input name="name" minlength="3" maxlength="100" value="{{ $user->name }}" required
                            type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                            placeholder="Name">
                    </div>
                    <div class="mb-4">
                        <input name="phone" value="{{ $user->phone }}" required type="text" pattern="\d*"
                            minlength="10" maxlength="10" class="form-control" id="exampleInputEmail1"
                            aria-describedby="emailHelp" placeholder="Phone">
                    </div>
                    <div class="mb-4">
                        <input name="organization" minlength="3" maxlength="100" value="{{ $user->organization }}"
                            required type="text" class="form-control" id="exampleInputEmail1"
                            aria-describedby="emailHelp" placeholder="Organization Name">
                    </div>
                    <div class="mb-4">
                        <input name="designation" minlength="3" maxlength="100" value="{{ $user->designation }}"
                            required type="text" class="form-control" id="exampleInputEmail1"
                            aria-describedby="emailHelp" placeholder="Your designation">
                    </div>
                    <div class="mb-4">
                        <div class="mb-4">
                            <select name="employee_strength" required class="form-select" id="exampleInputEmail1"
                                aria-describedby="emailHelp">
                                <option value="">Employee Strength</option>
                                <option {{ $user->employee_strength == '1-10' ? 'selected' : '' }} value="1-10">1-10
                                </option>
                                <option {{ $user->employee_strength == '10-20' ? 'selected' : '' }} value="10-20">10-20
                                </option>
                                <option {{ $user->employee_strength == '20-50' ? 'selected' : '' }} value="20-50">20-50
                                </option>
                                <option {{ $user->employee_strength == '50-100' ? 'selected' : '' }} value="50-100">
                                    50-100</option>
                                <option {{ $user->employee_strength == 'Above 100' ? 'selected' : '' }}
                                    value="Above 100">Above 100</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <input name="address_line" minlength="3" maxlength="150" value="{{ $user->address_line }}"
                            required type="text" class="form-control" id="exampleInputEmail1"
                            aria-describedby="emailHelp" placeholder="Address line">
                    </div>
                    <div class="mb-4">
                        <select name="country" id="country" required class="form-select"
                            aria-label="Default select example">
                            <option selected value="">Country</option>
                            @foreach ($countries as $country)
                                <option {{ $user->country == $country->country_name ? 'selected' : '' }}
                                    value="{{ $country->id }}">{{ $country->country_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <select name="state" id="state" required class="form-select"
                            aria-label="Default select example">
                            @foreach ($states as $state)
                                <option {{ $user->state == $state->name ? 'selected' : '' }} value="{{ $state->name }}">
                                    {{ $state->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <input type="text" minlength="3" maxlength="100" value="{{ $user->city }}"
                            name="city" required class="form-control" aria-label="Default select example"
                            placeholder="City">
                    </div>
                    <div class="mb-4">
                        <input name="pincode" minlength="3" maxlength="10" style="text-transform:uppercase"
                            value="{{ $user->pincode }}" required type="text" class="form-control"
                            id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Postcode">
                    </div>
                    <div class="mb-4">
                        <select name="timezone" id="timezone" required class="form-select"
                            aria-label="Default select example">
                            <option selected value="">Select Timezone</option>
                            @foreach ($tzlist as $zone)
                                <option {{ $user->timezone == $zone ? 'selected' : '' }} value="{{ $zone }}">
                                    {{ $zone }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="form-control btn btn-primary mb-4">Save</button>
                    <!-- <a href="dashboard.html" class="btn btn-primary mb-4">Next</a> -->
                    <!-- <p class="text-center reg-logbtn">Already have an account! <a href="{{ route('login') }}" class="text-dark"> <strong>Login</strong></a></p> -->
                </form>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </div>
    <div id="update_img_box"
        style="width:100%;display: none;flex-direction: column;position: fixed;top: 0;left: 0;height: 100vh;overflow: scroll; background: rgba(0, 0, 0, 0.3);justify-content: center;">
        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4 loginouter-box">
                <div class="col text-end"><button class="btn btn-danger" style="width:fit-content;"
                        onclick="document.getElementById('update_img_box').style.display='none';">Close</button></div>
                <form class="details-box login-box" method="POST" action="{{ route('update_user') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="profile_image" value="profile_image">
                    <input type="hidden" name="local_time" class="localtime" />
                    <h3 class="mb-5 pt-3 text-center">Update Profile Image</h3>
                    <div class="log-img mb-5">
                        @if ($user->profile_img == '')
                            <img src="{{ asset('web_assets/images/loginimg.png') }}" width="60" height="60"
                                alt="">
                        @else
                            <img style="border-radius: 50%;"
                                src="{{ asset('web_assets/users/user' . $user->id . '/' . $user->profile_img) }}"
                                width="60" height="60" alt="">
                        @endif
                    </div>

                    <div class="col d-flex justify-content-center align-items-center mb-5"
                        onclick="document.getElementById('select_pic').click();">
                        <div style="width:100%;height:200px;box-shadow: 0px 0px 5px 0px lightgrey;border-radius: 10px;justify-content: center;align-items: center;display: flex;position:relative;"
                            title="click to upload file">
                            <input id="select_pic" type="file" name="profile_img" style="display: none;">
                            <p style="position:absolute;">Click to Upload File</p>
                            <img id="profile_pic_preview"
                                style="width: auto;height: auto;max-width: 100%;max-height: 100%;" src="">
                        </div>
                    </div>

                    <button type="submit" disabled="disabled" id="save_photo"
                        class="form-control btn btn-primary mb-4">Save</button>
                    <!-- <a href="dashboard.html" class="btn btn-primary mb-4">Next</a> -->
                    <!-- <p class="text-center reg-logbtn">Already have an account! <a href="{{ route('login') }}" class="text-dark"> <strong>Login</strong></a></p> -->
                </form>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </div>
    <div id="update_logo_box"
        style="width:100%;display: none;flex-direction: column;position: fixed;top: 0;left: 0;height: 100vh;overflow: scroll; background: rgba(0, 0, 0, 0.3);justify-content: center;">
        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4 loginouter-box">
                <div class="col text-end"><button class="btn btn-danger" style="width:fit-content;"
                        onclick="document.getElementById('update_logo_box').style.display='none';">Close</button></div>
                <form class="details-box login-box" method="POST" action="{{ route('update_user') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="logo_image" value="logo_image">
                    <input type="hidden" name="local_time" class="localtime" />
                    <h3 class="mb-5 pt-3 text-center">Update Organization Logo</h3>
                    <div class="log-img mb-5">
                        @if ($user->profile_img == '')
                            <img src="{{ asset('web_assets/images/loginimg.png') }}" width="60" height="60"
                                alt="">
                        @else
                            <img style="border-radius: 50%;"
                                src="{{ asset('web_assets/users/user' . $user->id . '/' . $user->profile_img) }}"
                                width="60" height="60" alt="">
                        @endif
                    </div>

                    <div class="col d-flex justify-content-center align-items-center mb-5"
                        onclick="document.getElementById('select_logo').click();">
                        <div style="width:100%;height:200px;box-shadow: 0px 0px 5px 0px lightgrey;border-radius: 10px;justify-content: center;align-items: center;display: flex;position:relative;"
                            title="click to upload file">
                            <input id="select_logo" type="file" name="organization_logo" style="display: none;">
                            <p style="position:absolute;">Click to Upload File</p>
                            <img id="logo_preview" style="width: auto;height: auto;max-width: 100%;max-height: 100%;"
                                src="">
                        </div>
                    </div>

                    <button type="submit" disabled id="save_logo"
                        class="form-control btn btn-primary mb-4">Save</button>
                    <!-- <a href="dashboard.html" class="btn btn-primary mb-4">Next</a> -->
                    <!-- <p class="text-center reg-logbtn">Already have an account! <a href="{{ route('login') }}" class="text-dark"> <strong>Login</strong></a></p> -->
                </form>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </div>
    <div id="change_password"
        style="width:100%;display: none;flex-direction: column;position: fixed;top: 0;left: 0;height: 100vh;overflow: scroll; background: rgba(0, 0, 0, 0.3);">
        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4 loginouter-box">
                <div class="col text-end"><button class="btn btn-danger" style="width:fit-content;"
                        onclick="document.getElementById('change_password').style.display='none';">Close</button></div>
                <form class="details-box login-box" method="POST" action="{{ route('change_password') }}">
                    @csrf
                    <input type="hidden" name="local_time" class="localtime" />
                    <h3 class="mb-5 pt-3 text-center">Change Password</h3>
                    <div class="log-img mb-5">
                        @if ($user->profile_img == '')
                            <img src="{{ asset('web_assets/images/loginimg.png') }}" width="60" height="60"
                                alt="">
                        @else
                            <img src="{{ asset('web_assets/users/user' . $user->id . '/' . $user->profile_img) }}" width="60"
                                height="60" alt="">
                        @endif
                    </div>
                    <div class="mb-4">
                        <input name="old_password" required type="password" class="form-control" id="old_password" aria-describedby="emailHelp" placeholder="Old Password">
                    </div>
                    <div class="mb-4">
                        <input name="new_password" pattern="^[a-zA-Z0-9$#@!&%_=+-]+$" minlength="8" maxlength="100" required type="password" class="form-control" id="new_password" aria-describedby="emailHelp" placeholder="New Password">
                    </div>

                    <div class="mb-4">
                        <input name="password" pattern="^[a-zA-Z0-9$#@!&%_=+-]+$" minlength="8" maxlength="100" required type="password" class="form-control" id="password" aria-describedby="emailHelp" placeholder="Confirm Password">
                    </div>
                    <button type="submit" id="save_password" disabled class="form-control btn btn-primary mb-4">Save</button>
                    <!-- <a href="dashboard.html" class="btn btn-primary mb-4">Next</a> -->
                    <!-- <p class="text-center reg-logbtn">Already have an account! <a href="{{ route('login') }}" class="text-dark"> <strong>Login</strong></a></p> -->
                </form>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
        $(document).ready(() => {
            $("#select_pic").change(function() {
                const file = this.files[0];
                var filepath = $(this).val();
                var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.JPG|\.JPEG|\.PNG)$/i;
                if (!allowedExtensions.exec(filepath)) {
                    Swal.fire({
                        title: "Oops..",
                        icon: "info",
                        html: "Please select valid file format <br>( jpg, jpeg, png )"
                    });
                    $(this).val("");
                    return false;
                }
                const size = (this.files[0].size / 1024 / 1024).toFixed(2);
                if (size > 4) {
                    Swal.fire({
                        title: "Oops..",
                        icon: "info",
                        html: "Please select file upto 4MB"
                    });
                    $(this).val("");
                    return false;
                }
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        $("#profile_pic_preview").attr("src", event.target.result);
                    };
                    reader.readAsDataURL(file);
                }
                $("#save_photo").removeAttr('disabled');
            });
            $("#select_logo").change(function() {
                const file = this.files[0];
                var filepath = $(this).val();
                var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.JPG|\.JPEG|\.PNG)$/i;
                if (!allowedExtensions.exec(filepath)) {
                    Swal.fire({
                        title: "Oops..",
                        icon: "info",
                        html: "Please select valid file format <br>( jpg, jpeg, png )"
                    });
                    $(this).val("");
                    return false;
                }
                const size = (this.files[0].size / 1024 / 1024).toFixed(2);
                if (size > 4) {
                    Swal.fire({
                        title: "Oops..",
                        icon: "info",
                        html: "Please select file upto 4MB"
                    });
                    $(this).val("");
                    return false;
                }
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        $("#logo_preview").attr("src", event.target.result);
                    };
                    reader.readAsDataURL(file);
                }
                $("#save_logo").removeAttr('disabled');
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
                $.ajax({
                    url: '{{ route('get_timezone') }}',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        country: country,
                    },
                    cache: false,
                    success: function(data) {
                        // console.log("zones = "+data);
                        $("#timezone").html(data);
                    }
                });
            });
            $("#password").keyup(function(){
                var new_pass = $("#new_password").val();
                var pass = $(this).val();
                if(pass == new_pass){
                    $("#save_password").removeAttr('disabled');
                }
                else{
                    $("#save_password").attr('disabled','disabled');
                }
            });
            $("#new_password").keyup(function(){
                var new_pass = $("#old_password").val();
                var pass = $(this).val();
                if(pass == new_pass){
                    $("#save_password").removeAttr('disabled');
                }
                else{
                    $("#save_password").attr('disabled','disabled');
                }
            });
        });
    </script>
    @error('profile_img')
        <script>
            Swal.fire({

                icon: 'error',
                title: 'Oops...',
                text: 'Please select valid Image!'
            })
        </script>
    @enderror
    @if (session()->has('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Congratulations',
                text: 'Profile has been Updated!'
            })
        </script>
    @endif
    @if (session()->has('logo_updated'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Congratulations',
                text: 'Logo has been Updated!'
            })
        </script>
    @endif

    @if(session()->has('price_plan_expiry'))
  <script>
    Swal.fire({
      icon: 'warning',
      title: 'Your Subscription Plan has Expired',
      html: 'Please <a @if($user->user_type == "Subscriber") href="{{ route('membership') }}" @else href="#" @endif>Renew/Upgrade</a> to Continue!'
    })
  </script>

@endif
    @if (session()->has('wrong_password'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Wrong Password!',
                text: 'Please Enter Correct Old Password'
            })
        </script>
    @endif

@endsection()
