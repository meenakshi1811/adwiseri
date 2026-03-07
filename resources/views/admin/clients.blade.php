@extends('admin.layout.main')
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
    <div class="col-lg-10 column-client">
        <div class="client-dashboard">
            <div class="client-btn d-flex mb-2 ">
                {{-- <form class="form-inline d-flex justify-content-between w-100"> --}}
                {{-- <h3 class="text-primary">Clients</h3> --}}
                <h3 class="text-primary text-center flex-grow-1 text-center m-0">Clients</h3>
                <p>

                    {{-- <a href="{{ route('clients_export') }}" class="m-0">Export</a> --}}
                    <a href="{{ route('new_client') }}" class="m-0">Add Client</a>
                    <a href="javascript:void(0)" id="AddApplication" class="btn btn-primary">Add Application</a>
                    <a href="javascript:void(0)" id="AddDependent" class="m-0">Add Dependent</a>
                </p>


            </div>

                <div class="row m-0 pb-2">
                    <div class="col-6 border p-1 text-center bg-info text-white tab-anchor" >
                        Clients
                    </div>
                    <div class="col-6 border p-1 text-center tab-anchor" onclick="window.location.href = '{{ route('manage_dependents') }}';">
                        Dependants
                    </div>

                  </div>


                {{-- <div class="d-flex ">
                            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                        </div> --}}
                {{-- </form> --}}
                {{-- <i class="fa-solid fa-magnifying-glass"></i> --}}
                <div class="client-btn d-flex justify-content-between mb-4">

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
                                                class="form-control form-select @error('client_id') is-invalid @enderror">
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
                                                    readonly
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
                                        <div class="mb-3">
                                            <label for="clients" class="form-label">Subscriber <span class="text-danger" style="font-size: 18px;">*</span></label>
                                            <select name="subscriber_id" id="subscriber" required
                                                class="form-control form-select @error('subscriber_id') is-invalid @enderror">
                                                <option value="">Select Subscriber</option>
                                                @foreach ($subscribers as $client )
                                                <option value="{{$client->id}}">{{$client->name.'--('.$client->id.')' }}</option>
                                                @endforeach
                                            </select>
                                            @error('subscriber_id')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <!-- Client Selection -->
                                        <div class="mb-3">
                                            <label for="clients" class="form-label">Client <span class="text-danger" style="font-size: 18px;">*</span></label>
                                            <select name="client_id" id="clients" required
                                                class="form-control form-select @error('client_id') is-invalid @enderror">

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
                                                <input name="dob" type="date" max="{{ date('d-m-y') }}" class="form-control date @error('dob') is-invalid @enderror" id="dob" aria-describedby="dobHelp" value="{{ old('dob') }}" required placeholder="Date of Birth" autocomplete="dob">
                                                @error('dob')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror   
                                                <!-- <input name="dob" type="text"
                                                    class="form-control date @error('dob') is-invalid @enderror"
                                                    id="dob"
                                                    aria-describedby="emailHelp"
                                                    value="{{ old('dob') ? date('d-m-Y', strtotime(old('dob'))) : null }}"
                                                    placeholder="DateOfBirth"
                                                    autocomplete="dob"
                                                    max={{ date('d-m-Y')}}
                                                    required
                                                   onfocus="(this.type='date')"
                                                    />
                                                @error('dob')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror -->

                                        </div>

                                        <!-- Application Status -->

                                        <div class="mb-3">
                                            <label>Passport No</label>

                                                    <input oninput="validateInput(this)" name="passport_no" minlength="6" maxlength="14"
                                                    onkeyup="this.value = this.value.toUpperCase();" pattern="^[A-Z0-9]+$" type="text"
                                                    class="form-control @error('passport_no') is-invalid @enderror" id="exampleInputEmail1"
                                                    aria-describedby="emailHelp" value="{{ $client->passport_no }}"
                                                    placeholder="Passport No" autocomplete="passport_no">
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
            </div>
            <div class="table-wrapper">
                <table class="table table-hover table-bordered fl-table" id="clientTable">
                    <thead>
                        <tr>
                            <th class="text-center">Sr No.</th>
                            <th class="text-center">Client(ID)</th>
                            <th class="text-center">Client Name(Sub_ID)</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Phone</th>
                            <th class="text-center">Country</th>
                            {{-- <th>Sub_ID</th> --}}
                            <th class="text-center">NOA </th>
                            <th class="text-center">Created </th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clients as $key => $client)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td class="text-center">{{ $client->id }}</td>
                                <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $client->name }}" class="text-center" style="position: relative;">
                                    @if (strlen($client->name) > 15)
                                        {{ substr($client->name, 0, 15) }}...
                                        <span onmouseover="this.style.opacity='1';" onmouseout="this.style.opacity='0';"
                                            style="display:flex;opacity:0;align-items:center;padding:5px;position: absolute;left:0px;top:25px;height:100%;background:lightgrey;min-width:100%; width:fit-content;">{{ $client->name . '(' . $client->subscriber_id . ')' }}</span>
                                    @else
                                        {{ $client->name . '(' . $client->subscriber_id . ')' }}
                                    @endif
                                </td>
                                <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $client->email }}" class="text-center" style="position: relative;">
                                    @if (strlen($client->email) > 15)
                                        {{ substr($client->email, 0, 15) }}...
                                        <span onmouseover="this.style.opacity='1';" onmouseout="this.style.opacity='0';"
                                            style="display:flex;opacity:0;align-items:center;padding:5px;position: absolute;left:0px;top:25px;height:100%;background:lightgrey;min-width:100%; width:fit-content;">{{ $client->email }}</span>
                                    @else
                                        {{ $client->email }}
                                    @endif
                                </td>
                                <td class="text-center">{{ $client->phone }}</td>
                                <td class="text-center">{{ $client->country }}</td>
                                {{-- <td>{{ $client->subscriber_id }}</td> --}}
                                <td class="text-center"> {{ $client->applications ? $client->applications->count() ?? 'No' : 'No User' }}
                                </td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($client->created_at)->format('d-m-Y') }}</td>
                                <td class="text-center">
                                    <a style="background:transparent;border:none;" class="p-0 m-0 text-dark"
                                        href="{{ route('view_client', $client->id) }}"><i
                                            class="fa-solid fa-eye btn text-info p-1 m-0"></i></a>
                                    {{-- <a style="background:transparent;border:none;" class=" p-0 m-0 text-dark" href="{{ route('client_update', $client->id)}}"><i class="fa-solid fa-edit btn text-primary p-1 m-0" onclick="deleteclient({{ $client->id }})"></i></a> --}}
                                    <i class="fa-solid fa-edit btn text-primary p-1 m-0" style="font-size:14px;"
                                        onclick="updateclient({{ $client->id }})"></i>
                                    <i class="fa-solid fa-trash btn p-1 text-danger" style="font-size:14px;"
                                        onclick="deleteclient({{ $client->id }})"></i>
                                </td>
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
    @if (session()->has('deleted'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Client Deleted Successfully!'
            })
        </script>
    @endif
    @if (session()->has('client_added'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'New Client Added Successfully!'
            })
        </script>
    @endif
    @if (session()->has('client_updated'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Client Updated Successfully!'
            })
        </script>
    @endif
@endsection()
@push('scripts')

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
        function deleteclient(id) {
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
                    window.location.href = "delete_clients/" + id + "";
                }
            })
            // var conf = confirm('Delete Client');
            // if(conf == true){
            //     window.location.href = "delete_clients/"+id+"";
            // }
        }

        function updateclient(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to update record!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "client_update/" + id + "";
                }
            })
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

                // If the current end date is less than the start date, clear it
                if ($endDateInput.val() && $endDateInput.val() < startDate) {
                    $endDateInput.val("");
                }
             });
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
            // Close Modal on "Close" button or when clicking outside

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
@endpush
