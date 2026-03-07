@extends('web.layout.main')

@section('main-section')

        <div class="col-lg-10 userdash-client column-client">
            <h3 class="text-primary px-2">{{ $siteuser->name }}</h3>
            <div class="profile-detail">
                <div class="col-lg-7 profile-data" style="border: 1px solid lightgrey;">
                    <div class="row">
                        <div class="col-11"></div>
                        <div class="col-1 editss">
                            <img style="cursor: pointer;" onclick="document.getElementById('update_box').style.display='flex';" src="{{ asset('web_assets/images/edit.png') }}"width="20" height="20" alt="">
                        </div>
                    </div>
                    <div class="row det-row">
                        <div class="col-6">
                            <p style="font-weight:550;">Name</p>
                            <p style="font-weight:550;">Phone Number</p>
                            <p style="font-weight:550;">Email ID</p>
                            <p style="font-weight:550;">Date of Birth</p>
                            <p style="font-weight:550;">Organization</p>
                            <p style="font-weight:550;">Designation/Role</p>
                            <p style="font-weight:550;">Country</p>
                            <p style="font-weight:550;">State/County</p>
                            <p style="font-weight:550;">City/Town</p>
                            <p style="font-weight:550;">Postcode</p>
                            <p style="font-weight:550;">Timezone</p>
                        </div>
                        <div class="col-6">
                            <p>{{ $siteuser->name }}</p>
                            <p>{{ $siteuser->phone }}</p>
                            <p>{{ $siteuser->email }}</p>
                            <p>{{  $siteuser->formatted_dob }}</p>
                            <p>{{ $siteuser->organization }}</p>
                            <p>{{ $siteuser->designation }}</p>
                            <p>{{ $siteuser->country }}</p>
                            <p>{{ $siteuser->state }}</p>
                            <p>{{ $siteuser->city }}</p>
                            <p>{{ $siteuser->pincode }}</p>
                            <p>{{ $siteuser->timezone }}</p>

                        </div>
                    </div>
                </div>
                <div class="col-lg-4 profile-pic" style="border: 1px solid lightgrey;">
                    <div class="row">
                        <div class="col-10"></div>
                        <div class="col-2">
                            <img onclick="document.getElementById('update_img_box').style.display='flex';" src="{{ asset('web_assets/images/edit.png') }}"width="20" height="20" alt="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-7 profilepic-row">
                            @if($siteuser->profile_img != "")
                            <img src="{{ asset('web_assets/users/user'.$siteuser->id.'/'.$siteuser->profile_img) }}" width="200" height="200" alt="">
                            @else
                            <img src="{{ asset('web_assets/images/profile.jpg') }}" width="200" height="200" alt="">
                            @endif
                        </div>
                        <div class="col-lg-5"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

  </div>
  <div id="update_box" style="width:100%;display: none;flex-direction: column;position: fixed;top: 0;left: 0;height: 100vh;overflow: scroll; background: rgba(0, 0, 0, 0.3);">
    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-4 loginouter-box">
            <div class="col text-end"><button class="btn btn-danger" style="width:fit-content;" onclick="document.getElementById('update_box').style.display='none';">Close</button></div>
            <form class="details-box login-box" method="POST" action="{{ route('update_siteuser') }}">
            @csrf
            <input type="hidden" name="local_time" class="localtime" />
            <input type="hidden" name="id" value="{{ $siteuser->id }}">
            <input type="hidden" name="profile" value="profile">
                <h3 class="mb-5 pt-3 text-center">Update Profile</h3>
                <div class="log-img mb-5">
                @if($siteuser->profile_img == "")
                <img src="{{ asset('web_assets/images/loginimg.png') }}" width="60" height="60" alt="">
                @else
                    <img src="{{ asset('web_assets/users/user'.$siteuser->id.'/'.$siteuser->profile_img) }}" width="60" height="60" alt="">
                @endif
                </div>
                <div class="mb-4">
                    <input name="name" minlength="3" maxlength="100" value="{{ $siteuser->name }}" required type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Name">
                </div>
                <div class="mb-4">
                    <input name="phone" value="{{ $siteuser->phone }}" required type="text" pattern="\d*" minlength="9" maxlength="12" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Phone">
                </div>
                <div class="mb-4">
                    <input name="dob" value="{{ $siteuser->dob }}" required type="date" max="{{ date('Y-m-d') }}" class="form-control date" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Date of Birth">
                </div>
                <div class="mb-4">
                    <input name="organization" minlength="3" maxlength="100" value="{{ $siteuser->organization }}" required type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Organization Name">
                </div>
                <div class="mb-4">
                    {{-- <input name="designation" value="{{ $siteuser->designation }}" required type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Your designation"> --}}
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
                </div>
                <div class="mb-4">
                <div class="mb-4">
                    <select name="employee_strength" required class="form-select" id="exampleInputEmail1" aria-describedby="emailHelp">
                    <option value="">Employee Strength</option>
                    <option {{ ($siteuser->employee_strength == "1-10") ? 'selected' : ''}} value="1-10">1-10</option>
                    <option {{ ($siteuser->employee_strength == "10-20") ? 'selected' : ''}} value="10-20">10-20</option>
                    <option {{ ($siteuser->employee_strength == "20-50") ? 'selected' : ''}} value="20-50">20-50</option>
                    <option {{ ($siteuser->employee_strength == "50-100") ? 'selected' : ''}} value="50-100">50-100</option>
                    <option {{ ($siteuser->employee_strength == "Above 100") ? 'selected' : ''}} value="Above 100">Above 100</option>
                    </select>
                </div>
                </div>
                <div class="mb-4">
                    <input name="address_line" minlength="3" maxlength="150" value="{{ $siteuser->address_line }}" required type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Address line">
                </div>
                <div class="mb-4">
                    <select name="country" id="country" required class="form-select" aria-label="Default select example">
                        <option value="">Country</option>
                        @foreach($countries as $country)
                        <option {{ ($siteuser->country == $country->country_name) ? 'selected' : ''}} value="{{ $country->id }}">{{ $country->country_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <select name="state" id="state" required class="form-select" aria-label="Default select example">
                        @foreach ($states as $state)
                            <option {{ $siteuser->state == $state->name ? 'selected' : '' }}
                                value="{{ $state->name }}">{{ $state->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <input type="text" minlength="3" maxlength="100" value="{{ $siteuser->city }}" name="city" required class="form-control" aria-label="Default select example" placeholder="City">
                </div>
                <div class="mb-4">
                    <input name="pincode" minlength="3" maxlength="10" style="text-transform:uppercase" value="{{ $siteuser->pincode }}" required type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Postcode">
                </div>
                <div class="mb-4">
                    <select name="timezone" id="timezone" required class="form-select" aria-label="Default select example">
                        <option value="">Select Timezone</option>
                        @foreach($tzlist as $zone)
                        <option {{ ($siteuser->timezone == $zone) ? 'selected' : ''}} value="{{ $zone }}">{{ $zone }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="form-control btn btn-primary mb-4">Save</button>
                
            </form>
        </div>
        <div class="col-lg-4"></div>
    </div>
</div>
<div id="update_img_box" style="width:100%;display: none;flex-direction: column;position: fixed;top: 0;left: 0;height: 100vh;overflow: scroll; background: rgba(0, 0, 0, 0.3);justify-content: center;">
    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-4 loginouter-box">
            <div class="col text-end"><button class="btn btn-danger" style="width:fit-content;" onclick="document.getElementById('update_img_box').style.display='none';">Close</button></div>
            <form class="details-box login-box" method="POST" action="{{ route('update_siteuser') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="local_time" class="localtime" />
            <input type="hidden" name="id" value="{{ $siteuser->id }}">
            <input type="hidden" name="profile_image" value="profile_image">
                <h3 class="mb-5 pt-3 text-center">Update Profile Image</h3>
                <div class="log-img mb-5">
                @if($siteuser->profile_img == "")
                <img src="{{ asset('web_assets/images/loginimg.png') }}" width="60" height="60" alt="">
                @else
                    <img style="border-radius: 50%;" src="{{ asset('web_assets/users/user'.$siteuser->id.'/'.$siteuser->profile_img) }}" width="60" height="60" alt="">
                @endif
                </div>

                <div class="col d-flex justify-content-center align-items-center mb-5" onclick="document.getElementById('select_pic').click();">
                    <div style="width:100%;height:200px;box-shadow: 0px 0px 5px 0px lightgrey;border-radius: 10px;justify-content: center;align-items: center;display: flex;position:relative;" title="click to upload file">
                        <input id="select_pic" type="file" name="profile_img" style="display: none;">
                        <p style="position:absolute;">Click to Upload File</p>
                        <img id="profile_pic_preview" style="width: auto;height: auto;max-width: 100%;max-height: 100%;" src="">
                    </div>
                </div>

                <button type="submit" disabled="disabled" id="save_photo" class="form-control btn btn-primary mb-4">Save</button>
                
            </form>
        </div>
        <div class="col-lg-4"></div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
</script>
<script>
    $(document).ready(() => {
        $("#select_pic").change(function () {
            const file = this.files[0];
            var filepath = $(this).val();
            var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.JPG|\.JPEG|\.PNG)$/i;
            if (!allowedExtensions.exec(filepath)) {
                Swal.fire({
                    title: "Oops..",
                    icon:"info",
                    html: "Please select valid file format <br>( jpg, jpeg, png )"
                });
                $(this).val("");
                return false;
            }
            const size = (this.files[0].size / 1024 / 1024).toFixed(2);
            if (size > 4) {
                Swal.fire({
                    title: "Oops..",
                    icon:"info",
                    html: "Please select file upto 4MB"
                });
                $(this).val("");
                return false;
            }
            if (file) {
                let reader = new FileReader();
                reader.onload = function (event) {
                    $("#profile_pic_preview").attr("src", event.target.result);
                };
                reader.readAsDataURL(file);
            }
            $("#save_photo").removeAttr('disabled');
        });
        $("#country").change(function(){
            var country = $(this).val();
            //   console.log(country);
            $.ajax({
                url: "{{ route('get_states') }}",
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
@error('profile_img')
    <script>
      Swal.fire({

        icon: 'error',
        title: 'Oops...',
        text: 'Please select valid Image!'
      })
    </script>
@enderror
@if(session()->has('success'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Congratulations',
        text: 'Profile has been Updated!'
      })
    </script>

@endif

@endsection()
