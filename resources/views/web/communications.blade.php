@extends('web.layout.main')

@section('main-section')
@php

use App\Models\UserRoles;
$client_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Clients')->first();
$application_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Applications')->first();
$communication_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Communication')->first();
$invoice_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Invoices')->first();
$payment_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Payments')->first();
$report_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Reports')->first();
$subscription_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Subscription')->first();
$setting_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Settings')->first();
$support_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Support')->first();
@endphp

        <div class="col-lg-10 column-client">
            <div class="col-12 d-flex justify-content-between align-items-center  mb-3">

                    <h3 class="text-primary text-center flex-grow-1 text-center m-0">Communications</h3>
              </div>
            <div class="client-dashboard">
              <div class="row m-0 pb-2">
                <div class="col-4 border p-1 text-center top_modules" onclick="window.location.href = '{{ route('messaging') }}';">
                    Messaging
                  </div>

                <div class="col-4 border p-1 text-center top_modules" onclick="window.location.href = '{{ route('client_discussion') }}';">
                  Meeting Notes (Clients)
                </div>
                <div class="col-4 border p-1 text-center bg-info text-white top_modules">
                    Communication
                  </div>

              </div>

              <div class="table-wrapper m-0 communication">
                <table class="fl-table table table-hover p-0 m-0" id="clientTable">
                    <thead>
                    <tr>
                        <th class="p-1 text-center">Sr No.</th>
                        <th class="p-1 text-center">Comm. ID</th>
                        <th class="p-1 text-center">Sent By</th>
                        <th class="p-1 text-center">Sent To</th>
                        <th class="p-1 text-center">Message</th>
                        <th class="p-1 text-center">Date</th>
                        <th class="p-1 text-center">Action</th>


                    </tr>
                    </thead>
                        <tbody>
                            @if($messages != null)
                                @foreach($messages as $sn => $msg)
                                <tr>
                                    @php
                                    if($msg->send_by == 1){
                                        $receiver = $user->name;
                                    }
                                    else{
                                        $receiver = "";
                                        $receivernames = json_decode($msg->receiver_name, true);
                                        foreach($receivernames as $k => $name){
                                            if($k == count($receivernames)-1){
                                                $receiver = $receiver.$name;
                                            }
                                            else{
                                                $receiver = $receiver.$name.", ";
                                            }
                                        }
                                    }
                                    @endphp
                                    <!-- Adjust column widths -->
                                    <td class="p-1 text-center" style="width: 5%;">{{ $sn+1 }}</td>
                                    <td class="p-1 text-center" style="width: 10%;">{{ $msg->communication_id }}</td>
                                    <td class="p-1 text-center" style="width: 15%;">{{ $msg->sender_name }} ({{ $msg->send_by }})</td>
                                    <td  data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $receiver }}"  class="p-1 text-center" style="width: 15%;">@if(strlen($receiver) > 22){{ substr($receiver, 0, 22) }}... @else {{ $receiver }} @endif</td>
                                    <!-- Increase message column width -->
                                    <td  data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $msg->message }}"  class="p-1 text-center" style="width: 40%;">@if(strlen($msg->message) > 50){{ substr($msg->message, 0, 50) }}... @else {{ $msg->message }} @endif</td>
                                    <td class="p-1 text-center" style="width: 10%;">{{ \Carbon\Carbon::parse($msg->created_at)->format('d-m-Y') }}</td>
                                    <td class="p-1 text-center action-icon">
                                        <a @if($communication_roles->read_only == 1 or $communication_roles->read_write_only == 1) href="{{ route('view_message', $msg->id) }}" @else href="#" @endif style="text-decoration:none;">
                                            <i class="fa-solid fa-eye btn p-1 text-info" style="font-size:14px;"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>


                </table>
              </div>
            </div>
        </div>
    </div>

  </div>
  {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
  function togglepage(page){
    var vpage = page;
    if(vpage == "communication"){
      $(".communication").css('display','block');
      $(".messaging").css('display','none');
    }
    else{
      $(".communication").css('display','none');
      $(".messaging").css('display','block');
    }
  }

</script>
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
@if(session()->has('sent'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: 'Message Sent Successfully!'
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
