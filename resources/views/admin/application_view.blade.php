@extends('admin.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
            @if(isset($application))
            <div class="client-dashboard">
                <div class="client-btn d-flex mb-2 ">
                    <form class="form-inline d-flex justify-content-between w-100">
                        <h3 class="text-primary">{{ $application->application_name }}</h3>
                    </form>
                </div>
                <div class="col">
                    <div class="row px-3">
                        <div class="col-md-4 p-2 border">
                            <label style="font-weight:550;">Client ID</label>
                        </div>
                        <div class="col-md-8 p-2 border">
                            {{ $application->client_id }}
                        </div>
                        <div class="col-md-4 p-2 border">
                            <label style="font-weight:550;">Application ID</label>
                        </div>
                        <div class="col-md-8 p-2 border">
                            {{ $application->application_id }}
                        </div>
                        <div class="col-md-4 p-2 border">
                            <label style="font-weight:550;">Application</label>
                        </div>
                        <div class="col-md-8 p-2 border">
                            {{ $application->application_name }}
                        </div>
                        <div class="col-md-4 p-2 border">
                            <label style="font-weight:550;">Application Country</label>
                        </div>
                        <div class="col-md-8 p-2 border">
                            {{ $application->application_country }}
                        </div>
                        <div class="col-md-4 p-2 border">
                            <label style="font-weight:550;">Application Detail</label>
                        </div>
                        <div class="col-md-8 p-2 border">
                            {{ $application->application_detail }}
                        </div>
                        <div class="col-md-4 p-2 border">
                            <label style="font-weight:550;">Application Start Date</label>
                        </div>
                        <div class="col-md-8 p-2 border">
                            {{ date("d-m-Y", strtotime($application->start_date)) }}
                        </div>
                        <div class="col-md-4 p-2 border">
                            <label style="font-weight:550;">Application Status</label>
                        </div>
                        <div class="col-md-8 p-2 border">
                            {{ $application->application_status }}
                        </div>
                        <div class="col-md-4 p-2 border">
                            <label style="font-weight:550;">Application End Date</label>
                        </div>
                        <div class="col-md-8 p-2 border">
                            @if($application->end_date != null)
                            {{ date("d-m-Y", strtotime($application->end_date)) }}
                            @endif
                        </div>
                    </div>
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
          $("#client").change(function(){
            var id = $(this).val();
            // console.log(counrty);
            $.ajax({
                url: 'get_job_role',
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                },
                cache:false,
                success: function(data){
                  console.log(data);
                    $("#job_role").html(data);
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
            