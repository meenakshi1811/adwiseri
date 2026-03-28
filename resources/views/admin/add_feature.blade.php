@extends('admin.layout.main')

@section('main-section')
<div class="col-lg-10 column-client">
    <h3 class="text-primary px-2">Add New Feature</h3>
    <div class="col">
        <form id="registration_form" class="register-box login-box" method="POST" action="{{ route('post_feature') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-4 p-1">
                    <label>Feature Icon<span class="text-danger" style="font-size: 18px;">*</span></label>
                </div>
                <div class="col-md-8 p-1">
                    <div class="col p-2" style="width:100px;height:100px;border:1px solid lightgrey;border-radius:7px;position: relative;">
                        <input id="feature_icon" style="position: absolute;opacity: 0;width:100%;height:100%;top:0px;left:0px;" name="icon" required type="file" class="form-control @error('icon') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp">
                        <img id="icon_preview" src="{{ asset('web_assets/images/clickme.jpg') }}" style="width:100%;height:100%;"; />
                    </div>
                </div>
                <div class="col-md-4 p-1">
                    <label>Feature Name<span class="text-danger" style="font-size: 18px;">*</span></label>
                </div>
                <div class="col-md-8 p-1">
                    <input name="name" minlength="3" maxlength="100" required type="text" class="form-control @error('name') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('name') }}" placeholder="Feature Name" autocomplete="name">
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-4 p-1">
                    <label>Feature Description<span class="text-danger" style="font-size: 18px;">*</span></label>
                </div>
                <div class="col-md-8 p-1">
                    <textarea name="content" rows="4" class="form-control @error('content') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required placeholder="Feature Content" autocomplete="content">{{ old('content') }}</textarea>
                    @error('content')
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
      text: 'Feature Added Successfully.'
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
            