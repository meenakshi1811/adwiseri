@extends('web.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
            
            <div class="client-dashboard">
                <div class="client-btn d-flex mb-2 ">
                    <form class="form-inline d-flex justify-content-between w-100">
                        <h3 class="text-primary">Update Application Assignment</h3>
                    </form>
                </div>
                <div class="col">
                    <form id="registration_form" class="register-box login-box" method="POST" action="{{ route('user_app_assignment') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="local_time" class="localtime" />
                        <input type="hidden" value="{{ $assignment->id }}" name="id" />
                        <div class="row">
                            <div class="col-md-4 p-1">
                                <label>Client ID<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input readonly name="client_id" value="{{ $client->id }}" id="client_id" required class="form-control @error('client_id') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp">
                                   
                                @error('client_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Application ID<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <select name="application_id" id="application_id" class="form-control @error('application_id') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                    @foreach($applications as $app)
                                    <option {{ ($app->application_id == $assignment->application_id) ? 'selected' : ''}} value="{{ $app->application_id }}">{{ $app->application_id }}</option>
                                    @endforeach
                                </select>
                                @error('application_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>User/Advisor<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <select name="user_id" id="user_id" class="form-control form-select @error('user_id') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                    @foreach($advisors as $u)
                                    <option {{ ($u->id == $assignment->user_id) ? 'selected' : ''}} value="{{ $u->id }}">{{ $u->name."(".$u->designation.")" }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
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

  </div>
  <script>
    function deleteassignmentt(id){
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = "delete_app_assignment/"+id+"";
        }
      })
    }
      function updateassignmentt(id){
        Swal.fire({
          title: 'Are you sure?',
          text: "You want to update this record!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = "app_assignment_update/"+id+"";
          }
        })
      }
  </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
  </script>
  <script>
      $(document).ready(() => {
        
        $("#new_assign").click(function(){
            $("#new_assign").css('display','none');
            $("#back").css('display','block');
            $("#new_assignment").css('display','block');
            $("#assignments").css('display','none');
        });
        $("#back").click(function(){
            $("#new_assign").css('display','block');
            $("#back").css('display','none');
            $("#new_assignment").css('display','none');
            $("#assignments").css('display','block');
        });
          $("#client_id").change(function(){
            var id = $(this).val();
            // console.log(counrty);
            $.ajax({
                url: 'get_applications',
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                },
                cache:false,
                success: function(data){
                  console.log(data);
                    $("#application_id").html(data);
                }
            });
          });
      });
  </script>
  
  @if(session()->has('deleted'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'Application Assignment Deleted Successfully!'
      })
    </script>
  
  @endif
  @if(session()->has('assignment_added'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'New Assignment Added Successfully!'
      })
    </script>
  
  @endif
  @if(session()->has('assignment_updated'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'Assignment Updated Successfully!'
      })
    </script>
  
  @endif

@endsection()
            