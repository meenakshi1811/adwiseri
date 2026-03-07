@extends('admin.layout.main')

@section('main-section')

<style>
  .dropdown-menu{
    height:auto;
    max-height:150px;
    overflow:auto;
  }
</style>

        <div class="col-lg-10 column-client">
            <div class="client-dashboard">
                <div class="client-btn d-flex mb-2 ">
                    {{-- <form class="form-inline d-flex justify-content-between w-100"> --}}
                        {{-- <h3 class="text-primary">Communication</h3> --}}
                        <h3 class="text-primary text-center flex-grow-1 text-center m-0">Communication</h3>
                    {{-- </form> --}}
                </div>
                <div class="row m-0 pb-2">
                    <div class="col-4 border p-1 text-center top_modules tab-anchor" style="cursor: pointer;" onclick="window.location.href = '{{ route('admin_messaging') }}';">
                        Messaging
                      </div>
                  <div class="col-4 border p-1 text-center top_modules tab-anchor" style="cursor: pointer;" onclick="window.location.href = '{{ route('meetings') }}';">
                    Meeting Notes (Clients)
                  </div>

                  <div class="col-4 border p-1 text-center bg-info text-white top_modules tab-anchor">
                    Communication
                  </div>
                </div>

                <div class="table-wrapper m-0">
                    <table class="fl-table table table-hover p-0 m-0" id="clientTable" style="table-layout: fixed; width: 100%;">
                        <thead>
                            <tr>
                                <th class="p-1 text-center" style="width: 10%;">Sub_ID</th>
                                <th class="p-1 text-center" class="squeeze-column" style="width: 10%;">Comm. ID</th>
                                <th class="p-1 text-center" style="width: 15%;">Sent By</th>
                                <th class="p-1 text-center" style="width: 15%;">Sent To</th>
                                <th class="p-1 text-center" style="width: 35%;">Message</th> <!-- Increased width -->
                                <th class="p-1 text-center" style="width: 10%;">Date</th>
                                <th class="p-1 text-center" style="width: 5%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($messages as $sn => $msg)
                            <tr>
                                @php
                                    $receiver = "";
                                    $receivernames = json_decode($msg->receiver_name, true);
                                    foreach ($receivernames as $k => $name) {
                                        $receiver .= ($k == count($receivernames) - 1) ? $name : $name . ", ";
                                    }
                                @endphp
                                <td  class="p-1 text-center">{{ $msg->subscriber_id }}</td>
                                <td  class="p-1 text-center squeeze-column">{{ $msg->communication_id }}</td>
                                <td  class="p-1 text-center">{{ $msg->sender_name }} ({{ $msg->user_id }})</td>
                                <td class="p-1 text-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $receiver }}">
    @if(strlen($receiver) > 20)
        {{ substr($receiver, 0, 20) }}...
    @else
        {{ $receiver }}
    @endif
</td>

                                <td class="p-1 text-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $msg->message }}">
    @if(strlen($msg->message) > 50)
        {{ substr($msg->message, 0, 50) }}...
    @else
        {{ $msg->message }}
    @endif
</td>

                                <td   class="p-1 text-center squeeze-column">{{ \Carbon\Carbon::parse($msg->created_at)->format('d-m-Y') }}</td>
                                <td   class="p-1 text-center action-icon squeeze-column">
                                    <a href="{{ route('view_communication', $msg->id) }}" style="text-decoration:none;">
                                        <i class="fa-solid fa-eye btn p-1 text-info" style="font-size:14px;"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                {{-- <div class="table-btn">
                    <button>Previous</button>
                    <button>1</button>
                    <button>Next</button>
                </div> --}}
            </div>
        </div>
    </div>

  </div>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});


      function deleteclient(id){
          var conf = confirm('Delete Client');
          if(conf == true){
              window.location.href = "delete_clients/"+id+"";
          }
      }
  </script>
  {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> --}}
  <script>
      $(document).ready(function() {

          $(document).on('change', '#receiver', function(e){
              var receiver = $(this).val();
              if(receiver == "All"){
                $('#next_col').css('display','flex');
                $("#all").css('display','block');
                $("#subscribers").css('display','none');
                $("#users").css('display','none');
                $(".subscribers").removeAttr('checked');
                $(".users").removeAttr('checked');
              }
              else if(receiver == "Subscribers"){
                $('#next_col').css('display','flex');
                $("#all").css('display','none');
                $("#subscribers").css('display','block');
                $("#users").css('display','none');
                $(".all").removeAttr('checked');
                $(".users").removeAttr('checked');
              }
              else if(receiver == "Users"){
                $('#next_col').css('display','flex');
                $("#all").css('display','none');
                $("#subscribers").css('display','none');
                $("#users").css('display','block');
                $(".all").removeAttr('checked');
                $(".subscribers").removeAttr('checked');
              }
              else{
                $('#next_col').css('display','none');
                $("#all").css('display','none');
                $("#subscribers").css('display','none');
                $("#users").css('display','none');
                $(".all").removeAttr('checked');
                $(".subscribers").removeAttr('checked');
                $(".users").removeAttr('checked');
              }

          });
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
  @if(session()->has('sent'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'Message Sent Successfully!'
      })
    </script>

  @endif

@endsection()
