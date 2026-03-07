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
            <div class="client-btn d-flex justify-content-between align-items-center mb-3 ">
                <h3 class="text-primary text-center flex-grow-1 text-center m-0">Meeting Notes (Clients)</h3>

            {{-- <div class="col mb-3 d-flex justify-content-between">
                <h3 class="text-primary px-2">Meeting Notes (Clients)</h3> --}}
                @if(count($clients) > 0)
                <button type="button" @if($communication_roles->write_only == 1 or $communication_roles->read_write_only == 1) id="add_new" @endif class="btn btn-info text-white mb-3">Add New</button>
                @else
                <button type="button" @if($communication_roles->write_only == 1 or $communication_roles->read_write_only == 1) id="add_new_zero" @endif class="btn btn-info text-white mb-3">Add New</button>
                @endif
                <button type="button" id="back" class="btn btn-info text-white" style="display: none;">Back</button>
            </div>
            <div class="row m-0 pb-2">


                <div class="col-4 border p-1 text-center top_modules" onclick="window.location.href = '{{ route('messaging') }}';">
                  Messaging
                </div>


                  <div class="col-4 border p-1 text-center bg-info text-white top_modules">
                    Meeting Notes (Clients)
                  </div>
                  <div class="col-4 border p-1 text-center top_modules" onclick="window.location.href = '{{ route('communications') }}';">
                    Communication
                  </div>
              </div>

            <div style="display: none;" id="new_discussion" class="col">
                <form id="registration_form" class="register-box login-box" method="POST" action="{{ route('post_client_discussion') }}">
                    @csrf
                    <input type="hidden" name="local_time" class="localtime" />
                    <div class="row">
                        <div class="col-md-4 p-1">
                            <label>Client<span class="text-danger" style="font-size: 18px;">*</span></label>
                        </div>
                        <div class="col-md-8 p-1">
                            <select name="client" id="client" class="form-control form-select @error('client') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                <option value="">Select Client</option>
                                @foreach($clients as $client)
                                <option {{ (old('client') == $client->id) ? 'selected' : '' }} value="{{ $client->id }}">{{ $client->name }}({{$client->id}})</option>
                                @endforeach
                            </select>
                            @error('client')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4 p-1">
                            <label>Application<span class="text-danger" style="font-size: 18px;">*</span></label>
                        </div>
                        <div class="col-md-8 p-1">
                            <select name="application" id="application" class="form-control form-select @error('client') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                <option value="">Select Application</option>
                            </select>
                            @error('application')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4 p-1">
                            <label>Communication Type<span class="text-danger" style="font-size: 18px;">*</span></label>
                        </div>
                        <div class="col-md-8 p-1">
                            <select name="communication_type" class="form-control form-select @error('client') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                <option value="">Select Communication Type</option>
                                <option value="Call">Call</option>
                                <option value="Email">Email</option>
                                <option value="E-meet">E-meet</option>
                                <option value="Office Visit">Office Visit</option>
                            </select>
                            @error('communication_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4 p-1">
                            <label>Communication Date<span class="text-danger" style="font-size: 18px;">*</span></label>
                        </div>
                        <div class="col-md-8 p-1">
                            <input type="datetime-local" id="comm_date" max="" onfocus="set_max()" name="communication_date" class="form-control date" required />
                            @error('communication_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4 p-1">
                            <label>Details<span class="text-danger" style="font-size: 18px;">*</span></label>
                        </div>
                        <div class="col-md-8 p-1">
                            <textarea name="discussion" class="form-control" rows="3" minlength="5" maxlength="25000" placeholder="Details" required>{{old('discussion')}}</textarea>
                            @error('discussion')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4 p-1">
                         </div>
                        <div class="col-md-8 text-left p-1">
                            <button type="submit" class="form-control btn btn-primary" style="width: fit-content;">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="discussions" class="col">
                <div class="table-wrapper">
                    <table class="table table-hover table-bordered fl-table" id="clientTable">
                        <thead>
                        <tr>
                            <th class="text-center">Sr. No</th>
                            <th class="text-center">User Name</th>
                            <th class="text-center">Client Name</th>
                            <th class="text-center">Application ID</th>
                            <th class="text-center">Mode</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Discussion</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($discussions as $key => $discus)
                        <tr>
                            <td class="text-center">{{ $key+1 }}</td>
                            <td class="text-center">{{ $discus->user_name }}</td>
                            <td class="text-center">{{ $discus->client_name }}</td>
                            <td class="text-center">{{ $discus->application_id }}</td>
                            <td class="text-center">{{ $discus->communication_type }}</td>
                            <td class="text-center">{{ date("d-m-Y H:i:s",strtotime($discus->communication_date)) }}</td>
                            <td class="text-center"><div style="max-height: 100px;overflow:auto;">{{ $discus->discussion }}</div></td>
                            {{-- <td class="text-center">
                                <a style="background:none; border:none;" onclick="window.location.href = '{{ route('view_query', $discus->id) }}';" class="m-0 p-0"><i class="fa-solid fa-eye btn p-1 text-info" style="font-size:14px;"></i></a>
                                <i class="fa-solid fa-edit btn p-1 text-success" onclick="queryresponse({{ $discus->id }})" style="font-size:14px;"></i>
                                <i class="fa-solid fa-trash btn p-1 text-danger" onclick="deletequery({{ $discus->id }})" style="font-size:14px;"></i>
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
    function set_max(){
        var d = new Date();
        var y = d.getFullYear();
        var m = d.getMonth() + 1;
        if(m < 10){
            m = "0"+m;
        }
        var dd = d.getDate();
        if(dd < 10){
            dd = "0"+dd;
        }
        var hh = d.getHours();
        if(hh < 10){
            hh = "0"+hh;
        }
        var mm = d.getMinutes();
        if(mm < 10){
            mm = "0"+mm;
        }
        var ss = d.getSeconds();
        if(ss < 10){
            ss = "0"+ss;
        }
        var maxdate = ""+y+"-"+m+"-"+dd+"T"+hh+":"+mm+":"+ss+"";
        $("#comm_date").attr('max',maxdate);
    }
    document.addEventListener("DOMContentLoaded", function() {
    let now = new Date();
    
    // Get local date and time in YYYY-MM-DDTHH:MM format
    let year = now.getFullYear();
    let month = String(now.getMonth() + 1).padStart(2, '0'); // Ensure 2-digit format
    let day = String(now.getDate()).padStart(2, '0');
    let hours = String(now.getHours()).padStart(2, '0');
    let minutes = String(now.getMinutes()).padStart(2, '0');

    let localDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;

    document.getElementById("comm_date").value = localDateTime;
});
      $(document).ready(() => {

        $("#add_new_zero").click(function(){
            Swal.fire({
            icon: 'info',
            title: 'Oops...',
            text: "Either no clients are added yet or you haven't been assigned any application(s)."
            });
        });

        $("#add_new").click(function(){
            $("#add_new").css('display','none');
            $("#back").css('display','block');
            $("#new_discussion").css('display','block');
            $("#discussions").css('display','none');
        });
        $("#back").click(function(){
            $("#add_new").css('display','block');
            $("#back").css('display','none');
            $("#new_discussion").css('display','none');
            $("#discussions").css('display','block');
        });

          $("#client").change(function(){
            var id = $(this).val();
            var comm = "communication";
            // console.log(counrty);
            $.ajax({
                url: 'get_application',
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                    comm: comm,
                },
                cache:false,
                success: function(data){
                  console.log(data);
                    $("#application").html(data);
                }
            });
          });
      });
  </script>
@if(session()->has('success'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Congratulations',
      text: 'User Added Successfully.'
    })
  </script>

@endif

@endsection()
