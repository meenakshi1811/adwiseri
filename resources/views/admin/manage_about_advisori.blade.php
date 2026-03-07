@extends('admin.layout.main')

@section('main-section')

        <div class="col-lg-10 userdash-client column-client">
            <h3 class="text-primary">Manage About Adwiseri</h3>
            <div class="profile-detail">
                <div class="col-lg-7 profile-data" style="border-color: lightgrey;">
                    <div class="row">
                        <div class="col editss text-end px-3">
                            <img class="mt-2 me-2" style="cursor: pointer;" onclick="document.getElementById('update_box').style.display='flex';" src="{{ asset('web_assets/images/edit.png') }}"width="20" height="20" alt="">
                        </div>
                    </div>
                    <div class="row det-row">
                        <div class="col">
                            <div class="row">
                                <div class="col-6">
                                    <p style="font-weight:550;">Image</p>
                                </div>
                                <div class="col-6">
                                    <div class="col p-2" style="height: 150px; width:100%;border:1px solid lightgrey;">
                                        <img src="{{ asset('admin_assets/about_advisori/image/'.$about_adwiseri->image) }}" style="width: 100%;height:100%;" />
                                    </div>
                                </div>
                            <hr class="my-3">
                                <div class="col-6">
                                    <p style="font-weight:550;">Heading</p>
                                </div>
                                <div class="col-6">
                                    <p>{{ $about_adwiseri->heading }}</p>
                                </div>
                            <hr class="my-3">
                                <div class="col-6">
                                    <p style="font-weight:550;">Description</p>
                                </div>
                                <div class="col-6">
                                    <p>{{ $about_adwiseri->content }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 profile-pic" style="border-color: lightgrey;">
                    <div class="row">
                        <div class="col-10"></div>
                        <div class="col-2">
                            <img onclick="document.getElementById('update_img_box').style.display='flex';" src="{{ asset('web_assets/images/edit.png') }}"width="20" height="20" alt="">
                        </div>
                    </div>
                    <div class="col ps-2">Banner</div>
                    <div class="row m-0">
                        <div class="col-12 profilepic-row m-0 mt-3">
                                @if($about_adwiseri->banner != "")
                                <img src="{{ asset('admin_assets/about_advisori/banner/'.$about_adwiseri->banner) }}" width="100%" height="auto" alt="">
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
                <form class="details-box login-box" method="POST" action="{{ route('update_about_adwiseri') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $about_adwiseri->id }}">
                <input type="hidden" name="advisori_details" value="advisori_details">
                    <h3 class="mb-5 pt-3 text-center">Update Featrue</h3>
                    <div class="log-img mb-5">
                    @if($user->profile_img == "")
                    <img src="{{ asset('web_assets/images/loginimg.png') }}" width="60" height="60" alt="">
                    @else
                        <img src="{{ asset('web_assets/users/user'.$user->id.'/'.$user->profile_img) }}" width="60" height="60" alt="">
                    @endif
                    </div>
                    <div class="mb-4">
                        <div class="col p-2 d-flex justify-content-center align-items-center" style="position: relative;border:1px solid lightgrey;border-radius:7px;height:100px;width:100%;overflow:hidden;">
                            <input id="advisori_img" name="image" style="position: absolute;width:100%;height:100%;top:0px;left:0px;opacity:0;" type="file" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Plan Name">
                            <img id="advisori_img_preview" src="{{ asset('admin_assets/about_advisori/image/'.$about_adwiseri->image) }}" style="heigth:auto;width:auto;max-width:100%;max-height:100%;" />
                        </div>
                    </div>
                    <div class="mb-4">
                        <input name="heading" minlength="3" maxlength="150" value="{{ $about_adwiseri->heading }}" required type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Data Limit">
                    </div>
                    <div class="mb-4">
                        <textarea name="content" rows="5" required class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Content">{{ $about_adwiseri->content }}</textarea>
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
                <form class="details-box login-box" method="POST" action="{{ route('update_about_adwiseri') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $about_adwiseri->id }}">
                    <input type="hidden" name="advisori_banner" value="advisori_banner">
                    <h3 class="mb-5 pt-3 text-center">Update adwiseri Banner</h3>
                    <div class="log-img mb-5">
                        @if ($about_adwiseri->banner == '')
                            <img src="{{ asset('admin_assets/images/loginimg.png') }}" width="60" height="60"
                                alt="">
                        @else
                            <img style="border-radius: 50%;"
                                src="{{ asset('admin_assets/about_advisori/banner/' . $about_adwiseri->banner) }}"
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
            $("#advisori_img").change(function() {
                const file = this.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        $("#advisori_img_preview").attr("src", event.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@if(session()->has('success'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Congratulations',
        text: 'About Adwiseri Updated Successfully.'
      })
    </script>

  @endif
@error('icon')
  <script>
  Swal.fire({
    icon: 'error',
    title: 'Oops...',
    text: '{{ $message }}'
  })
</script>
@enderror
@endsection()
