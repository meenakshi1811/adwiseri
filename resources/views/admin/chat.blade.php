@extends('admin.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
            <div class="client-dashboard">
                <div class="client-btn d-flex mb-2 ">
                    <form class="form-inline d-flex justify-content-between w-100">
                        <h3 class="text-primary">{{ $client->name }} Messages</h3>
                      </form>
                </div>
                <div class="col-lg-6 p-2" style="border: 2px solid lightgrey; border-radius:7px;">
                    <div class="col" id="messages" style="min-height: 100px;max-height:500px;border: 0.5px solid lightgrey;border-radius:5px; overflow:auto;width:100%;">
                        @foreach($messages as $msg)
                        <div class="col p-2">
                            <h5 class="m-0" style="font-size:15px;font-weight:600;">@if($msg->admin_id == null) {{ $client->name }} @else admin @endif &nbsp;&nbsp;&nbsp;<span style="font-size: 12px; color:grey;">{{ date("d M, Y H:i:s", strtotime($msg->created_at)) }}</span></h5>
                            <p class="px-2 m-0" style="font-size: 14px;">{{ $msg->message }}</p>
                        </div>
                        @endforeach
                    </div>
                    <form id="message_form" class="form-control" style="border:none;">
                        @csrf
                        <input type="hidden" name="client_id" value="{{ $client->id }}" />
                        <input type="hidden" name="admin_id" value="{{ $user->id }}" />
                        <div class="row">
                            <div class="col-9 p-0">
                                <input class="form-control" type="text" name="message" placeholder="Type Message" required />
                            </div>
                            <div class="col-3 p-0">
                                <input class="form-control btn btn-primary" type="submit" value="Send" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

  </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
    
        $(document).on('submit', '#message_form', function(e){
            e.preventDefault();
            let formdata = new FormData(this);
            $.ajax({
                url: "{{ route('send_response') }}",
                method: "POST",
                data: formdata,
                contentType: false,
                processData: false,
                cache: false,
                success: function(data){
                    $("#messages").load(location.href + " #messages>*", "");
                    $('#message_form').trigger('reset');
                    console.log(data);
                }
            });
        });
        setInterval(() => {
            $("#messages").load(location.href + " #messages>*", "");
        }, 1000);
    });
</script>
  
  @if(session()->has('deleted'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'Client Deleted Successfully!'
      })
    </script>
  
  @endif

@endsection()
            