@extends('admin.layout.main')

@section('main-section')
  
<div class="col-lg-10 column-client">
    @if(isset($message))
    <div class="client-dashboard">
        <div class="client-btn d-flex mb-2 ">
            <form class="form-inline d-flex justify-content-between w-100">
                <h3 class="text-primary">Message Detail</h3>
            </form>
        </div>
        <div class="col">
            <div class="row px-3">
                <div class="col-md-4 p-1">
                    <label>Communication ID</label>
                </div>
                <div class="col-md-8 p-1">
                    {{ $message->communication_id }}
                </div>
                <hr>
                <div class="col-md-4 p-1">
                    <label>Send By</label>
                </div>
                <div class="col-md-8 p-1">
                    {{ $message->sender_name }}
                </div>
                <hr>
                <div class="col-md-4 p-1">
                    <label>Send To</label>
                </div>
                @php $receiver = json_decode($message->receiver_name,true); @endphp
                @php $receiverid = json_decode($message->send_to,true); @endphp
                <div class="col-md-8 p-1" style="max-height: 300px;overflow:auto;">
                    @foreach($receiver as $i => $rec)
                        @if($i == count($receiver)-1)
                            {{ $rec }}({{$receiverid[$i]}})
                        @else
                            {{ $rec }}({{$receiverid[$i]}}), &nbsp;&nbsp;
                        @endif
                    @endforeach
                </div>
                <hr>
                <div class="col-md-4 p-1">
                    <label>Message</label>
                </div>
                <div class="col-md-8 p-1">
                    {{ $message->message }}
                </div>
                <hr>
                <div class="col-md-4 p-1">
                    <label>Date</label>
                </div>
                <div class="col-md-8 p-1">
                    {{ date("d-m-Y H:i:s", strtotime($message->created_at)) }}
                </div>
                <hr>
            </div>
        </div>
        
    </div>
    @endif
</div>
    </div>

  </div>
  <script>
    function deleteapplication(id){
        var conf = confirm('Delete Application');
        if(conf == true){
            window.location.href = "delete_application/"+id+"";
        }
    }
</script>

@if(session()->has('deleted'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: 'Application Deleted Successfully!'
    })
  </script>

@endif
@if(session()->has('application_added'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: 'New Application Added Successfully!'
    })
  </script>

@endif
@if(session()->has('application_updated'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: 'Application Updated Successfully!'
    })
  </script>

@endif
@endsection()
