@extends('web.layout.main')
<style>
    .nav-item {
        margin: 0px !important;
        --bs-nav-tabs-border-radius: 0px !important;
    }
    <style>
        .error {
            border: 2px red solid !important;
        }
        #serviceTable th, #serviceTable td {
        text-align: center;
        vertical-align: middle;
        }
        #serviceTable th {
            font-weight: bold;
        }
    </style>
</style>

@section('main-section')
    @php

        use App\Models\UserRoles;
        $client_roles = UserRoles::where('user_id', '=', $user->id)
            ->where('module', '=', 'Clients')
            ->first();
        $application_roles = UserRoles::where('user_id', '=', $user->id)
            ->where('module', '=', 'Applications')
            ->first();
        $communication_roles = UserRoles::where('user_id', '=', $user->id)
            ->where('module', '=', 'Communication')
            ->first();
        $invoice_roles = UserRoles::where('user_id', '=', $user->id)
            ->where('module', '=', 'Invoices')
            ->first();
        $payment_roles = UserRoles::where('user_id', '=', $user->id)
            ->where('module', '=', 'Payments')
            ->first();
        $report_roles = UserRoles::where('user_id', '=', $user->id)
            ->where('module', '=', 'Reports')
            ->first();
        $subscription_roles = UserRoles::where('user_id', '=', $user->id)
            ->where('module', '=', 'Subscription')
            ->first();
        $setting_roles = UserRoles::where('user_id', '=', $user->id)
            ->where('module', '=', 'Settings')
            ->first();
        $support_roles = UserRoles::where('user_id', '=', $user->id)
            ->where('module', '=', 'Support')
            ->first();
    @endphp

    <div class="col-lg-10 column-client">
        <div class="client-dashboard">
            <div class="client-btn d-flex mb-2">
                <h3 class="text-primary">Settings</h3>
            </div>

            <ul class="nav nav-tabs border" id="settingsTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="general-tab" data-bs-toggle="tab" href="#general" role="tab"
                        aria-controls="general" aria-selected="true">General</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="invoice-tab" data-bs-toggle="tab" href="#invoice" role="tab"
                        aria-controls="invoice" aria-selected="false">Invoice</button>
                </li>
                <li class="nav-item"> 
                    <button class="nav-link" id="reports-tab" data-bs-toggle="tab" href="#reports" role="tab" aria-controls="reports" aria-selected="false"> Reports 
                    </button> 
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="service-tab" data-bs-toggle="tab" href="#service" role="tab"
                        aria-controls="service" aria-selected="false">Add New Service</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="appointment-tab" data-bs-toggle="tab"
                        href="#appointment" role="tab" aria-controls="appointment"
                        aria-selected="false">
                        Appointment Scheduler
                    </button>
                </li>
            </ul>

            <!-- Tabs Content -->
            <div class="tab-content" id="settingsTabContent">
                <!-- General Tab -->
                <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                    <div style="overflow: hidden!important;" class="table-wrapper">
                        <div class="row p-1 m-0">
                            <p class="m-0 p-1" style="font-size:18px;font-weight: 550;">General</p>
                        </div>
                        <form  id="general-settings-form">
                            @csrf
                            <div class="row p-1 mb-3 align-items-center">
                                <div class="col-6">
                                    <label>Time Zone</label>
                                </div>
                                <div class="col-6">
                                    <select id="timezone1" name="timezone" class="form-control form-select">
                                        @foreach ($tzlist as $time)
                                            <option {{ $user->timezone == $time ? 'selected' : '' }}>{{ $time }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row p-1 mb-3 align-items-center">
                                <div class="col-6">
                                    <label>Currency</label>
                                </div>
                                <div class="col-6">
                                    <select id="currenc" name="currency" class="form-control form-select">
                                        <option value="">Select Currency</option>
                                        @foreach ($currencies as $currency)
                                            <option
                                                {{ $user->currency == $currency->currency_code . '(' . $currency->currency_symbol . ')' ? 'selected' : '' }}
                                                value="{{ $currency->currency_code }}({{ $currency->currency_symbol }})">
                                                {{ $currency->currency_code }} - {{ $currency->currency_symbol }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row p-1 m-0">
                                <div class="col-6"></div>
                                <div class="col-6 text-end">
                                    <button type="button" class="btn btn-primary" id="save-general-settings">Apply/Send</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Invoice Tab -->
                <div class="tab-pane fade" id="invoice" role="tabpanel" aria-labelledby="invoice-tab">
                    <div class="row p-1 m-0">
                        <p class="m-0 p-1" style="font-size:18px;font-weight: 550;">Invoice</p>
                    </div>
                    <form id="invoice-settings-form">
                        @csrf

                        <div class="row p-1 mb-3 align-items-center">
                            <div class="col-6">
                                <label>Tax (%)</label>
                            </div>
                            <div class="col-6">
                                <input type="number" min="0" max="100" value="{{ !empty($inv_setting) ? $inv_setting->tax : '' }}"
                                    name="tax" class="form-control" placeholder="Tax(%)">
                            </div>
                        </div>
                        <div class="row p-1 mb-3 align-items-center">
                            <div class="col-6">
                                <label>Discount (%)</label>
                            </div>
                            <div class="col-6">
                                <input type="number" min="0" max="100" value="{{  !empty($inv_setting) ? $inv_setting->discount : ''; }}"
                                    name="discount" class="form-control" placeholder="Discount(%)">
                            </div>
                        </div>
                        <div class="row p-1 mb-3 align-items-center">
                            <div class="col-6">
                                <label>Payment Link</label>
                            </div>
                            <div class="col-6">
                                <input type="url"   value="{{  !empty($inv_setting) ? $inv_setting->payment_link : '' }}"
                                    id="discount" name="payment_link" class="form-control" placeholder="Payment Link" >
                            </div>
                        </div>
                        <div class="row p-1 m-0">
                            <div class="col-6"></div>
                            <div class="col-6 text-end">
                                <button type="button" class="btn btn-primary" id="save-invoice-settings">Apply/Send</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Add New Service Tab -->
                <div class="tab-pane fade" id="service" role="tabpanel" aria-labelledby="service-tab">
                    <div class="row p-1 m-0">
                        <p class="m-0 p-1" style="font-size:18px;font-weight: 550;">Add New Service</p>
                    </div>
                    <form  id="add-service">
                        <input type="hidden" name="id" value=""  id="serviceId"/>
                        @csrf
                        <div class="row p-1 mb-3 align-items-center">
                            <div class="col-6">
                                <label>Service Name</label>
                            </div>
                            <div class="col-6">
                                <input type="text" id="serviceName" name="service_name" class="form-control"
                                    placeholder="Service Name">
                            </div>
                        </div>
                        <div class="row p-1 mb-3 align-items-center">
                            <div class="col-6">
                                <label>Fees (Amount)</label>
                            </div>
                            <div class="col-6">
                                <input type="number" id="serviceFee" name="fees" class="form-control"
                                    placeholder="Fees(Amount)">
                            </div>
                        </div>
                        <div class="row p-1 mb-3 align-items-center">
                            <div class="col-6"></div>
                            <div class="col-6 text-end">
                                <button type="button" class="btn btn-primary" id="save-add-service">Apply/Send</button>
                            </div>
                        </div>
                    </form>
                    <div class="row p-1 m-0">
                        <div class="col-12">
                            <div class="table-wrapper">
                                <table class="fl-table table table-hover table-responsive p-0 m-0" style="width:100%"
                                    id="serviceTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Sr No.</th>
                                            <th class="text-center">Sub_Name(Sub_ID)</th>
                                            <th class="text-center">User_Name(User_ID)</th>
                                            <th class="text-center">Service Name</th>
                                            <th class="text-center">Fees</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>


                                    <tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="tab-pane fade" id="reports" role="tabpanel" aria-labelledby="reports-tab">

                    <div class="row p-1 m-0">
                        <p class="m-0 p-1" style="font-size:18px;font-weight:550;">
                        Reports Settings
                        </p>
                    </div>

                    <form id="reports-settings-form">
                        @csrf

                        <!-- Select Modules -->
                        <div class="row p-1 mb-3 align-items-center">
                            <div class="col-6">
                            <label>Select Module(s)</label>
                            </div>

                        <div class="col-6">
                            <div class="form-check">
                                <input type="checkbox" name="modules[]" value="clients" class="form-check-input">
                                <label class="form-check-label">Clients</label>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" name="modules[]" value="applications" class="form-check-input">
                                <label class="form-check-label">Applications</label>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" name="modules[]" value="invoices" class="form-check-input">
                                <label class="form-check-label">Invoices</label>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" name="modules[]" value="payments" class="form-check-input">
                                <label class="form-check-label">Payments</label>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" name="modules[]" value="referrals" class="form-check-input">
                                <label class="form-check-label">Referrals</label>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" name="modules[]" value="wallets" class="form-check-input">
                                <label class="form-check-label">Wallets</label>
                            </div>

                        </div>
                        </div>


                        <!-- Frequency -->
                        <div class="row p-1 mb-3 align-items-center">
                        <div class="col-6">
                        <label>Select Frequency</label>
                        </div>

                        <div class="col-6">
                        <select name="frequency" class="form-control form-select">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly</option>
                        </select>
                        </div>
                        </div>


                        <!-- Delivery Mode -->
                        <div class="row p-1 mb-3 align-items-center">
                        <div class="col-6">
                        <label>Delivery Mode</label>
                        </div>

                        <div class="col-6">

                        <select name="delivery_mode" class="form-control form-select">
                        <option value="attachment">Reports as PDF in Email Attachment</option>
                        <option value="link">Links to View / Download Reports</option>
                        </select>

                        </div>
                        </div>


                        <!-- Send To Emails -->
                        <div class="row p-1 mb-3 align-items-center">
                        <div class="col-6">
                        <label>Send To</label>
                        </div>

                        <div class="col-6">
                        <textarea name="emails" class="form-control"
                        placeholder="Enter upto 5 emails separated by comma"></textarea>
                        <small class="text-muted">Example: test1@gmail.com, test2@gmail.com</small>
                        </div>
                        </div>


                        <!-- Buttons -->
                        <div class="row p-1 m-0">
                        <div class="col-6"></div>

                        <div class="col-6 text-end">

                        <button type="button" class="btn btn-primary"
                        id="save-reports-settings">
                        Apply
                        </button>

                        <button type="reset" class="btn btn-secondary">
                        Cancel
                        </button>

                        </div>
                        </div>

                    </form>

                </div>

                <div class="tab-pane fade" id="appointment" role="tabpanel" aria-labelledby="appointment-tab">

                    <div class="row p-1 m-0">
                        <p class="m-0 p-1" style="font-size:18px;font-weight:550;">
                            Appointment Scheduler
                        </p>
                    </div>

                    <form id="appointment-form">
                        @csrf

                        <!-- Select Client -->
                        <div class="row p-1 mb-3 align-items-center">
                            <div class="col-6">
                                <label>Select Client</label>
                            </div>
                            <div class="col-6">
                                <select name="client_id" class="form-control form-select">
                                    <option value="">Select Client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}">
                                            {{ $client->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="row p-1 mb-3 align-items-center">
                            <div class="col-6">
                                <label>Description (Meeting Purpose)</label>
                            </div>
                            <div class="col-6">
                                <input type="text" name="remarks" class="form-control"
                                    placeholder="Meeting Purpose">
                            </div>
                        </div>

                        <!-- Select Date -->
                        <div class="row p-1 mb-3 align-items-center">
                            <div class="col-6">
                                <label>Select Date</label>
                            </div>
                            <div class="col-6">
                                <input type="date" name="date" class="form-control">
                            </div>
                        </div>

                        <!-- Select Time -->
                        <div class="row p-1 mb-3 align-items-center">
                            <div class="col-6">
                                <label>Select Time</label>
                            </div>
                            <div class="col-6">
                                <input type="time" name="time" class="form-control">
                            </div>
                        </div>

                        <!-- Send Link Via -->
                        <div class="row p-1 mb-3 align-items-center">
                            <div class="col-6">
                                <label>Send Link Via</label>
                            </div>
                            <div class="col-6">

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="send_via"
                                        value="email" checked>
                                    <label class="form-check-label">Email</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="send_via"
                                        value="sms">
                                    <label class="form-check-label">SMS</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="send_via"
                                        value="both">
                                    <label class="form-check-label">Both</label>
                                </div>

                            </div>
                        </div>

                    <!-- Buttons -->
                    <div class="row p-1 m-0">
                        <div class="col-6"></div>
                        <div class="col-6 text-end">

                            <button type="button" class="btn btn-primary"
                                id="save-appointment">
                                Apply
                            </button>

                            <button type="reset" class="btn btn-secondary">
                                Cancel
                            </button>

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

        $('#save-reports-settings').click(function () {

            let formData = $('#reports-settings-form').serialize();

            $.ajax({
                url: "{{ route('save_report_settings') }}",
                method: "POST",
                data: formData,

                success: function(response){

                    Swal.fire({
                        icon:'success',
                        title:'Success',
                        text: response.message
                    });
                    $('#reports-settings-form')[0].reset();

                },

                error:function(){

                    Swal.fire({
                        icon:'error',
                        title:'Error',
                        text:'Failed to save report settings'
                    });

                }
            });

        });

        $('#save-appointment').click(function () {

            let formData = $('#appointment-form').serialize();

            $.ajax({
                url: "{{ route('save_appointment') }}",
                method: 'POST',
                data: formData,
                success: function (response) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    });

                    $('#appointment-form')[0].reset();

                },
                error: function () {

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to schedule appointment!'
                    });

                }
            });

        });
        function deleteapplication(id) {
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
                    window.location.href = "delete_application/" + id + "";
                }
            })
        }

        function updateapplication(id) {
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
                    window.location.href = "application_update/" + id + "";
                }
            })
        }
    </script>
    <script>
        $(document).ready(() => {
            $("#country").change(function() {
                var country = $(this).val();
                // console.log(counrty);
                $.ajax({
                    url: 'get_states',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        country: country,
                    },
                    cache: false,
                    success: function(data) {
                        console.log(data);
                        $("#state").html(data);
                    }
                });
            });
            $("#currency").change(function() {
                var currency = $(this).val();
                // console.log(counrty);
                $.ajax({
                    url: "{{ route('update_my_currency') }}",
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        currency: currency,
                    },
                    cache: false,
                    success: function(data) {
                        if (data = "currency_updated") {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Currency Updated Successfully!'
                            })
                        }
                    }
                });
            });
            $("#tax").change(function() {
                var tax = $(this).val();
                // console.log(counrty);
                $.ajax({
                    url: "{{ route('invoice_settings') }}",
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        tax: tax,
                    },
                    cache: false,
                    success: function(data) {
                        if (data = "setting_saved") {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Settings Updated Successfully!'
                            })
                        }
                    }
                });
            });
            $("#discount").change(function() {
                var discount = $(this).val();
                // console.log(counrty);
                $.ajax({
                    url: "{{ route('invoice_settings') }}",
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        discount: discount,
                    },
                    cache: false,
                    success: function(data) {
                        if (data = "setting_saved") {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Settings Updated Successfully!'
                            })
                        }
                    }
                });
            });
            $("#timezone").change(function() {
                var timezone = $(this).val();
                // console.log(counrty);
                $.ajax({
                    url: 'update_timezone',
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        timezone: timezone,
                    },
                    cache: false,
                    success: function(data) {
                        if (data = "timezone_updated") {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Timezone Updated Successfully!'
                            })
                        }
                    }
                });
            });



            $('#serviceTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('get_subscriber_service') }}",
                    type: 'GET',
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'subscriber',
                        name: 'subscriber'
                    },
                    {
                        data: 'user',
                        name: 'user'
                    },
                    {
                        data: 'service_name',
                        name: 'service_name'
                    },
                    {
                        data: 'fees',
                        name: 'fees'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });



        });
        $(document).on('click', '.editService', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const fee = $(this).data('fee');

            // Populate the modal fields
            $('#add-service #serviceId').val(id);
            $('#add-service #serviceName').val(name);
            $('#add-service #serviceFee').val(fee);

            // Show the modal
        });

        function deleteService(serviceId) {
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
                    // Send an AJAX request to delete the service
                    $.ajax({
                        url: '/services_delete/' + serviceId, // Update with your route
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}" // Include CSRF token
                        },
                        success: function(response) {
                            Swal.fire('Deleted!', response.message, 'success');
                            // Refresh the DataTable or remove the row
                            $('#serviceTable').DataTable().ajax.reload(null, false);
                        },
                        error: function(xhr) {
                            Swal.fire('Error!', 'An error occurred while deleting the service.',
                                'error');
                        }
                    });
                }
            });
        }
        $('#save-general-settings').click(function () {
                let formData = $('#general-settings-form').serialize();

                $.ajax({
                    url: "{{ route('update_my_currency') }}",
                    method: 'POST',
                    data: formData,
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'General Settings Updated Successfully!!',
                        });
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to update settings!',
                        });
                    },
                });
            });
            $('#save-invoice-settings').click(function () {
                    let formData = $('#invoice-settings-form').serialize();

                    $.ajax({
                        url: "{{ route('invoice_settings') }}",
                        method: 'POST',
                        data: formData,
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                            });
                        },
                        error: function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to update invoice settings!',
                            });
                        },
                    });
            });
            $('#save-add-service').click(function () {
                    let formData = $('#add-service').serialize();

                    $.ajax({
                        url: "{{ route('add_service') }}",
                        method: 'POST',
                        data: formData,
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                            });
                            $('#add-service')[0].reset();
                            $('#serviceTable').DataTable().ajax.reload(null, false);
                        },
                        error: function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to update invoice settings!',
                            });
                        },
                    });
            });
    </script>

    @if (session()->has('deleted'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Application Deleted Successfully!'
            })
        </script>
    @endif
    @if (session()->has('setting_saved'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Invoice Settings Updated Successfully!'
            })
        </script>
    @endif
    @if (session()->has('setting_general'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'General Settings Updated Successfully!'
            })
        </script>
    @endif
    @if (session()->has('application_updated'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Application Updated Successfully!'
            })
        </script>
    @endif
    @if (session()->has('success_services'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success_services') }}'
            })
        </script>
    @endif
@endsection()
