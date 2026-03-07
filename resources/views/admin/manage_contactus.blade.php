@extends('admin.layout.main')

@section('main-section')

        <div class="col-lg-10 userdash-client column-client">
            <h3 class="text-primary">Contact Us Management</h3>
            <div class="profile-detail">
                <div class="col-lg-7 profile-data" style="border-color: lightgrey;">
                    <div class="row">
                        <div class="col-11"></div>
                        <div class="col-1 editss">
                            <img style="cursor: pointer;" onclick="document.getElementById('update_box').style.display='flex';" src="{{ asset('web_assets/images/edit.png') }}"width="20" height="20" alt="">
                        </div>
                    </div>
                    <div class="row det-row">
                        <div class="col">
                            <div class="row">
                                <div class="col-6">
                                    <p style="font-weight:550;">Contact No.</p>
                                </div>
                                <div class="col-6">
                                    <p>@if($contact->contact_no == "") --- @else {{ $contact->contact_no }} @endif</p>
                                </div>
                            {{-- <div class="col-6">
                                    <p style="font-weight:550;">Alternate No.</p>
                                </div>
                                <div class="col-6">
                                    <p>@if($contact->alternate_no == "") --- @else {{ $contact->alternate_no }} @endif</p>
                                </div>  --}}
                                <div class="col-6">
                                    <p style="font-weight:550;">Location</p>
                                </div>
                                <div class="col-6">
                                    <p>@if($contact->location == "") --- @else {{ $contact->location }} @endif</p>
                                </div>
                                <div class="col-6">
                                    <p style="font-weight:550;">Email</p>
                                </div>
                                <div class="col-6">
                                    <p>@if($contact->email == "") --- @else {{ $contact->email }} @endif</p>
                                </div>
                                <div class="col-6">
                                    <p style="font-weight:550;">Website</p>
                                </div>
                                <div class="col-6">
                                    <p>@if($contact->website == "") --- @else {{ $contact->website }} @endif</p>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-6">
                            <p>@if($contact->contact_no == "") --- @else {{ $contact->contact_no }} @endif</p>
                            <p>@if($contact->alternate_no == "") --- @else {{ $contact->alternate_no }} @endif</p>
                            <p>@if($contact->location == "") --- @else {{ $contact->location }} @endif</p>
                            <p>@if($contact->email == "") --- @else {{ $contact->email }} @endif</p>
                            <p>@if($contact->website == "") --- @else {{ $contact->website }} @endif</p>

                        </div>  --}}
                    </div>
                </div>
                <div class="col-lg-4 profile-pic" style="border-color: lightgrey;">
                    <div class="row">
                        <div class="col-10"></div>
                        <div class="col-2">
                            <img onclick="document.getElementById('update_img_box').style.display='flex';" src="{{ asset('web_assets/images/edit.png') }}"width="20" height="20" alt="">
                        </div>
                    </div>
                    <div class="row m-0">
                        <div class="col-12 profilepic-row m-0 mt-3">
                                @if($contact->banner != "")
                                <img src="{{ asset('admin_assets/contactus/'.$contact->banner) }}" width="100%" height="auto" alt="">
                                @else
                                <img src="{{ asset('admin_assets/images/default_banner.jpg') }}" width="100%" height="auto" alt="">
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
            <form class="details-box login-box" method="POST" action="{{ route('update_contactus') }}">
                @csrf
                <input type="hidden" name="id" value="{{ $contact->id }}">
                <input type="hidden" name="contact_details" value="contact_details">
                <h3 class="mb-5 pt-3 text-center">Update Contact Us</h3>
                <div class="log-img mb-5">
                    @if ($contact->banner == '')
                        <img src="{{ asset('admin_assets/images/loginimg.png') }}" width="60" height="60"
                            alt="">
                    @else
                        <img src="{{ asset('admin_assets/contactus/' . $contact->banner) }}" width="60"
                            height="60" alt="">
                    @endif
                </div>
                <div class="mb-4">
                    <input name="contact_no" value="{{ $contact->contact_no }}" required type="text" pattern="\d*" minlength="10" maxlength="10" class="form-control"
                        id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Contact Number">
                </div>
                <div class="mb-4">
                    <input name="alternate_no" value="{{ $contact->alternate_no }}" required type="text" pattern="\d*" minlength="10" maxlength="10" class="form-control"
                        id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Alternate Number">
                </div>
                <div class="mb-4">
                    <input name="location" minlength="3" maxlength="250" value="{{ $contact->location }}" required type="text"
                        class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                        placeholder="Location">
                </div>
                <div class="mb-4">
                    <input type="email" minlength="3" maxlength="150" value="{{ $contact->email }}" name="email" required class="form-control"
                        aria-label="Default select example" placeholder="Email">
                </div>
                <div class="mb-4">
                    <input name="website" minlength="3" maxlength="150" value="{{ $contact->website }}" required type="text" class="form-control"
                        id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Website URL">
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
            <div class="col text-end"><button class="btn btn-danger" style="width:fit-content;" onclick="document.getElementById('update_img_box').style.display='none';">Close</button></div>
            <form class="details-box login-box" method="POST" action="{{ route('update_contactus') }}"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $contact->id }}">
                <input type="hidden" name="banner" value="banner">
                <h3 class="mb-5 pt-3 text-center">Update Contact Us Banner</h3>
                <div class="log-img mb-5">
                    @if ($contact->banner == '')
                        <img src="{{ asset('admin_assets/images/loginimg.png') }}" width="60" height="60"
                            alt="">
                    @else
                        <img style="border-radius: 50%;"
                            src="{{ asset('admin_assets/contactus/' . $contact->banner) }}"
                            width="60" height="60" alt="">
                    @endif
                </div>

                <div class="col d-flex justify-content-center align-items-center mb-5"
                    onclick="document.getElementById('select_pic').click();">
                    <div style="width:100%;height:200px;box-shadow: 0px 0px 5px 0px lightgrey;border-radius: 10px;justify-content: center;align-items: center;display: flex;position:relative;"
                        title="click to upload file">
                        <input id="select_pic" type="file" name="banner" style="display: none;">
                        <p style="position:absolute;">Click to Upload File</p>
                        <img id="profile_pic_preview"
                            style="width: auto;height: auto;max-width: 100%;max-height: 100%;" src="">
                    </div>
                </div>

                <button type="submit" class="form-control btn btn-primary mb-4">Save</button>
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
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        $("#profile_pic_preview").attr("src", event.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
    @error('banner')
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
                text: 'Contact Us Details has been Updated!'
            })
        </script>
    @endif

@endsection()
