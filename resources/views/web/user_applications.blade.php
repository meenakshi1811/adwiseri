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
            <div class="client-btn d-flex justify-content-between align-items-center mt-3 ">
                <h3 class="text-primary text-center flex-grow-1 text-center m-0">Application Management</h3>

                @if(count($applications) > 0)
                    @if(count($siteusers) > 0)
                    <button class="btn btn-info text-white" type="button" @if($application_roles->write_only == 1 or $application_roles->read_write_only == 1) id="new_assign" @endif>New Assign</button>
                    @else
                    <button class="btn btn-info text-white" type="button" @if($application_roles->write_only == 1 or $application_roles->read_write_only == 1) id="new_assign_usr" @endif>New Assign</button>
                    @endif
                @else
                <button class="btn btn-info text-white" type="button" @if($application_roles->write_only == 1 or $application_roles->read_write_only == 1) id="new_assign_zero" @endif>New Assign</button>
                @endif
                <button style="display: none;" class="btn btn-info text-white" type="button" id="back">Back</button>

        </div>
            <div class="client-dashboard">
              <div class="row m-0 p-2">
                <div class="col-3 border p-1 text-center top_modules" onclick="window.location.href = '{{ route('applications') }}';">
                  Applications
                </div>
                <div class="col-3 border p-1 text-center top_modules" onclick="window.location.href = '{{ route('client_documents') }}';">
                  Documents
                </div>
                <div class="col-3 border p-1 text-center bg-info text-white">
                  Application Management
                </div>
                <div class="col-3 border p-1 text-center top_modules" onclick="window.location.href = '{{ route('user_application_tracking') }}';">
                  Application Tracking
                </div>
              </div>

                <div style="display: none;" id="new_assignment" class="col">
                  <h5>New Application Assignment</h5>
                    <form id="registration_form" class="register-box login-box" method="POST" action="{{ route('user_app_assignment') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="local_time" class="localtime" />
                        <div class="row">
                            <div class="col-md-4 p-1">
                                <label>Client<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <select name="client_id" id="client_id" required class="form-control form-select @error('client_id') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp">
                                    <option value="">Select Client</option>
                                    @foreach($clients as $clint)
                                    <option value="{{ $clint->id }}">{{ $clint->name."(".$clint->id.")" }}</option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Application<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <select name="application_id" id="application_id" class="form-control form-select @error('application_id') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                    <option value="">Select Application</option>
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
                                    <option value="">Select User/Advisor</option>
                                    @foreach($siteusers as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}({{ $u->id }})</option>
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
                <div id="assignments" class="table-wrapper">
                    <table class="table table-hover table-bordered fl-table" id="clientTable">
                        <thead>
                        <tr>
                          <th class="text-center">Sr No.</th>
                            <th class="text-center">Client(ID)</th>
                            <th class="text-center">Application (ID)</th>
                            <th class="text-center">Assigned To</th>
                            <th class="text-center">Assigned On</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($assignments as $key=>$assign)
                        <tr>
                        <td class="text-center">{{ $key+1 }} </td>
                            <td class="text-center">{{ $assign->client ?  $assign->client->name .'('. $assign->client_id.')' : '' }}</td>
                            <td class="text-center">{{ $assign->application_id ?  $assign->application->application_name .'('. $assign->application_id.')' : '' }}</td>
                            <td class="text-center">{{ $assign->user_name  .'('. $assign->user_id.')'}}</td>
                            <td class="text-center">{{ date("d-m-Y",strtotime($assign->created_at)) }}</td>
                            <td class="text-center">
                                {{-- <a style="background:transparent;border:none;" class="p-0 m-0 text-dark" href="{{ route('application_view', $doc->id)}}"><i class="fa-solid fa-eye btn text-info p-1 m-0"></i></a> --}}
                                <i class="fa-solid fa-edit btn text-primary p-1 m-0" style="font-size:14px;" @if($application_roles->update_only ==1) onclick="updateassignmentt({{ $assign->id }})" @endif></i>
                                <i class="fa-solid fa-trash btn p-1 text-danger" style="font-size:14px;" @if($application_roles->delete_only ==1) onclick="deleteassignmentt({{ $assign->id }})" @endif></i>
                            </td>
                            {{-- <td>
                                <a class="p-1 text-dark" href=""><i class="fa-solid fa-eye"></i></a>
                                <i class="fa-solid fa-trash btn p-1 text-danger" style="font-size:14px;" onclick="deleteapplication({{ $app->id }})"></i>
                            </td> --}}
                        </tr>
                        @endforeach

                        <tbody>
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
            window.location.href = "update_application_assignment/"+id+"";
          }
        })
      }
  </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
  </script>
  <script>
      $(document).ready(() => {

        $("#new_assign_zero").click(function(){
            Swal.fire({
            icon: 'info',
            title: 'Oops...',
            text: 'There is no application created.'
            });
        });
        $("#new_assign_usr").click(function(){
            Swal.fire({
            icon: 'info',
            title: 'Oops...',
            text: 'There is no user created.'
            });
        });

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
                url: 'get_application',
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
