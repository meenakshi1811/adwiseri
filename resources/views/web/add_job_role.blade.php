@extends('web.layout.main')

@section('main-section')
  
        <div class="col-lg-10 column-client">
            <h3 class="text-primary px-2">Add Job Role</h3>
            <div class="col">
                <form id="registration_form" class="register-box login-box" method="POST" action="{{ route('add_new_job_role') }}">
                    @csrf
                    <input type="hidden" name="local_time" class="localtime" />
                    <div class="row">
                        <div class="col-md-4 p-1">
                            <label>Job Role</label>
                        </div>
                        <div class="col-md-8 p-1">
                            <input name="job_role" minlength="3" maxlength="100" required type="text" class="form-control @error('job_role') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('job_role') }}" placeholder="Job Role" autocomplete="job_role">
                            @error('job_role')
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
      text: 'User Added Successfully.'
    })
  </script>

@endif

@endsection()
