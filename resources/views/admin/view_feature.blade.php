@extends('admin.layout.main')

@section('main-section')

        <div class="col-lg-10 userdash-client column-client">
            <h3 class="text-primary">Feature - {{ $feature->name }}</h3>
            <div class="profile-detail">
                <div class="col-lg-12 profile-data" style="border-color: lightgrey;">
                    <div class="row">
                        <div class="col editss text-end px-3">
                            <img class="mt-2 me-2" style="cursor: pointer;" onclick="document.getElementById('update_box').style.display='flex';" src="{{ asset('web_assets/images/edit.png') }}"width="20" height="20" alt="">
                        </div>
                    </div>
                    <div class="row det-row">
                        <div class="col">
                            <div class="row">
                                <div class="col-6">
                                    <p style="font-weight:550;">Feature Icon</p>
                                </div>
                                <div class="col-6">
                                    <div class="col p-2" style="height: 100px; width:100px;border:1px solid lightgrey;">
                                        <img src="{{ asset('admin_assets/features/icon/'.$feature->icon) }}" style="width: 100%;height:100%;" />
                                    </div>
                                </div>
                            <hr class="my-3">
                                <div class="col-6">
                                    <p style="font-weight:550;">Feature Heading</p>
                                </div>
                                <div class="col-6">
                                    <p>{{ $feature->name }}</p>
                                </div>
                            <hr class="my-3">
                                <div class="col-6">
                                    <p style="font-weight:550;">Feature Description</p>
                                </div>
                                <div class="col-6">
                                    <p>{{ $feature->content }}</p>
                                </div>
                            </div>
                        </div>
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
                <form class="details-box login-box" method="POST" action="{{ route('post_feature') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $feature->id }}">
                    <h3 class="mb-5 pt-3 text-center">Update Featrue</h3>
                    <div class="log-img mb-5">
                    @if($user->profile_img == "")
                    <img src="{{ asset('web_assets/images/loginimg.png') }}" width="60" height="60" alt="">
                    @else
                        <img src="{{ asset('web_assets/users/user'.$user->id.'/'.$user->profile_img) }}" width="60" height="60" alt="">
                    @endif
                    </div>
                    <div class="mb-4">
                        <div class="col p-2 d-flex justify-content-center align-items-center" style="position: relative;border:1px solid lightgrey;border-radius:7px;">
                            <input id="feature_icon" name="icon" style="position: absolute;width:100%;height:100%;top:0px;left:0px;opacity:0;" type="file" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Plan Name">           
                            <img id="icon_preview" src="{{ asset('admin_assets/features/icon/'.$feature->icon) }}" style="width: 100px;height:100px;" />
                        </div>
                    </div>
                    <div class="mb-4">
                        <input name="name" minlength="3" maxlength="100" value="{{ $feature->name }}" required type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Data Limit">           
                    </div>
                    <div class="mb-4">
                        <textarea name="content" rows="5" required class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Client Limit">{{ $feature->content }}</textarea>
                    </div>
                    <button type="submit" class="form-control btn btn-primary mb-4">Save</button>
                    <!-- <a href="dashboard.html" class="btn btn-primary mb-4">Next</a> -->
                    <!-- <p class="text-center reg-logbtn">Already have an account! <a href="{{ route('user_login') }}" class="text-dark"> <strong>Login</strong></a></p> -->
                </form>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
        $(document).ready(() => {
            $("#feature_icon").change(function() {
                const file = this.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        $("#icon_preview").attr("src", event.target.result);
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
        text: 'Feature Updated Successfully.'
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
            