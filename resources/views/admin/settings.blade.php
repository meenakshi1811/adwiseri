@extends('admin.layout.main')
<style>
    .nav-item {
        margin: 0px !important;
        --bs-nav-tabs-border-radius: 0px !important;
    }
</style>

@section('main-section')
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
                    <button class="nav-link" id="service-tab" data-bs-toggle="tab" href="#service" role="tab"
                        aria-controls="service" aria-selected="false">Discounts & Offers</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="reports-tab" data-bs-toggle="tab" href="#reports" role="tab"
                        aria-controls="reports" aria-selected="false">Reports</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="email-template-tab" data-bs-toggle="tab" href="#email-template" role="tab"
                        aria-controls="email-template" aria-selected="false">Email Templates</button>
                </li>
            </ul>

            <!-- Tabs Content -->
            <div class="tab-content" id="settingsTabContent">
                <!-- General Tab -->
                <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                    <div style="overflow:hidden;" class="table-wrapper">

                        <div class="row p-1 m-0">
                            <p class="m-0 p-1" style="font-size:18px;font-weight: 550;">General</p>
                        </div>
                        <form id="general-settings-form">
                            @csrf
                            <!-- Time Zone -->
                            <div class="row p-1 mb-3 align-items-center">
                                <div class="col-6">
                                    <label for="timezon" class="form-label">Time Zone</label>
                                </div>
                                <div class="col-6">
                                    <select id="timezon" name="timezone" class="form-control form-select">
                                        @foreach ($tzlist as $time)
                                            <option {{ $user->timezone == $time ? 'selected' : '' }}>{{ $time }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- Currency -->
                            <div class="row p-1 mb-3 align-items-center">
                                <div class="col-6">
                                    <label for="currenc" class="form-label">Currency</label>
                                </div>
                                <div class="col-6">
                                    <select id="currenc" name="currency" class="form-control form-select">
                                        <option value="">Select Currency</option>
                                        @foreach ($currencies as $currency)
                                            <option
                                                {{ $user->currency == $currency->currency_code . '(' . $currency->currency_symbol . ')' ? 'selected' : '' }}
                                                value="{{ $currency->currency_code }}({{ $currency->currency_symbol }})">
                                                {{ $currency->currency_code }} - {{ $currency->currency_symbol }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- Apply/Send Button -->
                            <div class="row p-1 m-0">
                                <div class="col-12 text-end">
                                    <button type="button" id="save-general-settings"class="btn btn-primary">Apply/Send</button>
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
                        <input type="hidden" name="id" value="1" />

                        <div class="row p-1 mb-3 align-items-center">
                            <div class="col-6">
                                <label>Tax (%)</label>
                            </div>
                            <div class="col-6">
                                <input type="number" min="1" max="100" value="{{ $inv_setting->tax }}"
                                    id="tax" name="tax" class="form-control" placeholder="Tax (%)">
                            </div>
                        </div>
                        <div class="row p-1 mb-3 align-items-center">
                            <div class="col-6">
                                <label>Discount(%)</label>
                            </div>
                            <div class="col-6">
                                <input type="number" min="1" max="100" value="{{ $inv_setting->discount }}"
                                    id="discount" name="discount" class="form-control" placeholder="Discount (%)">
                            </div>
                        </div>

                        <div class="row p-1 m-0">
                            <div class="col-6">
                                {{-- <label>Description</label> --}}
                            </div>
                            <div class="col-6 text-end">
                                <button type="button" class="btn btn-primary" id="save-invoice-settings">Apply/Send</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Add New Service Tab -->
                <div class="tab-pane fade" id="service" role="tabpanel" aria-labelledby="service-tab">


                    <div class="row p-1 m-0">
                        <p class="m-0 p-1" style="font-size:18px;font-weight: 550;">Discounts & Offers</p>
                    </div>

                    <form id="offers-settings-form" method="post">
                        @csrf

                        <div class="row p-1 mb-3 align-items-center">
                            <div class="col-6">
                                <label>Select Subscriber(s)</label>
                            </div>
                            <div class="col-6">

                                <div class="dropdown">
                                    <div class="form-control dropdown-toggle" data-bs-toggle="dropdown">
                                        Select Subscriber(s)
                                    </div>
                                    <div class="dropdown-menu form-control">

                                        <div class="dropdown-item" style="width: 100%;"><input type="checkbox"  id="selectAll"
                                                name="subscribers[]" value="All" />All</div>

                                        @if ($subscribers)
                                            @foreach ($subscribers as $suser)
                                                <div class="dropdown-item" style="width: 100%;">
                                                    <input type="checkbox"
                                                     class="subscriber-checkbox"
                                                        name="subscribers[]" value="{{ $suser->id }}" />
                                                    {{ $suser->name }} ({{ $suser->id }}) ({{ $suser->user_type }})</div>
                                            @endforeach
                                        @endif

                                    </div>
                                </div>
                                @error('subscribers')
                                    <span style="color: red;">{{ $message }}</span>
                                @enderror

                            </div>
                        </div>
                        <div class="row p-1 mb-3 align-items-center">
                            <div class="col-6">
                                <label>Select Type of subscribers</label>
                            </div>
                            <div class="col-6">
                                <select id="subscriber_type" name="subscriber_type" class="form-control" required>
                                    <option value="existing" {{ old('subscriber_type', 'existing') == 'existing' ? 'selected' : '' }}>Existing</option>
                                    <option value="new" {{ old('subscriber_type') == 'new' ? 'selected' : '' }}>New</option>
                                </select>
                            </div>
                        </div>

                        <div id="offer-date-range" style="display:none;">
                            <div class="row p-1 mb-3 align-items-center">
                                <div class="col-6">
                                    <label>Select Dates</label>
                                </div>
                                <div class="col-3">
                                    <input type="date" id="offer_start_date" name="offer_start_date" class="form-control" value="{{ old('offer_start_date') }}" />
                                </div>
                                <div class="col-3">
                                    <input type="date" id="offer_end_date" name="offer_end_date" class="form-control" value="{{ old('offer_end_date') }}" />
                                </div>
                            </div>
                        </div>
                        <div class="row p-1 mb-3 align-items-center">
                            <div class="col-6">
                                <label>Type of Discount</label>
                            </div>
                            <div class="col-6">
                                <select id="discount_type" name="discount_type" class="form-control" required>
                                    <option value="" {{ empty(old('discount_type')) ? 'selected' : '' }}>Select
                                        Discount Type
                                    </option>
                                    <option value="cashback" {{ old('discount_type') == 'cashback' ? 'selected' : '' }}>
                                        Cashback
                                    </option>
                                    <option value="one_off" {{ old('discount_type') == 'one_off' ? 'selected' : '' }}>
                                        One-off credit
                                    </option>
                                    <option value="double_term"
                                        {{ old('discount_type') == 'double_term' ? 'selected' : '' }}>
                                        Double the subscription term</option>
                                </select>
                                @error('discount_type')
                                    <span style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div id="dynamic-field" class="form-group" style="display: none;">
                            <div class="row p-1 mb-3 align-items-center">
                                <div class="col-6">
                                    <label for="discount_value" id="discount_label">Discount Amount</label>
                                </div>
                                <div class="col-6">
                                    <input type="number" id="discount_value" name="discount_value" class="form-control"
                                        placeholder="Enter Discount Value" min="1" step="any"
                                        value="{{ old('discount_value') }}" required />
                                    @error('discount_value')
                                        <span style="color: red;">{{ $message }}</span>
                                    @enderror
                                    <br />
                                </div>
                            </div>
                        </div>
                        <div class="row p-1 m-0">
                            <div class="col-6">
                                {{-- <label>Description</label> --}}
                            </div>
                            <div class="col-6 text-end">
                                <button type="submit" class="btn btn-primary" id="save-offers-settings">Apply/Send</button>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="tab-pane fade" id="reports" role="tabpanel" aria-labelledby="reports-tab">
                    <div class="row p-1 m-0">
                        <p class="m-0 p-1" style="font-size:18px;font-weight:550;">Reports Settings</p>
                        <small class="text-muted px-2">A single PDF will be generated for the selected modules and sent on the selected frequency.</small>
                    </div>

                    <form id="reports-settings-form">
                        @csrf
                    <div class="row p-1 mb-3 align-items-center">
                        <div class="col-6">
                                    <label>Select Module(s)</label>
                                </div>
                                <div class="col-6">
                                    @php
                                        $selectedModules = [];
                                    @endphp
                                    @foreach ($reportModules as $moduleKey => $moduleLabel)
                                        <div class="form-check">
                                            <input type="checkbox" name="modules[]" value="{{ $moduleKey }}" class="form-check-input"
                                                {{ in_array($moduleKey, $selectedModules) ? 'checked' : '' }}>
                                            <label class="form-check-label">{{ $moduleLabel }}</label>
                                        </div>
                                    @endforeach
                                    @error('modules')
                                        <span style="color: red;">{{ $message }}</span>
                                    @enderror

                                </div>
                    </div>

                    <div class="row p-1 mb-3 align-items-center">
                        <div class="col-6">
                            <label>Select Frequency</label>
                        </div>
                        <div class="col-6">
                            <select name="frequency" class="form-control form-select">
                                @php
                                    $selectedFrequency = 'daily';
                                @endphp
                                <option value="daily" {{ $selectedFrequency == 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="weekly" {{ $selectedFrequency == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="monthly" {{ $selectedFrequency == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="quarterly" {{ $selectedFrequency == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                            </select>
                        </div>
                    </div>

                    <div class="row p-1 mb-3 align-items-center">
                        <div class="col-6">
                            <label>Delivery Mode</label>
                        </div>
                        <div class="col-6">
                            @php
                                $selectedDeliveryMode = 'attachment';
                            @endphp
                            <select name="delivery_mode" class="form-control form-select">
                                <option value="attachment" {{ $selectedDeliveryMode == 'attachment' ? 'selected' : '' }}>Reports as PDF in Email Attachment</option>
                                <option value="link" {{ $selectedDeliveryMode == 'link' ? 'selected' : '' }}>Links to View / Download Reports</option>
                            </select>
                        </div>
                    </div>

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

                    <div class="row p-1 m-0">
                        <div class="col-6"></div>
                        <div class="col-6 text-end">
                            <button type="button" class="btn btn-primary" id="save-reports-settings">Apply</button>
                            <button type="reset" class="btn btn-secondary">Cancel</button>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="email-template" role="tabpanel" aria-labelledby="email-template-tab">
                    <div class="row p-1 m-0">
                        <p class="m-0 p-1" style="font-size:18px;font-weight: 550;">Email Template</p>
                    </div>
                    <form id="email-template-form">
                        @csrf
                        <input type="hidden" id="emailAudience" name="audience" value="{{ $emailTemplateAudience }}">
                        <div class="row p-1 mb-3 align-items-center">
                            <div class="col-6"><label>Audience</label></div>
                            <div class="col-6">
                                <input type="text" class="form-control" value="{{ $emailTemplateAudience === 'admin' ? 'Admin Mail Templates' : 'Subscriber Mail Templates' }}" readonly>
                            </div>
                        </div>
                        <div class="row p-1 mb-3 align-items-center">
                            <div class="col-6"><label>Select Email Template</label></div>
                            <div class="col-6"><select id="emailTemplateKey" class="form-control form-select" name="template_key"></select></div>
                        </div>
                        <div class="row p-1 mb-3 align-items-center" id="otherTemplateRow" style="display:none;">
                            <div class="col-6"><label>Other (text input)</label></div>
                            <div class="col-6"><input type="text" id="otherTemplateName" class="form-control" name="custom_name" placeholder="Template name"></div>
                        </div>
                        <div class="row p-1 mb-3 align-items-center">
                            <div class="col-6"><label>Subject</label></div>
                            <div class="col-6"><input type="text" id="emailTemplateSubject" class="form-control" name="subject" placeholder="Email subject"></div>
                        </div>
                        <div class="row p-1 mb-3 align-items-start">
                            <div class="col-6"><label>Email Body</label></div>
                            <div class="col-6"><textarea id="emailTemplateBody" name="body" class="form-control" rows="8"></textarea></div>
                        </div>
                        <div class="row p-1 m-0">
                            <div class="col-12 text-end">
                                <button type="button" class="btn btn-primary" id="save-email-template">Save</button>
                                <button type="button" class="btn btn-outline-secondary" id="reset-email-template">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>


    <script>
        const emailTemplateAudience = @json($emailTemplateAudience);
        const emailTemplatesData = @json(($emailTemplates[$emailTemplateAudience] ?? collect()->values()));
        let emailTemplateEditor = null;
        function getTemplatesMap() {
            const rows = (emailTemplatesData || []);
            return rows.reduce((acc, row) => {
                acc[row.template_key] = row;
                return acc;
            }, {});
        }
        function renderEmailTemplateOptions() {
            const map = getTemplatesMap();
            const select = $('#emailTemplateKey');
            select.html('');
            Object.keys(map).forEach((key) => {
                select.append(`<option value="${key}">${map[key].template_name}</option>`);
            });
            renderEmailTemplateDetails();
        }
        function renderEmailTemplateDetails() {
            const key = $('#emailTemplateKey').val();
            const template = getTemplatesMap()[key] || {};
            $('#otherTemplateRow').toggle(key === 'other');
            $('#otherTemplateName').val(template.custom_name || '');
            $('#emailTemplateSubject').val(template.subject || '');
            const body = template.body || '';
            if (emailTemplateEditor) {
                emailTemplateEditor.setData(body);
            } else {
                $('#emailTemplateBody').val(body);
            }
        }
    </script>

    <script>
        $('#save-reports-settings').click(function() {
            const $saveReportsButton = $('#save-reports-settings');
            const defaultButtonText = ($saveReportsButton.data('default-text') || $.trim($saveReportsButton.text()) || 'Apply');

            $saveReportsButton
                .data('default-text', defaultButtonText)
                .prop('disabled', true)
                .text('Submitting...');

            let formData = $('#reports-settings-form').serialize();

            $.ajax({
                url: "{{ route('save_report_settings') }}",
                method: "POST",
                data: formData,

                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    });

                    $('#reports-settings-form')[0].reset();
                },

                error: function(xhr) {
                    let message = 'Failed to save report settings';

                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        if (xhr.responseJSON.errors) {
                            const firstErrorKey = Object.keys(xhr.responseJSON.errors)[0];
                            if (firstErrorKey && xhr.responseJSON.errors[firstErrorKey][0]) {
                                message = xhr.responseJSON.errors[firstErrorKey][0];
                            }
                        }
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: message
                    });
                },

                complete: function() {
                    $saveReportsButton
                        .prop('disabled', false)
                        .text(defaultButtonText);
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


        document.addEventListener('DOMContentLoaded', function() {
            const discountType = document.getElementById('discount_type');
            const dynamicField = document.getElementById('dynamic-field');
            const discountLabel = document.getElementById('discount_label');
            const discountValue = document.getElementById('discount_value');
            const subscriberType = document.getElementById('subscriber_type');
            const offerDateRange = document.getElementById('offer-date-range');

            function updateDiscountField(type) {
                if (type === 'cashback') {
                    dynamicField.style.display = 'block';
                    discountLabel.textContent = 'Cashback (%)';
                    discountValue.placeholder = 'Enter percentage (e.g., 20)';
                    discountValue.setAttribute('max', '100');
                    discountValue.setAttribute('required', 'required');
                } else if (type === 'one_off') {
                    dynamicField.style.display = 'block';
                    discountLabel.textContent = 'Credit Amount';
                    discountValue.placeholder = 'Enter amount (e.g., $100)';
                    discountValue.setAttribute('max', '500');
                    discountValue.setAttribute('required', 'required');
                } else {
                    dynamicField.style.display = 'none';
                    discountValue.removeAttribute('required');
                    discountValue.value = '';
                }
            }

            function updateSubscriberTypeField(type) {
                const startDateInput = document.getElementById('offer_start_date');
                const endDateInput = document.getElementById('offer_end_date');

                if (type === 'new') {
                    offerDateRange.style.display = 'block';
                    startDateInput.setAttribute('required', 'required');
                    endDateInput.setAttribute('required', 'required');
                } else {
                    offerDateRange.style.display = 'none';
                    startDateInput.removeAttribute('required');
                    endDateInput.removeAttribute('required');
                    startDateInput.value = '';
                    endDateInput.value = '';
                }
            }

            discountType.addEventListener('change', function() {
                updateDiscountField(this.value);
            });

            subscriberType.addEventListener('change', function() {
                updateSubscriberTypeField(this.value);
            });

            updateDiscountField(discountType.value);
            updateSubscriberTypeField(subscriberType.value);
        });

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
                    url: 'update_currency',
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
            $('#save-general-settings').click(function() {
                let formData = $('#general-settings-form').serialize();

                $.ajax({
                    url: "{{ route('update_currency') }}",
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Settings Updated Successfully!',
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to update settings!',
                        });
                    },
                });
            });
            $('#save-invoice-settings').click(function() {
                let formData = $('#invoice-settings-form').serialize();

                $.ajax({
                    url: "{{ route('invoice_settings') }}",
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to update invoice settings!',
                        });
                    },
                });
            });
            // const discountInput = document.getElementById('discount_value');


            // discountInput.addEventListener('input', function () {
            //     if (this.value === '' || parseFloat(this.value) <= 0) {
            //         this.value = ''; // Clear the input
            //         this.placeholder = 'Error: Must be greater than 0'; // Show the error message
            //     } else {
            //         this.placeholder = ''; // Reset the placeholder
            //     }
            // });

            // $('#offers-settings-form').on('submit', function(e) {
            //     e.preventDefault(); // Prevent default form submission

            //     const discountValue = $('#discount_value').val();

            //     if (discountValue === '' || parseFloat(discountValue) < 1) {
            //         Swal.fire({
            //             icon: 'error',
            //             title: 'Error',
            //             text: 'Discount value must be greater than or equal to 1!',
            //         });
            //         return; // Stop form submission
            //     }

            //     // Proceed with AJAX call if validation passes
            //     const formData = $(this).serialize();
            //     $.ajax({
            //         url: "{{ url('offers_store') }}",
            //         method: 'POST',
            //         data: formData,
            //         success: function(response) {
            //             Swal.fire({
            //                 icon: 'success',
            //                 title: 'Success',
            //                 text: 'Discount applied successfully!',
            //             });
            //         },
            //         error: function(xhr) {
            //             Swal.fire({
            //                 icon: 'error',
            //                 title: 'Error',
            //                 text: 'Failed to apply discount!',
            //             });
            //         },
            //     });
            // });

            $('#selectAll').on('change', function () {
                const isChecked = $(this).is(':checked');
                $('.subscriber-checkbox').prop('checked', isChecked);
            });

            if (window.ClassicEditor) {
                ClassicEditor.create(document.querySelector('#emailTemplateBody'))
                    .then(editor => {
                        emailTemplateEditor = editor;
                        renderEmailTemplateOptions();
                    })
                    .catch(() => {
                        renderEmailTemplateOptions();
                    });
            } else {
                renderEmailTemplateOptions();
            }
            $('#emailTemplateKey').on('change', renderEmailTemplateDetails);
            $('#save-email-template').on('click', function () {
                const templateKey = $('#emailTemplateKey').val();
                const body = emailTemplateEditor ? emailTemplateEditor.getData() : $('#emailTemplateBody').val();
                $.ajax({
                    url: "{{ route('save_email_template') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                                                template_key: templateKey,
                        template_name: $('#emailTemplateKey option:selected').text(),
                        custom_name: templateKey === 'other' ? $('#otherTemplateName').val() : '',
                        subject: $('#emailTemplateSubject').val(),
                        body: body,
                    },
                    success: function (response) {
                        Swal.fire({ icon: 'success', title: 'Success', text: response.message });
                    },
                    error: function () {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to save email template!' });
                    }
                });
            });
            $('#reset-email-template').on('click', function () {
                renderEmailTemplateDetails();
            });

            $('#offers-settings-form').on('submit', function (e) {
                e.preventDefault();

                const discountValue = $('#discount_value').val();
                const discountType = $('#discount_type').val();
                const subscriberType = $('#subscriber_type').val();
                const offerStartDate = $('#offer_start_date').val();
                const offerEndDate = $('#offer_end_date').val();

                if ((discountType === 'cashback' || discountType === 'one_off') && (discountValue === '' || parseFloat(discountValue) < 1)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Discount value must be greater than or equal to 1!',
                    });
                    return;
                }

                if (subscriberType === 'new' && (!offerStartDate || !offerEndDate)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Please select start and end dates for new subscribers.',
                    });
                    return;
                }

                let selectedSubscribers = [];
                if ($('#selectAll').is(':checked')) {
                    selectedSubscribers.push('All');
                } else {
                    $('.subscriber-checkbox:checked').each(function () {
                        selectedSubscribers.push($(this).val());
                    });
                }

                if (selectedSubscribers.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Please select at least one subscriber!',
                    });
                    return;
                }

                const formData = {
                    discount_value: discountValue,
                    subscribers: selectedSubscribers,
                    discount_type: discountType,
                    subscriber_type: subscriberType,
                    offer_start_date: offerStartDate,
                    offer_end_date: offerEndDate,
                };

                $.ajax({
                    url: "{{ url('offers_store') }}",
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message || 'Discounts & offers applied successfully!',
                        });
                    },
                    error: function (xhr) {
                        const message = xhr?.responseJSON?.message || 'Failed to apply discount!';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: message,
                        });
                    },
                });
            });


        });
          document.addEventListener('DOMContentLoaded', function () {
        const selectAllCheckbox = document.getElementById('selectAll');
        const subscriberCheckboxes = document.querySelectorAll('.subscriber-checkbox');

        // Add event listener for "All" checkbox
        selectAllCheckbox.addEventListener('change', function () {
            if (this.checked) {
                // Uncheck and disable other checkboxes
                subscriberCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                    checkbox.disabled = true;
                });
            } else {
                // Enable other checkboxes
                subscriberCheckboxes.forEach(checkbox => {
                    checkbox.disabled = false;
                    checkbox.checked = false;
                });
            }
        });

        // Add event listeners for individual subscriber checkboxes
        subscriberCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                if (this.checked) {
                    // Uncheck the "All" checkbox if any subscriber is selected
                    selectAllCheckbox.checked = false;
                }
            });
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
    @if (session()->has('setting_general'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'General Settings Updated Successfully!'
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
    @if (session()->has('application_updated'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Application Updated Successfully!'
            })
        </script>
    @endif
    @if (session()->has('offer_apply'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Discounts & Offers Applied Successfully!'
            })
        </script>
    @endif
@endpush
