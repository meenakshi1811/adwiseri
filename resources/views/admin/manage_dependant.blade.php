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
                <h3 class="text-primary text-center flex-grow-1 text-center m-0">Dependants</h3>
                <p>

                    {{-- <a href="{{ route('clients_export') }}" class="m-0">Export</a> --}}
                    {{-- <a href="{{ route('new_client') }}" class="m-0">Add New</a>
                    <a href="javascript:void(0)" id="AddApplication" class="btn btn-primary">Add Application</a> --}}
                    <a href="javascript:void(0)" id="AddDependent" class="m-0">Add Dependants</a>
                </p>

                {{-- <div class="d-flex ">
                            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                        </div> --}}
                {{-- </form> --}}
                {{-- <i class="fa-solid fa-magnifying-glass"></i> --}}

            </div>
            <div class="row m-0 pb-2">
                <div class="col-6 border p-1 text-center  tab-anchor"
                    onclick="window.location.href = '{{ route('manage_clients') }}';">
                    Clients
                </div>
                <div class="col-6 border p-1 text-center bg-info text-white tab-anchor">
                    Dependants
                </div>

            </div>
            <div class="table-wrapper">
                <table class="table table-hover table-bordered fl-table" id="clientTable">
                    <thead>
                        <tr>
                            <th class="p-1 text-center">Sr No.</th>
                            <th class="p-1 text-center">Dependant (ID)</th>
                            <th class="p-1 text-center">Client(ID)</th>
                            {{-- <th class="p-1 text-center">Sub(ID)</th> --}}
                            <th class="p-1 text-center">Gender</th>
                            <th class="p-1 text-center">Relation</th>
                            <th class="p-1 text-center">DOB</th>
                            {{-- <th class="p-1 text-center">Sub_ID</th> --}}
                            <th class="p-1 text-center">Passport No</th>
                            <th class="p-1 text-center">CreatedDate</th>
                            <th class="p-1 text-center">Action</th>
                            {{-- <th class="p-1 text-center">Action</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dependents as $key => $dependent)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td class="text-center">{{ $dependent->name . '(' . $dependent->id . ')' }}</td>
                                <td class="text-center">
                                    {{ $dependent->client_id ? $dependent->client->name . '(' . $dependent->client_id . ')' : '' }}
                                </td>
                                {{-- <td class="p-1 text-center">{{ ($dependent->client_id  && $dependent->client->subscriber_id) ? $dependent->client->subscriber->name.'('.$dependent->client->subscriber_id.')' : '' }}</td> --}}

                                <td class="text-center">{{ $dependent->gender }}</td>
                                <td class="text-center">{{ $dependent->relation }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($dependent->dob)->format('d-m-Y') }}</td>
                                <td class="text-center">{{ $dependent->passport_no }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($dependent->created_at)->format('d-m-Y') }}
                                </td>
                                <td class="text-center">
                                    {{-- <a style="background:transparent;border:none;" class="p-0 m-0 text-dark" href="{{ route('application_view', $doc->id)}}"><i class="fa-solid fa-eye btn text-info p-1 m-0"></i></a> --}}
                                    <i class="fa-solid fa-edit btn text-primary p-1 m-0" style="font-size:14px;"
                                        onclick="updatedpendant('{{ url('get_dependants/' . $dependent->id) }}')"></i>
                                    <i class="fa-solid fa-trash btn p-1 text-danger" style="font-size:14px;"
                                        onclick="deleteDependant({{ $dependent->id }})"></i>
                                </td>
                            </tr>
                        @endforeach

                    <tbody>
                </table>
            </div>
            <div id="addDependent" class="modal" tabindex="-1">
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
                                        <option value="">Select Gender</option>
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
                                    <!-- <input name="dob" type="date" max="{{ date('d-m-y') }}" class="form-control date @error('dob') is-invalid @enderror" id="dob" aria-describedby="dobHelp" value="{{ old('dob') }}" required placeholder="Date of Birth" autocomplete="dob">
                                                @error('dob')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror    -->
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
                                        onblur="(this.type='text')" />
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
            <div id="myDependent" class="modal" tabindex="-1">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h5 class="modal-title text-primary">Edit Dependant</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <!-- Modal Body -->
                        <div class="modal-body">
                            <form id="edit-client-dependent">
                                @csrf

                                <!-- Subscriber Selection -->
                                <div class="mb-3">
                                    <label for="clients" class="form-label">Subscriber <span
                                            class="text-danger">*</span></label>
                                    <select name="subscriber_id" id="subscriber" required
                                        class="form-control form-select @error('subscriber_id') is-invalid @enderror">
                                        <option value="">Select Subscriber</option>
                                        @foreach ($subscribers as $client)
                                            <option value="{{ $client->id }}">
                                                {{ $client->name . '--(' . $client->id . ')' }}</option>
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
                                    <label for="clients" class="form-label">Client <span
                                            class="text-danger">*</span></label>
                                    <select name="client_id" id="clients" required
                                        class="form-control form-select @error('client_id') is-invalid @enderror">
                                    </select>
                                    @error('client_id')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Dependent Name -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input name="name" type="text" id="name" placeholder="Dependent Name"
                                        class="form-control @error('name') is-invalid @enderror">
                                    @error('name')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Gender -->
                                <div class="mb-3">
                                    <label for="gender" class="form-label">Gender <span
                                            class="text-danger">*</span></label>
                                    <select name="gender" id="gender" required
                                        class="form-control form-select @error('gender') is-invalid @enderror">
                                        <option value="">Select Gender</option>
                                        <option {{ old('gender') == 'Male' ? 'selected' : '' }} value="Male">Male
                                        </option>
                                        <option {{ old('gender') == 'Female' ? 'selected' : '' }} value="Female">Female
                                        </option>
                                        <option {{ old('gender') == 'Prefer Not To Say' ? 'selected' : '' }} value="Prefer Not To Say">Prefer Not
                                            to Say</option>
                                    </select>
                                    @error('gender')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Relation with Client -->
                                <div class="mb-3">
                                    <label for="relation" class="form-label">Relation with Client <span
                                            class="text-danger">*</span></label>
                                    <select name="relation" id="job_status" required
                                        class="form-control form-select @error('relation') is-invalid @enderror">
                                        <option value="">Select Relation</option>
                                        <option {{ old('relation') == 'Husband' ? 'selected' : '' }} value="Husband">
                                            Husband</option>
                                        <option {{ old('relation') == 'Wife' ? 'selected' : '' }} value="Wife">Wife
                                        </option>
                                        <option {{ old('relation') == 'Son' ? 'selected' : '' }} value="Son">Son
                                        </option>
                                        <option {{ old('relation') == 'Daughter' ? 'selected' : '' }} value="Daughter">
                                            Daughter</option>
                                        <option {{ old('relation') == 'Father' ? 'selected' : '' }} value="Father">Father
                                        </option>
                                        <option {{ old('relation') == 'Mother' ? 'selected' : '' }} value="Mother">Mother
                                        </option>
                                        <option {{ old('relation') == 'Other' ? 'selected' : '' }} value="Other">Other
                                        </option>
                                    </select>
                                    @error('relation')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Date of Birth -->
                                <div class="mb-3">
                                    <label for="dob" class="form-label">Date of Birth <span
                                            class="text-danger">*</span></label>
                                    <input name="dob" type="text"
                                        class="form-control date @error('dob') is-invalid @enderror" id="edit-dob"
                                        value="{{ old('dob') ? date('Y-m-d', strtotime(old('dob'))) : '' }}"
                                        placeholder="Date of Birth" autocomplete="dob" max="{{ date('Y-m-d') }}"
                                        required onfocus="(this.type='date')" onblur="(this.type='text')" />
                                    @error('dob')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <!-- Passport Number -->
                                <div class="mb-3">
                                    <label>Passport No</label>
                                    <input oninput="validateInput(this)" name="passport_no" id="passport_no" minlength="6" maxlength="14"
                                        onkeyup="this.value = this.value.toUpperCase();" pattern="^[A-Z0-9]+$"
                                        type="text" class="form-control @error('passport_no') is-invalid @enderror"
                                        placeholder="Passport No" autocomplete="passport_no">
                                    @error('passport_no')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success">Update Dependant</button>
                                </div>
                            </form>
                            <input type="hidden" id="dependant" />
                        </div>
                    </div>
                </div>
            </div>
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
function validateInput(input) {
    input.value = input.value.replace(/[^A-Za-z0-9]/g, '');
}
</script>
    <script>
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
        var modal = document.getElementById("myDependent");
        var closeButtons = document.querySelectorAll(".btn-close, .close");
        closeButtons.forEach(function(closeButton) {
            closeButton.onclick = function() {
                modal.style.display = "none";

            };
        });

        function getClient(subscriber, client_id) {
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

                    if (data.clients.length > 0) {
                        var options =
                            '<option value="">Select Client</option>'; // Default option
                        data.clients.forEach(function(client) {
                            // Add each client as an option in the dropdown
                            options += '<option value="' + client.id + '">' + client
                                .name + '(' + client.subscriber.name + ')' + '</option>';
                        });
                        $("#clients").html(
                            options); // Update the clients dropdown with the new options
                        document.getElementById('clients').value = client_id;
                    } else {
                        // Optionally handle the case when no clients are found
                        $("#clients").html(
                            '<option value="">No clients available</option>');
                    }
                }
            });
        }

        function updatedpendant(url) {
            $.ajax({
                url: url, // Laravel route
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                cache: false,
                success: function(response) {
                    console.log(response);
                    var dependent = response.data
                    document.getElementById('subscriber').value = dependent.subscriber_id;
                    getClient(dependent.subscriber_id, dependent.client_id);
                    $('#name').val(dependent.name);
                    document.getElementById('gender').value = dependent.gender;
                    document.getElementById('job_status').value = dependent.relation;
                    const dob = dependent.dob ? new Date(dependent.dob).toISOString().split('T')[0] : '';
                    $('#dob').val(dob);
                    dependant
                    $('#dependant').val(dependent.id);
                    $('#passport_no').val(dependent.passport_no);

                    // const modal = document.getElementById('myDependent');
                    // modal.show();
                    modal.style.display = "block";
                }
            });


        }

        $(document).ready(function() {
                const btnDependent = document.getElementById("AddDependent");
                const modalAddDependent = document.getElementById("addDependent");
                const modalDependent = document.getElementById("myDependent");
                const closeButtons = document.querySelectorAll(".btn-close, .close");

                // Open dependent modal on button click
                // if (btnDependent && modalDependent) {
                //     btnDependent.onclick = function () {
                //         modalDependent.style.display = "block";
                //         fetchClients();
                //     };
                // }
                btnDependent.onclick = function () {
                    console.log("Button Clicked!"); // Debugging
                    modalAddDependent.style.display = "block";
                    fetchClients();
                };
                closeButtons.forEach(function (closeButton) {
                    closeButton.addEventListener("click", function () {
                        if (modalDependent) modalDependent.style.display = "none";
                        if (modalAddDependent) modalAddDependent.style.display = "none";
                });
            });
        });

        // Get modal, buttons, and close elements
        // var btnDependent = document.getElementById("AddDependent"); // Only if exists
        // var modalAddDependent = document.getElementById("addDependent"); // Only if exists
        // var closeButtons = document.querySelectorAll(".btn-close, .close");
        // var modalDependent = document.getElementById("myDependent")


        // // (Optional) Open affiliate modal on button click
        // if (btnDependent && modalDependent) {
        //     btnDependent.onclick = function() {
        //         modalDependent.style.display = "block";
        //         $.ajax({
        //             url: 'getClient',
        //             method: 'get',
        //             data: {
        //                 "_token": "{{ csrf_token() }}",
        //                 id: id,
        //                 name: name,
        //             },
        //             cache: false,
        //             success: function(data) {
        //                 console.log(data);

        //                 if (data.clients.length > 0) {
        //                     var options =
        //                         '<option value="">Select Client</option>'; // Default option
        //                     data.clients.forEach(function(client) {
        //                         // Add each client as an option in the dropdown
        //                         options += '<option value="' + client.id + '">' + client
        //                             .name + '</option>';
        //                     });
        //                     $("#add-clients-dependent").html(
        //                         options); // Update the clients dropdown with the new options
        //                 } else {
        //                     // Optionally handle the case when no clients are found
        //                     $("#add-clients-dependent").html(
        //                         '<option value="">No clients available</option>');
        //                 }
        //             }
        //         });
        //     };
        // }

        // // Close modal on clicking close buttons
        // closeButtons.forEach(function(closeButton) {
        //     closeButton.onclick = function() {
        //         modal.style.display = "none";
        //         if (modalDependent) modalDependent.style.display = "none";
        //     };
        // });

        // // Close modal if clicking outside of it
        // window.onclick = function(event) {
        //     if (event.target == modal) {
        //         modal.style.display = "none";
        //     }
        //     if (modalDependent && event.target == modalDependent) {
        //         modalDependent.style.display = "none";
        //     }
        // };
        function fetchClients() {
            $.ajax({
                url: 'getClient',
                method: 'GET',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                cache: false,
                success: function (data) {
                    let options = '<option value="">Select Client</option>';
                    if (data.clients.length > 0) {
                        data.clients.forEach(client => {
                            options += `<option value="${client.id}">${client.name}</option>`;
                        });
                    } else {
                        options = '<option value="">No clients available</option>';
                    }
                    $("#add-clients-dependent").html(options);
                }
            });
        }
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
                    setTimeout(function() {
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
        $('#edit-client-dependent').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            var id = $('#dependant').val();
            // Proceed with AJAX call if validation passes
            const formData = $(this).serialize();
            $.ajax({
                url: "{{ url('update_dependant') }}" + '/' + id,
                method: 'POST',
                data: formData,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                    });
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Dependant Updated Successfully!'
                    })
                    setTimeout(function() {
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
        function deleteDependant(dependantId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // If user confirms, proceed with the AJAX call
            $.ajax({
                url: `/delete_dependant/${dependantId}`, // Laravel route
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}" // CSRF token for security
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Dependant Deleted Successfully!'
                        });

                        // Optionally, remove the row from the UI or reload the page
                        setTimeout(function() {
                        location.reload();
                    }, 5000); // 5000 milliseconds = 5 seconds
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Failed to delete dependant.'
                        });
                    }
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while deleting the dependant.'
                    });
                }
            });
        }
    });
}

    </script>
@endpush
