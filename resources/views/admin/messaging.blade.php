@extends('admin.layout.main')

@section('main-section')

<style>
  .dropdown-menu{
    height:auto;
    max-height:150px;
    overflow:auto;
  }
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />



        <div class="col-lg-10 column-client">
            <div class="client-dashboard">
                <div class="client-btn d-flex mb-2 ">
                    {{-- <form class="form-inline d-flex justify-content-between w-100"> --}}
                        {{-- <h3 class="text-primary">Messaging</h3> --}}
                        <h3 class="text-primary text-center flex-grow-1 text-center m-0">Messaging</h3>
                    {{-- </form> --}}
                </div>
                <div class="row m-0 pb-2">
                    <div class="col-4 border p-1 text-center bg-info text-white top_modules tab-anchor">
                        Messaging
                      </div>

                  <div class="col-4 border p-1 text-center top_modules tab-anchor" style="cursor: pointer;" onclick="window.location.href = '{{ route('meetings') }}';">
                    Meeting Notes (Clients)
                  </div>
                  <div class="col-4 border p-1 text-center top_modules tab-anchor" style="cursor: pointer;" onclick="window.location.href = '{{ route('communication') }}';">
                  Communications
                  </div>

                </div>

                <div class="col mt-3 p-2" style="border: 2px solid lightgrey; border-radius:7px;">
                  <h4 class="text-center pb-3">Send Message</h4>
                  <form id="message_form" class="form-control" method="POST" action="{{route('admin_communicate')}}" >
                      @csrf
                      <input type="hidden" name="local_time" class="localtime" />

                      <div class="row">
                        <div class="col-md-6 p-1 d-flex align-items-center">
                            <h6>Send To</h6>
                        </div>
                        <div class="col-md-6 p-1">
                          <select class="form-select" id="receiver" name="receiver" required>
                            <option value="">Select Recipient(s)</option>
                            {{-- <option value="All">All Subscribers & Users</option> --}}
                            <option value="Subscribers">Subscribers</option>
                            <option value="Users">Staff(Users)</option>
                          </select>
                            @error('sendto')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                            {{-- <input class="form-control" minlength="3" maxlength="200" type="text" name="message" placeholder="Type Message" required /> --}}
                        </div>
                        <div class="col-md-6 p-1 mt-3 align-items-center subscribers-row" id="next_col" style="display:none;" >
                        </div>
                        <div class="col-md-6 p-1 mt-3  dropdown_box subscribers-row"  style="display: none;">
                            {{-- <select id="subscribersId" name="sendto[]" class="form-control" multiple>
                                <option value="All Subscribers" {{ in_array('All Subscribers', old('sendto', [])) ? 'selected' : '' }}>All Subscribers
                                </option>
                                @foreach ($subscribers as $subscriber)
                                    <option value="{{ $subscriber->id }}"
                                        {{ in_array($subscriber->id, old('sendto', [])) ? 'selected' : '' }}>
                                        {{ $subscriber->name }}
                                    </option>
                                @endforeach
                            </select> --}}
                            <!-- <div class="dropdown">
                                <div class="form-control dropdown-toggle" data-bs-toggle="dropdown">
                                    All Subscribers
                                </div>
                                <div class="dropdown-menu form-control">
                                  <div class="dropdown-item" style="width: 100%;"><input type="checkbox" name="sendto[]" value="All Subscribers" /> All Subscribers</div>

                                  @if($subscribers)
                                  @foreach($subscribers as $suser)
                                  <div class="dropdown-item" style="width: 100%;"><input type="checkbox" name="sendto[]" value="{{$suser->id}}" /> {{$suser->name}}</div>
                                  @endforeach
                                  @endif
                                </div>
                              </div> -->
                              <div class="dropdown">
                                <div class="form-control dropdown-toggle" data-bs-toggle="dropdown">
                                    All Subscribers
                                </div>
                                <div class="dropdown-menu form-control">
                                    <div class="dropdown-item" style="width: 100%;">
                                        <input type="checkbox" id="selectAllSubscribers" /> All Subscribers
                                    </div>

                                    @if($subscribers)
                                    @foreach($subscribers as $suser)
                                    <div class="dropdown-item" style="width: 100%;">
                                        <input type="checkbox" class="subscriberCheckbox" name="sendto[]" value="{{ $suser->id }}" /> {{ $suser->name }}
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                            </div>


                        </div>
                        <div class="col-md-6 p-1 mt-3 align-items-center user-row" id="next_col"  style="display: none;">
                        </div>
                        <div class="col-md-6 p-1 mt-3 dropdown_box user-row" id="users" style="display: none;">
                            {{-- <select id="userId" name="sendto[]" class="form-control" multiple>
                                <option value="All Users" {{ in_array('All Users', old('sendto', [])) ? 'selected' : '' }}>All Users
                                </option>
                                @foreach ($users as $usrs)
                                    <option value="{{ $usrs->id }}"
                                        {{ in_array($usrs->id, old('sendto', [])) ? 'selected' : '' }}>
                                        {{ $usrs->name }}
                                    </option>
                                @endforeach
                            </select> --}}
                          <div class="dropdown">
                            <div class="form-control dropdown-toggle" data-bs-toggle="dropdown">
                              Select User/s
                            </div>
                            <!-- <div class="dropdown-menu form-control">
                              @if($users)
                              <div class="dropdown-item" style="width: 100%;position:relative;"><input type="checkbox" class="users" name="sendto[]" value="All Users" /> All User</div>
                              @foreach($users as $usrs)
                              <div class="dropdown-item" style="width: 100%;position:relative;"><input type="checkbox" class="users" name="sendto[]" value="{{$usrs->id}}" /> {{$usrs->name}}</div>
                              @endforeach
                              @endif
                            </div> -->
                            <div class="dropdown-menu form-control">
                                @if($users)
                                <div class="dropdown-item" style="width: 100%;position:relative;">
                                    <input type="checkbox" id="selectAllUsers" class="users" value="All Users" /> All User
                                </div>
                                @foreach($users as $usrs)
                                <div class="dropdown-item" style="width: 100%;position:relative;">
                                    <input type="checkbox" class="users userCheckbox" name="sendto[]" value="{{ $usrs->id }}" /> {{ $usrs->name }}
                                </div>
                                @endforeach
                                @endif
                            </div>

                          </div>
                        </div>
                        <div class="col-md-6 p-1 d-flex align-items-center">
                            <h6>Message</h6>
                        </div>
                        <div class="col-md-6 p-1">
                          <textarea rows="3" class="form-control" minlength="3" maxlength="250" pattern="[a-zA-Z0-9]+" required name="message" placeholder="Type Message"></textarea>
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


@endsection()
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    $('#selectAllSubscribers').on('change', function () {
        $('.subscriberCheckbox').prop('checked', this.checked);
    });
    // When 'All User' is clicked
    $('#selectAllUsers').on('change', function () {
        $('.userCheckbox').prop('checked', this.checked);
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
             $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                $('#subscribersId, #discount_type').select2('destroy').select2({});
            });

            $('#subscribersId').select2({
                placeholder: "    Select Subscriber/s",
                allowClear: true,
                width: '100%'
            });
            $('#subscribersId').on('select2:select', function(e) {
                let selectedValues = $(this).val();

                // If "All" is selected
                if (selectedValues.includes("All Subscribers")) {
                    // Deselect all other options except "All"
                    $(this).val(["All Subscribers"]).trigger('change');
                }
            });

            $('#subscribersId').on('select2:unselect', function(e) {
                // Allow other selections if "All" is unselected
                if (!$(this).val().includes("All Subscribers")) {
                    return;
                }

                // If "All" is unselected, deselect everything
                $(this).val(null).trigger('change');
            });
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                $('#userId, #discount_type').select2('destroy').select2({});
            });

            $('#userId').select2({
                placeholder: "All Users",
                allowClear: true,
                width: '100%'
            });
            $('#userId').on('select2:select', function(e) {
                let selectedValues = $(this).val();

                // If "All" is selected
                if (selectedValues.includes("All Users")) {
                    // Deselect all other options except "All"
                    $(this).val(["All Users"]).trigger('change');
                }
            });

            $('#userId').on('select2:unselect', function(e) {
                // Allow other selections if "All" is unselected
                if (!$(this).val().includes("All Users")) {
                    return;
                }

                // If "All" is unselected, deselect everything
                $(this).val(null).trigger('change');
            });


    $(document).on('change', '#receiver', function(e) {
        var receiver = $(this).val();
        console.log(receiver); // Debugging log

        if (receiver === "All") {
            $('#next_col').css('display', 'flex');
            $("#all").css('display', 'block');
            $(".subscribers-row").css('display', 'none');
            $("#user-row").css('display', 'none');
            $(".subscribers").prop('checked', false); // Uncheck all checkboxes
            $(".users").prop('checked', false);
        } else if (receiver === "Subscribers") {
            console.log('Subscribers selected'); // Debugging log
            $('#next_col').css('display', 'flex');
            $("#all").css('display', 'none');
            $(".subscribers-row").css('display', 'block');
            $("#user-row").css('display', 'none');
            $(".all").prop('checked', false);
            $(".users").prop('checked', false);
        } else if (receiver === "Users") {
            console.log('Users selected'); // Debugging log
            $('#next_col').css('display', 'flex');
            $("#all").css('display', 'none');
            $(".subscribers-row").css('display', 'none');
            $(".user-row").css('display', 'block');
            $(".all").prop('checked', false);
            $(".subscribers").prop('checked', false);
        } else {
            console.log('None selected'); // Debugging log
            $('#next_col').css('display', 'none');
            $("#all").css('display', 'none');
            $(".subscribers-row").css('display', 'none');
            $(".user-row").css('display', 'none');
            $(".all").prop('checked', false);
            $(".subscribers").prop('checked', false);
            $(".users").prop('checked', false);
        }
    });


});

    </script>
      @if (session()->has('sent'))
      <script>
          Swal.fire({
              icon: 'success',
              title: 'Success',
              text: 'Message Sent Successfully.'
          })
      </script>
    @endif
    @endpush


    {{-- @if(session()->has('deleted'))
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

  @endif --}}




