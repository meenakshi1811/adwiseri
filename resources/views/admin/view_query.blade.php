@extends('admin.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
            @if(isset($query))
            <div class="client-dashboard">
                <div class="client-btn d-flex mb-2 ">
                    <form class="form-inline d-flex justify-content-between w-100">
                        <h3 class="text-primary">Ticket ID : {{ $query->ticket_no }}</h3>
                        <a class="text-white" style="height:fit-content;cursor:pointer;" onclick="history.back();">Back</a>
                    </form>
                </div>
                <div class="col">
                    <div class="row px-3">
                        <div class="col-md-4 p-2 border">
                            <label style="font-weight:550;">Subscriber ID</label>
                        </div>
                        <div class="col-md-8 p-2 border">
                            {{ $query->subscriber_id }}
                        </div>
                        <div class="col-md-4 p-2 border">
                            <label style="font-weight:550;">Client ID</label>
                        </div>
                        <div class="col-md-8 p-2 border">
                            {{ $query->client_id }}
                        </div>
                        <div class="col-md-4 p-2 border">
                            <label style="font-weight:550;">Query/Issue</label>
                        </div>
                        <div class="col-md-8 p-2 border">
                            {{ $query->issue }}
                        </div>
                        <div class="col-md-4 p-2 border">
                            <label style="font-weight:550;">Response</label>
                        </div>
                        <div class="col-md-8 p-2 border">
                            {{ $query->response }}
                        </div>
                        <div class="col-md-4 p-2 border">
                            <label style="font-weight:550;">Status</label>
                        </div>
                        <div class="col-md-8 p-2 border">
                            {{ $query->status }}
                        </div>
                        @if($query->attachment != null and file_exists("web_assets/users/ticket_images/".$query->attachment))
                        <div class="col-md-4 p-2 border">
                            <label style="font-weight:550;">Attachment</label>
                        </div>
                        <div class="col-md-8 p-2 border">
                            <img src='{{ asset("web_assets/users/ticket_images/".$query->attachment) }}' style="max-width:250px;max-height:200px;width:auto;height:auto;" />
                        </div>
                        @endif
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
            