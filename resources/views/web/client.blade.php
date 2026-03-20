@extends('web.layout.main')
<style>
    .error {
        border: 2px red solid !important;
    }

    input {
        width: 100% !important;
    }

    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        /* Stay in place */
        z-index: 1;
        /* Sit on top */
        padding-top: 100px;
        /* Location of the box */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgb(0, 0, 0);
        /* Fallback color */
        background-color: rgba(0, 0, 0, 0.4);
        /* Black w/ opacity */
    }

    /* Modal Content */
    .modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }

    /* The Close Button */
    .close {
        color: #aaaaaa;
        float: right !important;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }
</style>


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

                <div class="col-12 d-flex justify-content-between align-items-center mb-3">
                    <h3 class="text-primary text-center flex-grow-1 text-center m-0">Clients</h3>
                    <div class="d-flex gap-2">
                    @php
                    $encryptedId = encrypt($user->id);
                    $qrUrl = url('/create-new-lead/'.$encryptedId);
                    @endphp
                    <a href="javascript:void(0)" 
                    class="btn btn-info btn-sm" 
                    data-bs-toggle="modal" 
                    data-bs-target="#qrModal">
                    Add Enquiry
                    </a>
                      <a
                        @if($client_roles->write_only == 1 or $client_roles->read_write_only == 1)
                          href="{{ route('add_client') }}"
                        @else
                          href="#"
                        @endif
                        class="btn btn-primary btn-sm"
                      >
                        Add Client
                      </a>
                      @if(count($clients) != 0)
                      @if($user->user_type == "Subscriber")
                        {{-- <a href="{{ route('export_clients') }}" class="btn btn-secondary btn-sm">Export</a> --}}
                        <a href="javascript:void(0)" id="AddApplication" class="btn btn-primary">Add Application</a>
                        <a href="javascript:void(0)" id="AddDependent" class="m-0">Add Dependant</a>
                      @endif
                    @endif
                    </div>
                  </div>
                  <div class="client-btn d-flex justify-content-between mb-4">
                    <div class="modal fade" id="qrModal" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">Scan QR Code</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body text-center">

                                <p>Scan this QR to create a new lead</p>

                               <img 
                                src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($qrUrl) }}"
                                alt="QR Code"
                                />

                                <!-- <p class="mt-2 small text-muted">
                                {{ $qrUrl }}
                                </p> -->

                            </div>

                            </div>
                        </div>
                        </div>
                    <div id="myModal" class="modal" tabindex="-1">
                        <div class="modal-dialog modal-md">
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h5 class="modal-title text-primary">Add Application</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <!-- Modal Body -->
                                <div class="modal-body">
                                    <form id="add-client-application">
                                        @csrf

                                        <!-- Subscriber Selection -->

                                        <!-- Client Selection -->
                                        <div class="mb-3">
                                            <label for="clients" class="form-label">Clients <span class="text-danger" style="font-size: 18px;">*</span></label>
                                            <select name="client_id" id="add-clients-app" required
                                                class="form-control form-select @error('client_id') is-invalid @enderror" >
                                                <option value="">Select Client</option>
                                                @foreach ($clients as $client )
                                                <option value="{{$client->id}}" data-subscriberId="{{$client->subscriber_id}}">{{$client->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('client_id')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <!-- Application Type -->
                                        <div class="mb-3">
                                            <label for="job_role" class="form-label">Application Type <span class="text-danger" style="font-size: 18px;">*</span></label>
                                            <select name="job_role" id="job_role" required
                                                class="form-control form-select @error('job_role') is-invalid @enderror">
                                                <option value="">Select Application Type</option>
                                            </select>
                                            @error('job_role')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <!-- Visa Country -->
                                        <div class="mb-3">
                                            <label for="visa_country" class="form-label">Visa Country <span class="text-danger" style="font-size: 18px;">*</span></label>
                                            <select name="visa_country" id="visa_country" required
                                                class="form-control form-select @error('visa_country') is-invalid @enderror">
                                                <option value="">Select Visa Country</option>
                                                @foreach ($countries as $country)
                                                    <option {{ old('visa_country') == $country->country_name ? 'selected' : '' }}
                                                        value="{{ $country->country_name }}">{{ $country->country_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('visa_country')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <!-- Application Dates -->
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="app_start_date" class="form-label">Application Start Date <span class="text-danger" style="font-size: 18px;">*</span></label>
                                                    <input name="job_open_date" type="text"
                                                    class="form-control date @error('job_open_date') is-invalid @enderror"
                                                    id="job_open_date"
                                                    aria-describedby="emailHelp"
                                                    value="{{ old('payment_date') ? date('Y-m-d', strtotime(old('job_open_date'))) : null }}"
                                                    placeholder="Application Start Date"
                                                    autocomplete="job_open_date"
                                                    {{-- max={{ date('Y-m-d')}} --}}
                                                    required
                                                   onfocus="(this.type='date')"
                                                    onblur="(this.type='text')"
                                                      max="{{date('Y-m-d')}}"
                                                      {{-- max="{{ date('Y-m-d', strtotime('+2 years')) }}" --}}
                                                    />
                                                @error('job_open_date')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="app_end_date" class="form-label">Application End Date </label>
                                                {{-- <input name="job_completion_date" type="date" id="app_end_date"
                                                    class="form-control @error('job_completion_date') is-invalid @enderror"> --}}
                                                    <input name="job_completion_date" type="text"
                                                    class="form-control date @error('job_completion_date') is-invalid @enderror"
                                                    id="job_completion_date"
                                                    aria-describedby="emailHelp"
                                                    value="{{ old('job_completion_date') ? date('Y-m-d', strtotime(old('job_completion_date'))) : date('Y-m-d') }}"

                                                    placeholder="Application End Date"
                                                    autocomplete="job_completion_date"
                                                   onfocus="(this.type='date')"
                                                    onblur="(this.type='text')"
                                                      max="{{date('Y-m-d')}}"
                                                    />
                                                @error('job_completion_date')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Application Status -->
                                        <div class="mb-3">
                                            <label for="job_status" class="form-label">Application Status <span class="text-danger" style="font-size: 18px;">*</span></label>
                                            <select name="job_status" id="job_status" required
                                                class="form-control form-select @error('job_status') is-invalid @enderror">
                                                <option value="">Select Application Status</option>
                                                <option {{ (old('job_status') == "Pending") ? 'selected':'' }} value="Pending">Pending (For submission)</option>
                                                <option {{ (old('job_status') == "In Process") ? 'selected':'' }} value="In Process">In Process (Waiting for decision)</option>
                                                <option {{ (old('job_status') == "Complete") ? 'selected':'' }} value="Complete">Completed (Application/Appeal decision received)</option>
                                                <option {{ (old('job_status') == "Cancelled") ? 'selected':'' }} value="Cancelled">Cancelled (Application/Appeal Cancelled by Consultancy/Authorities)</option>
                                                <option {{ (old('job_status') == "Withdrawn") ? 'selected' : '' }} value="Withdrawn">Withdrawn (Application/Appeal Withdrawn by Client)</option>
                                            </select>
                                            @error('job_status')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label>Remarks</label>
                                            <textarea rows="3" maxlength="255" name="job_detail"
                                                class="form-control @error('job_detail') is-invalid @enderror" id="exampleInputEmail1"
                                                aria-describedby="emailHelp" value="{{ old('job_detail') }}" placeholder="Additional Information"></textarea>
                                            @error('job_detail')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-success">Add Application</button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Modal Footer -->
                                <div class="modal-footer">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="myDependent" class="modal" tabindex="-1">
                        <div class="modal-dialog modal-md">
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h5 class="modal-title text-primary">Add Dependant</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <!-- Modal Body -->
                                <div class="modal-body">
                                    <form id="add-client-dependent">
                                        @csrf

                                        <!-- Subscriber Selection -->

                                        <!-- Client Selection -->
                                        <div class="mb-3">
                                            <label for="clients" class="form-label">Client <span class="text-danger" style="font-size: 18px;">*</span></label>
                                            <select name="client_id" id="add-clients-dependent" required
                                                class="form-control form-select @error('client_id') is-invalid @enderror">
                                                <option value="">Select Client</option>
                                                @foreach ($clients as $client )
                                                <option value="{{$client->id}}">{{$client->name.'--('.$client->id.')' }}</option>
                                                @endforeach
                                            </select>
                                            @error('client_id')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <!-- Application Type -->
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name <span class="text-danger" style="font-size: 18px;">*</span></label>
                                            <input name="name" type="text" id="app_end_date" placeholder="Dependant Name"
                                                    class="form-control @error('name') is-invalid @enderror">
                                            @error('name')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <!-- Visa Country -->
                                        <div class="mb-3">
                                            <label for="gender" class="form-label"> Gender <span class="text-danger" style="font-size: 18px;">*</span></label>
                                            <select name="gender" id="gender" required
                                                class="form-control form-select @error('gender') is-invalid @enderror">
                                                <option value="">Select  Gender</option>
                                                    <option {{ old('gender') == 'Male' ? 'selected' : '' }}value="Male">Male</option>
                                                    <option {{ old('gender') == 'Female' ? 'selected' : '' }}value="Female">Female</option>
                                                    <option {{ old('gender') == 'Prefer Not To Say' ? 'selected' : '' }}value="Prefer Not To Say">Prefer Not to Say</option>
                                            </select>
                                            @error('gender')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="relation" class="form-label">Relation with client <span class="text-danger" style="font-size: 18px;">*</span></label>
                                            <select name="relation" id="job_status" required
                                                class="form-control form-select @error('relation') is-invalid @enderror">
                                                <option value="">Select Relation</option>
                                                <option {{ old('relation') == 'Husband' ? 'selected' : '' }} value="Husband">Husband</option>
                                                <option {{ old('relation') == 'Wife' ? 'selected' : '' }} value="Wife">Wife</option>
                                                <option {{ old('relation') == 'Son' ? 'selected' : '' }} value="Son">Son</option>
                                                <option {{ old('relation') == 'Daughter' ? 'selected' : '' }} value="Daughter">Daughter</option>
                                                <option {{ old('relation') == 'Father' ? 'selected' : '' }} value="Father">Father</option>
                                                <option {{ old('relation') == 'Mother"' ? 'selected' : '' }} value="Mother">Mother</option>
                                                <option {{ old('relation') == 'Other"' ? 'selected' : '' }} value="Other">Other</option>

                                                </select>
                                            @error('relation')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <!-- Application Dates -->
                                            <div class="mb-3">
                                                <label for="app_start_date" class="form-label">DateOfBirth <span class="text-danger" style="font-size: 18px;">*</span></label>
                                                <input name="dob" type="text"
                                                    class="form-control date @error('dob') is-invalid @enderror"
                                                    id="dob"
                                                    aria-describedby="emailHelp"
                                                    value="{{ old('dob') ? date('Y-m-d', strtotime(old('dob'))) : '' }}"
                                                    placeholder="{{ date('d-m-Y')}}"
                                                    autocomplete="dob"
                                                    max="{{ date('Y-m-d')}}"

                                                onfocus="(this.type='date')"
                                                    onblur="(this.type='text')"
                                                    />
                                                    @error('dob')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                <!-- <input name="dob" type="text"
                                                    class="form-control date @error('dob') is-invalid @enderror"
                                                    id="dob"
                                                    aria-describedby="emailHelp"
                                                    value="{{ old('dob') ? date('Y-m-d', strtotime(old('dob'))) : null }}"
                                                    placeholder="DateOfBirth"
                                                    autocomplete="dob"
                                                    max={{ date('Y-m-d')}}
                                                    required
                                                   onfocus="(this.type='date')"
                                                    onblur="(this.type='text')"
                                                    />
                                                @error('dob')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror -->

                                        </div>

                                        <!-- Application Status -->

                                        <div class="mb-3">
                                            <label>Passport No</label>
                                                <input oninput="validateInput(this)" name="passport_no" type="text" class="form-control @error('passport_no') is-invalid @enderror"
                                                    id="passport_no" value="{{ old('passport_no') }}" placeholder="Passport Number" minlength="6" maxlength="14" pattern="^[A-Z0-9]+$"
                                                    onkeyup="this.value = this.value.toUpperCase();">
                                            @error('passport_no')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-success">Add Dependant</button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Modal Footer -->
                                <div class="modal-footer">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row m-0 pb-2">
                    <div class="col-4 border p-1 text-center bg-info text-white " >
                        Clients
                    </div>
                    <div class="col-4 border p-1 text-center top_modules" onclick="window.location.href = '{{ route('subscriber_dependents') }}';">
                        Dependants
                    </div>
                    <div class="col-4 border p-1 text-center top_modules" onclick="window.location.href = '{{ route('enquiries') }}';">
                        Enquries
                    </div>

                  </div>
                @if(count($clients) != 0)
                <div class="table-wrapper">
                    <table class="fl-table table table-hover table-responsive p-0 m-0" id="clientTable">
                        <thead>
                        <tr>
                            <th class="p-1 text-center">Sr No.</th>
                            <!-- <th class="p-1 text-center">Client Name(Sub_ID)</th> -->
                            <th class="p-1 text-center">Client Name (ID)</th>
                            <th class="p-1 text-center">Phone No</th>
                            <th class="p-1 text-center">Email</th>
                            <th class="p-1 text-center">Country</th>
                            <th class="p-1 text-center">City/Town</th>
                            <th class="p-1 text-center">Postcode</th>
                            <th class="p-1 text-center">NOA </th>
                            <th class="p-1 text-center">Created </th>
                            <th class="p-1 text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($clients as $client)
                        <tr>
                            <td class="p-1">{{ $client->id }}</td>

                            <td class="p-1 text-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $client->name }}" style="position: relative;">@if(strlen($client->name) > 22){{ substr($client->name, 0, 22) }}... <span onmouseover="this.style.opacity='1';" onmouseout="this.style.opacity='0';" style="display:flex;opacity:0;align-items:center;padding:5px;position: absolute;left:0px;top:25px;height:100%;background:lightgrey;min-width:100%; width:fit-content;">{{$client->name}} ({{$client->id}})</span> @else {{$client->name}} ({{$client->id}})@endif</td>
                            <td class="p-1 text-center">{{ $client->phone }}</td>
                            <td class="p-1 text-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $client->email }}" style="position: relative;">@if(strlen($client->email) > 22){{ substr($client->email, 0, 22) }}... <span onmouseover="this.style.opacity='1';" onmouseout="this.style.opacity='0';" style="display:flex;opacity:0;align-items:center;padding:5px;position: absolute;left:0px;top:25px;height:100%;background:lightgrey;min-width:100%; width:fit-content;">{{$client->email}}</span> @else {{$client->email}} @endif</td>
                            <td class="p-1 text-center">{{ $client->country }}</td>
                            <td class="p-1 text-center">{{ $client->city }}</td>
                            <td class="p-1 text-center">{{ $client->pincode }}</td>
                            <td class="text-center"> {{ $client->applications ? ($client->applications->count() ?? 'No') : 'No User' }}</td>
                            <td class="text-center">{{  \Carbon\Carbon::parse($client->created_at)->format('d-m-Y') }}</td>
                            <td class="text-center action-icon p-1">
                                <a @if($client_roles->read_only == 1 or $client_roles->read_write_only == 1) href="{{ route('client_profile', $client->id)}}" @else href="#" @endif style="text-decoration:none;"><i class="fa-solid fa-eye btn p-1 text-info" style="font-size:12px;"></i></a>
                                <i class="fa-solid fa-trash btn p-1 text-danger" style="font-size:12px;" @if($client_roles->delete_only == 1) onclick="deleteclient({{ $client->id }})" @endif></i>


                            </td>
                        </tr>
                        @endforeach
                        <tbody>
                    </table>
                </div>
                @else
                <p class="text-secondary px-3">Clients Not added...</p>
                @endif
                {{-- <div class="table-btn">
                    <button>Previous</button>
                    <button>1</button>
                    <button>2</button>
                    <button>3</button>
                    <button>Next</button>
                </div> --}}
            </div>
        </div>
    </div>

  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  
  <script>
      document.addEventListener("DOMContentLoaded", function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
function validateInput(input) {
    input.value = input.value.replace(/[^A-Za-z0-9]/g, '');
}
</script>
<script>
     // Close Modal on "Close" button or when clicking outside
     document.getElementById('dob').addEventListener('change', function () {
                var inputField = this;
                var inputDate = new Date(inputField.value); // Get the selected date
                var today = new Date(); // Current date

                // Check if the input date is in the future
                if (inputDate > today) {
                    inputField.value = ""; // Clear the invalid value
                    inputField.placeholder = "Future dates are not allowed!"; // Show error in the placeholder
                    inputField.classList.add('is-invalid'); // Add red border for invalid input
                } else {
                    inputField.classList.remove('is-invalid'); // Remove error state
                    inputField.placeholder = "Payment Date"; // Reset placeholder
                }
            });
            
    function deleteclient(id){
      var localtime = new Date();
        var conf = confirm('Delete Client');
        if(conf == true){
            window.location.href = "delete_client/"+id+"/"+localtime.toString()+"";
        }
    }

       // Get modal, buttons, and close elements
var modal = document.getElementById("myModal");
var btn = document.getElementById("AddApplication");
var btnDependent = document.getElementById("AddDependent"); // Only if exists
var modalDependent = document.getElementById("myDependent"); // Only if exists
var closeButtons = document.querySelectorAll(".btn-close, .close");

// Open modal on button click
btn.onclick = function () {
    modal.style.display = "block";
};

// (Optional) Open affiliate modal on button click
if (btnDependent && modalDependent) {
    btnDependent.onclick = function () {
        modalDependent.style.display = "block";
        $.ajax({
                    url: 'getClient',
                    method: 'get',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: id,
                        name: name,
                    },
                    cache: false,
                    success: function(data) {
                        console.log(data);

                        if (data.clients.length > 0) {
                            var options =
                            '<option value="">Select Client</option>'; // Default option
                            data.clients.forEach(function(client) {
                                // Add each client as an option in the dropdown
                                options += '<option value="' + client.id + '">' + client
                                    .name + '</option>';
                            });
                            $("#add-clients-dependent").html(
                            options); // Update the clients dropdown with the new options
                        } else {
                            // Optionally handle the case when no clients are found
                            $("#add-clients-dependent").html(
                            '<option value="">No clients available</option>');
                        }
                    }
                });
    };
}

// Close modal on clicking close buttons
closeButtons.forEach(function (closeButton) {
    closeButton.onclick = function () {
        modal.style.display = "none";
        if (modalDependent) modalDependent.style.display = "none";
    };
});

// Close modal if clicking outside of it
window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
    if (modalDependent && event.target == modalDependent) {
        modalDependent.style.display = "none";
    }
};

        $(document).ready(function() {
            // Get the modal
            $("#job_open_date").on("change", function () {
                var startDate = $(this).val(); // Get the selected start date
                var $endDateInput = $("#job_completion_date");
                $endDateInput.prop("readonly", false);
                // Update the min attribute of the end date
                $endDateInput.attr("min", startDate);

                // Calculate the max date (startDate + 2 years)
                    var maxDate = new Date(startDate);
                    maxDate.setFullYear(maxDate.getFullYear()); // Add 2 years

                    // Format the maxDate to YYYY-MM-DD
                    var formattedMaxDate = maxDate.toISOString().split("T")[0];

                    // Update the max attribute of the end date
                    $endDateInput.attr("max", formattedMaxDate);

                // If the current end date is less than the start date, clear it
                if ($endDateInput.val() && $endDateInput.val() < startDate) {
                    $endDateInput.val("");
                }
             });
            // Close Modal on "Close" button or when clicking outside
            document.getElementById('job_open_date').addEventListener('change', function () {
        var inputField = this;
        var inputDate = new Date(inputField.value); // Get the selected date
        var today = new Date(); // Current date

        // Check if the input date is in the future
        if (inputDate > today) {
            inputField.value = ""; // Clear the invalid value
            inputField.placeholder = "Future dates are not allowed!"; // Show error in the placeholder
            inputField.classList.add('is-invalid'); // Add red border for invalid input
        } else {
            inputField.classList.remove('is-invalid'); // Remove error state
            inputField.placeholder = "Application Start Date"; // Reset placeholder
        }
    });
     document.getElementById('job_completion_date').addEventListener('change', function () {
        var inputField = this;
        var inputDate = new Date(inputField.value); // Get the selected date
        var today = new Date(); // Current date

        // Check if the input date is in the future
        if (inputDate > today) {
            inputField.value = ""; // Clear the invalid value
            inputField.placeholder = "Future dates are not allowed!"; // Show error in the placeholder
            inputField.classList.add('is-invalid'); // Add red border for invalid input
        } else {
            inputField.classList.remove('is-invalid'); // Remove error state
            inputField.placeholder = "Application End Date"; // Reset placeholder
        }
    });
    document.getElementById('dob').addEventListener('change', function () {
        var inputField = this;
        var inputDate = new Date(inputField.value); // Get the selected date
        var today = new Date(); // Current date

        // Check if the input date is in the future
        if (inputDate > today) {
            inputField.value = ""; // Clear the invalid value
            inputField.placeholder = "Future dates are not allowed!"; // Show error in the placeholder
            inputField.classList.add('is-invalid'); // Add red border for invalid input
        } else {
            inputField.classList.remove('is-invalid'); // Remove error state
            inputField.placeholder = "DOB"; // Reset placeholder
        }
    });
            $("#subscriber").change(function() {
                var subscriber = $(this).val();
                $.ajax({
                    url: 'check_client_limit',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        subscriber: subscriber,
                    },
                    cache: false,
                    success: function(data) {
                        //   console.log(data);
                        if (data.limit == 'full') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Oops..',
                                text: 'Client limit reached for this Subscriber!'
                            });
                            setTimeout(function() {
                                window.location.reload();
                            }, 5000);
                        }
                        if (data.clients.length > 0) {
                            var options =
                            '<option value="">Select Client</option>'; // Default option
                            data.clients.forEach(function(client) {
                                // Add each client as an option in the dropdown
                                options += '<option value="' + client.id + '">' + client
                                    .name +'('+client.subscriber.name +')'+'</option>';
                            });
                            $("#clients").html(
                            options); // Update the clients dropdown with the new options
                        } else {
                            // Optionally handle the case when no clients are found
                            $("#clients").html(
                            '<option value="">No clients available</option>');
                        }
                    }
                });
            });

            $("#add-clients-app").change(function() {
                var selectedOption = $(this).find(":selected"); // Get the selected option
                var id = selectedOption.data("subscriberid"); //
                var name = 'subscriber';
                // console.log(counrty);
                $.ajax({
                    url: 'get_job_role',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: id,
                        name: name,
                    },
                    cache: false,
                    success: function(data) {
                        console.log(data);
                        $("#job_role").html(data);
                    }
                });
            });
            $("#subscriber").change(function() {
                var id = $(this).val();
                var name = 'subscriber';
                // console.log(counrty);
                $.ajax({
                    url: 'get_job_role',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: id,
                        name: name,
                    },
                    cache: false,
                    success: function(data) {
                        console.log(data);
                        $("#job_role").html(data);
                    }
                });
            });

            $('#add-client-dependent').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                // Proceed with AJAX call if validation passes
                const formData = $(this).serialize();
                $.ajax({
                    url: "{{ url('addClientDependent') }}",
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                        });
                        setTimeout(function () {
                        location.reload();
                    }, 5000); // 5000 milliseconds = 5 seconds

                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to add client application !',
                        });
                    },
                });
            });
            $('#add-client-application').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                // Proceed with AJAX call if validation passes
                const formData = $(this).serialize();
                $.ajax({
                    url: "{{ url('addClientApplication') }}",
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                        });
                        setTimeout(function () {
                        location.reload();
                    }, 5000); // 5000 milliseconds = 5 seconds

                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to add client application !',
                        });
                    },
                });
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
@if(session()->has('client_limit'))
  <script>
    // Swal.fire({
    //   icon: 'warning',
    //   title: 'Client Limit Reached!',
    //   text: 'Upgrade membership to add more Clients!'
    // })
    Swal.fire({
      icon: 'warning',
      title: 'Client Limit Reached!',
      text: 'Upgrade membership to add more Clients!',
      showCancelButton: true,
      confirmButtonText: 'Upgrade',
      cancelButtonText: 'Will do it later',
      buttonsStyling: true
    }).then((result) => {
      if (result.isConfirmed) {
        // Redirect to the upgrade page
        window.location.href = '{{ route('membership') }}'; // Replace with your actual upgrade URL
      }
    });
  </script>

@endif
@endsection()
