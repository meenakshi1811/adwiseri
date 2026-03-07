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
            <div class="client-dashboard">
                <div class="client-btn d-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-primary text-center flex-grow-1 text-center m-0">Messaging</h3>

                  </div>
              <div class="row m-0 pb-2">
                <div class="col-4 border p-1 text-center bg-info text-white top_modules">
                    Messaging
                  </div>

                <div class="col-4 border p-1 text-center top_modules" onclick="window.location.href = '{{ route('client_discussion') }}';">
                  Meeting Notes (Clients)
                </div>
                <div class="col-4 border p-1 text-center top_modules" onclick="window.location.href = '{{ route('communications') }}';">
                Communications
                  </div>

              </div>

              <div class="col mt-3 p-2 messaging" style="border: 2px solid lightgrey; border-radius:7px;">
                <h4 class="text-center pb-3">Send Message</h4>
                <form id="message_form" class="form-control" method="POST" action="{{route('communicate')}}" style="border:none;">
                    @csrf
                    <input type="hidden" name="local_time" class="localtime" />
                    {{-- <input type="hidden" name="client_id" value="{{ $client->id }}" />
                    <input type="hidden" name="admin_id" value="{{ $user->id }}" /> --}}
                    <div class="row">
                      <div class="col-md-6 p-1 d-flex align-items-center">
                          <h6>Send To</h6>
                      </div>
                      <!-- <div class="col-md-6 p-1">
                        <div class="dropdown">
                          <div class="form-control dropdown-toggle" data-bs-toggle="dropdown">
                            Select Recipient(s)
                          </div>
                          <div class="dropdown-menu form-control">
                          @if($user->user_type == "Subscriber")
                            <div class="dropdown-item" style="width: 100%;"><input type="checkbox" name="sendto[]" value="admin" /> Admin</div>
                            <div class="dropdown-item" style="width: 100%;"><input type="checkbox" name="sendto[]" value="all user" /> All User(Staff)</div>
                            @else
                            <div class="dropdown-item" style="width: 100%;"><input type="checkbox" name="sendto[]" value="{{$user->added_by}}" /> Subscriber</div>
                            @endif
                            @if($siteusers)
                            @foreach($siteusers as $suser)
                            <div class="dropdown-item" style="width: 100%;"><input type="checkbox" name="sendto[]" value="{{$suser->id}}" /> {{$suser->name}}</div>
                            @endforeach
                            @endif
                          </div>
                        </div>
                        @error('sendto')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                      </div> -->
                      <div class="col-md-6 p-1">
                        <div class="dropdown">
                            <div class="form-control dropdown-toggle" data-bs-toggle="dropdown">
                                Select Recipient(s)
                            </div>
                            <div class="dropdown-menu form-control">
                                <!-- Select All Option -->
                                <div class="dropdown-item" style="width: 100%;">
                                    <input type="checkbox" id="selectAllRecipients" /> <strong>Select All</strong>
                                </div>
                                
                                @if($user->user_type == "Subscriber")
                                <div class="dropdown-item" style="width: 100%;">
                                    <input type="checkbox" class="recipient-checkbox" name="sendto[]" value="admin" /> Admin
                                </div>
                                <div class="dropdown-item" style="width: 100%;">
                                    <input type="checkbox" class="recipient-checkbox" name="sendto[]" value="all user" /> All User(Staff)
                                </div>
                                @else
                                <div class="dropdown-item" style="width: 100%;">
                                    <input type="checkbox" class="recipient-checkbox" name="sendto[]" value="{{$user->added_by}}" /> Subscriber
                                </div>
                                @endif
                                
                                @if($siteusers)
                                @foreach($siteusers as $suser)
                                <div class="dropdown-item" style="width: 100%;">
                                    <input type="checkbox" class="recipient-checkbox" name="sendto[]" value="{{$suser->id}}" /> {{$suser->name}}
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                        @error('sendto')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <!-- JavaScript to Handle "Select All" Functionality -->
                    <script>
                        document.getElementById('selectAllRecipients').addEventListener('change', function () {
                            let checkboxes = document.querySelectorAll('.recipient-checkbox');
                            checkboxes.forEach(checkbox => {
                                checkbox.checked = this.checked;
                            });
                        });
                    </script>

                      <div class="col-md-6 p-1 d-flex align-items-center">
                          <h6>Message</h6>
                      </div>
                      <div class="col-md-6 p-1">
                        <textarea rows="3" class="form-control" minlength="3" maxlength="500" required name="message" placeholder="Type Message"></textarea>
                        @error('message')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                          {{-- <input class="form-control" minlength="3" maxlength="200" type="text" name="message" placeholder="Type Message" required /> --}}
                      </div>
                      <div class="col-md-6 p-1">
                      </div>
                        <div class="col-md-6 p-1">
                            <input class="btn btn-primary" type="submit" value="Send" />
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

        // $(document).on('submit', '#message_form', function(e){
        //     e.preventDefault();
        //     let formdata = new FormData(this);
        //     $.ajax({
        //         url: "{{ route('communicate') }}",
        //         method: "POST",
        //         data: formdata,
        //         contentType: false,
        //         processData: false,
        //         cache: false,
        //         success: function(data){
        //             $("#messages").load(location.href + " #messages>*", "");
        //             $('#message_form').trigger('reset');
        //             console.log(data);
        //         }
        //     });
        // });
        // setInterval(() => {
        //     $("#messages").load(location.href + " #messages>*", "");
        // }, 1000);
    });
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
