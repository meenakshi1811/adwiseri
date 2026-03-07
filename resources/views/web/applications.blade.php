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
                <div class="col-12 d-flex justify-content-between align-items-center mt-3  mb-3">
                    <h3 class="text-primary text-center flex-grow-1 text-center m-0">Applications</h3>
                    <p>
                        <a href="{{ route('export_applications') }}">Export</a>
                  @if(count($clients) > 0)
                  <a @if($application_roles->write_only == 1 or $application_roles->read_write_only == 1) href="{{ route('add_application') }}" @else href="#" @endif>Add New</a>
                  @else
                  <a @if($application_roles->write_only == 1 or $application_roles->read_write_only == 1) href="{{ route('add_client') }}" @else href="#" @endif>Add New</a>
                  @endif
                  @if($user->user_type == 'Subscriber')

                  @endif
                </p>
                </div>
              <div class="row m-0 pb-2">
                <div class="col-3 border p-1 text-center bg-info text-white">
                  Applications
                </div>
                <div class="col-3 border p-1 text-center top_modules" onclick="window.location.href = '{{ route('client_documents') }}';">
                  Documents
                </div>
                <div class="col-3 border p-1 text-center top_modules" @if($user->user_type == "Subscriber") onclick="window.location.href = '{{ route('user_applications') }}';" @endif>
                  Application Management
                </div>
                <div class="col-3 border p-1 text-center top_modules" onclick="window.location.href = '{{ route('user_application_tracking') }}';">
                  Application Tracking
                </div>
              </div>
                {{-- <div class="col-12 d-flex justify-content-between mb-2 ">
                        <h3 class="text-primary">Applications</h3>

                </div> --}}


                <div class="table-wrapper">
                    <table class="table table-hover table-bordered fl-table" id="clientTable">
                        <thead>
                        <tr >
                            <th class="p-1 text-center">Sr No.</th>
                            <th class="p-1 text-center">Client(ID)</th>
                            <th class="p-1 text-center">Application (ID)</th>
                            <th class="p-1 text-center">Visa Country</th>
                            <th class="p-1 text-center">Home Country</th>
                            <th class="p-1 text-center">Status</th>
                            <th class="p-1 text-center">Start Date</th>
                            <th class="p-1 text-center">End Date</th>
                            <th class="p-1 text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($applications as $key=>$app)
                        <tr>
                            <td class="p-1 text-center">{{ $key+1}}</td>
                            <td class="p-1 text-center">{{ $app->client ? $app->client->name .'('.$app->client_id.')' :  '' }}</td>
                            <td class="p-1 text-center">{{  $app->application_name  .'('.$app->application_id.')'}}</td>
                            <td class="p-1 text-center">{{ $app->visa_country }}</td>
                            <td class="p-1 text-center">{{ $app->application_country }}</td>
                            <td class="p-1 text-center">{{ $app->application_status }}</td>
                            <td class="p-1 text-center">{{ $app->formatted_start_date }}</td>
                            <td class="p-1 text-center">@if($app->end_date != null){{ $app->formatted_end_date }}@endif</td>
                            <td class="p-1 text-center">
                                <a style="background:transparent;border:none;" class="p-0 m-0 text-dark" @if($application_roles->read_only == 1 or $application_roles->read_write_only == 1) href="{{ route('view_application', $app->id)}}" @else href="#" @endif><i class="fa-solid fa-eye btn text-info p-1 m-0"></i></a>
                                <a style="background:transparent;border:none;" class=" p-0 m-0 text-dark" @if($application_roles->update_only == 1) href="{{ route('update_application', $app->id)}}" @else href="#" @endif><i class="fa-solid fa-edit btn text-primary p-1 m-0"></i></a>
                                <i class="fa-solid fa-trash btn p-1 text-danger" style="font-size:14px;" @if($application_roles->delete_only == 1) onclick="deleteapplication({{ $app->id }})" @endif></i>
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
            </div>
        </div>
    </div>

  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
  </script>
  <script>
    function deleteapplication(id){
        var conf = confirm('Delete Application');
        if(conf == true){
            window.location.href = "delete_application/"+id+"";
        }
    }
  </script>
  <script>
      $(document).ready(() => {

        $("#add_new_zero").click(function(){
            Swal.fire({
            icon: 'info',
            title: 'Oops...',
            text: 'There is no applications created.'
            });
        });
      })
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
