@extends('web.layout.main')

@section('main-section')

    <div class="col-lg-10 column-client">

            <div class="client-dashboard">
                <div class="client-btn d-flex mb-2 ">
                    <form class="form-inline d-flex justify-content-between w-100">
                        <h3 class="text-primary">Add Payment (AP = Payments Made) Record</h3>
                    </form>
                </div>
                <div class="col">
                    <form id="registration_form" class="register-box login-box" method="POST"
                        action="{{ route('advance_payment') }}">
                        @csrf
                        <input type="hidden" name="local_time" class="localtime" />
                        <div class="row">
                             <!-- Payment Entry Type -->
                             <div class="col-md-4 p-1">
                                <label>Payment Entry Type<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="payment_entry_type" id="new_entry" value="New" checked onclick="toggleOutstanding(false)">
                                    <label class="form-check-label" for="new_entry">New</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="payment_entry_type" id="existing_entry" value="Existing" onclick="toggleOutstanding(true)">
                                    <label class="form-check-label" for="existing_entry">Existing</label>
                                </div>
                            </div>

                            <div class="col-md-4 p-1 inovice-section" class="display:none">
                                <label>Select Invoice<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1 inovice-section" class="display:none">
                            
                                <select name="invoices_list" id="invoices_list"
                                        class="form-control form-select @error('invoices') is-invalid @enderror"  onchange="fetchInvoiceDetails()">
                                    <option value="">Select Invoice</option>
                                    @foreach ($invoices as $invoice)
                                    <option {{ (old('invoices_list') == $invoice['id']) ? 'selected' : '' }}
                                            value="{{ $invoice['id'] }}"
                                            data-client-id="{{ $invoice['client_id'] }}"
                                            data-outstanding="{{ $invoice['outstanding_amount'] }}"
                                            data-paid="{{ $invoice['paid_amount'] }}">
                                            {{ $invoice['display_label'] }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('subscriber')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-4 p-1">
                                <label>Product/Service Provider<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="service_provider" type="text" minlength="3" maxlength="200"
                                    class="form-control @error('service_provider') is-invalid @enderror" id="service_provider"
                                    aria-describedby="ageHelp" value="{{ old('service_provider') }}" required readonly
                                    placeholder="Product/Service Provider" autocomplete="detail">
                                @error('service_provider')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Product/Service Taken<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="service_taken" type="text" minlength="3" maxlength="200"
                                    class="form-control @error('detail') is-invalid @enderror" id="service_taken"
                                    aria-describedby="ageHelp" value="{{ old('service_taken') }}" required readonly
                                    placeholder="Product/Service Taken" autocomplete="service_taken">
                                @error('service_taken')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-4 p-1">
                                <label>Total Amount<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="amount" required type="number" min="1" step="any"
                                    class="form-control @error('amount') is-invalid @enderror" id="amount"
                                    aria-describedby="emailHelp" value="{{ old('amount') }}" placeholder="Total Amount"
                                    autocomplete="amount" readonly>
                                @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1 outstanding-section">
                                <label>Outstanding</label>
                            </div>
                            <div class="col-md-8 p-1 outstanding-section">
                                <input name="outstanding_amount" min="0" step="0.01" type="number" class="form-control" id="outstanding_amount" placeholder="Outstanding Amount" readonly>
                            </div>
                            <div class="col-md-4 p-1 existing-only" style="display:none;">
                                <label>Amount Paid</label>
                            </div>
                            <div class="col-md-8 p-1 existing-only" style="display:none;">
                                <input type="number" class="form-control" id="amount_paid_existing" readonly>
                            </div>
                            <div class="col-md-4 p-1 existing-only outstanding-existing" style="display:none;">
                                <label>Outstanding</label>
                            </div>
                            <div class="col-md-8 p-1 existing-only outstanding-existing" style="display:none;">
                                <input min="0" step="0.01" type="number" class="form-control" id="outstanding_amount_existing" placeholder="Outstanding Amount" readonly>
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Amount Paying<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="paid_amount" required type="number" min="0.01" step="0.01"
                                    class="form-control @error('amount') is-invalid @enderror" id="paid_amount"
                                    aria-describedby="emailHelp" value="{{ old('paid_amount') }}" placeholder="Amount Paying"
                                    autocomplete="amount">
                                @error('paid_amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Mode Of Payment (MOP)
                                    {{-- <span class="text-danger" style="font-size: 18px;">*</span> --}}
                                </label>
                            </div>
                            <div class="col-md-8 p-1">
                                <select name="payment_mode" class="form-control form-select @error('payment_mode') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('payment_mode') }}" required>
                                    <option value="">Select Payment Mode</option>
                                    <option {{(old('payment_mode') == "Cash") ? 'selected':''}} value="Cash">Cash</option>
                                    <option {{(old('payment_mode') == "Cheque") ? 'selected':''}} value="Cheque">Cheque</option>
                                    <option {{(old('payment_mode') == "DD") ? 'selected':''}} value="DD">DD</option>
                                    <option {{(old('payment_mode') == "Wire") ? 'selected':''}} value="Wire">Wire</option>
                                    <option {{(old('payment_mode') == "UPI") ? 'selected':''}} value="UPI">UPI</option>
                                    <option {{(old('payment_mode') == "Vouchers") ? 'selected':''}} value="Vouchers">Vouchers</option>
                                    <option {{(old('payment_mode') == "Notes") ? 'selected':''}} value="Notes">Notes</option>
                                </select>
                                @error('payment_mode')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Payment Date</label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="payment_date" type="text"
                                class="form-control date @error('payment_date') is-invalid @enderror"
                                id="payment_date"
                                aria-describedby="emailHelp"
                                value="{{ old('payment_date') ? date('Y-m-d', strtotime(old('payment_date'))) : '' }}"
                                placeholder="{{ date('d-m-Y')}}"
                                autocomplete="dob"
                                max="{{ date('Y-m-d')}}"

                               onfocus="(this.type='date')"
                                onblur="(this.type='text')"
                                />
                                @error('payment_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-4 p-1"></div>
                            <div class="col-md-8 p-1 text-start">
                                <button type="submit" class="form-control btn btn-primary"
                                    style="width: fit-content;">Submit</button>
                            </div>
                        </div>
                    </form>


                </div>


            </div>

    </div>
    </div>

    </div>
    <script>
        // Function to fetch selected invoice details
        function fetchInvoiceDetails() {
            const selectedInvoiceId = document.getElementById("invoices_list").value;
            if (!selectedInvoiceId) return;

            fetch(`/invoices_ap/${selectedInvoiceId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById("service_provider").value = data.serviceProvider;
                    document.getElementById("service_taken").value = data.serviceTaken;
                    document.getElementById("amount").value = data.amount.toFixed(2);
                    document.getElementById("amount_paid_existing").value = data.paidAmmount.toFixed(2);
                    document.getElementById("paid_amount").value = "";
                    document.getElementById("outstanding_amount").value = data.outstandingAmount.toFixed(2);
                    document.getElementById("outstanding_amount_existing").value = data.outstandingAmount.toFixed(2);
                })
                .catch(error => console.error("Error fetching invoice details:", error));
        }
        toggleOutstanding(false);
        function toggleOutstanding(show) {
            let invoiceSections = document.querySelectorAll('.inovice-section');
            invoiceSections.forEach(section => {
                section.style.display = 'block';
            });
            document.querySelectorAll('.outstanding-existing').forEach(section => {
                section.style.display = show ? 'block' : 'none';
            });
            document.querySelectorAll('.outstanding-section').forEach(section => {
                section.style.display = show ? 'none' : 'block';
            });
            document.querySelectorAll('.existing-only').forEach(section => {
                section.style.display = show ? 'block' : 'none';
            });
            const invoiceSelect = document.getElementById("invoices_list");
            Array.from(invoiceSelect.options).forEach((opt, idx) => {
                if (idx === 0) return;
                const paidAmount = parseFloat(opt.dataset.paid || '0');
                const shouldShow = show ? paidAmount > 0 : paidAmount <= 0;
                opt.hidden = !shouldShow;
            });
            invoiceSelect.value = '';

            if(show ==false) {

                document.getElementById("service_provider").value = '';

                document.getElementById("service_taken").value = '';

                document.getElementById("amount").value = '';

                document.getElementById("paid_amount").removeAttribute("readonly");
                document.getElementById("paid_amount").value = '';
                document.getElementById("amount_paid_existing").value = '';
                document.getElementById("outstanding_amount").value = '';
                document.getElementById("outstanding_amount_existing").value = '';

             }
             else{
                document.getElementById("service_provider").setAttribute("readonly", "readonly");
                document.getElementById("service_taken").setAttribute("readonly", "readonly");
                document.getElementById("amount").setAttribute("readonly", "readonly");
                // document.getElementById("paid_amount").setAttribute("readonly", "readonly");
             }
        }
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
        $(document).ready(() => {
            const amountInput = document.getElementById('amount');
            const outstandingInput = document.getElementById('outstanding_amount');
            const outstandingExistingInput = document.getElementById('outstanding_amount_existing');
            const paidAmountInput = document.getElementById('paid_amount');
            const registrationForm = document.getElementById('registration_form');

    // Ensure both fields exist before adding event listeners
    if (amountInput && paidAmountInput && registrationForm) {
        // Function to validate the paid amount
        function getAllowedAmount() {
            const outstanding = parseFloat(outstandingInput?.value);
            const outstandingExisting = parseFloat(outstandingExistingInput?.value);
            if (!isNaN(outstanding) && outstanding > 0) {
                return outstanding;
            }
            if (!isNaN(outstandingExisting) && outstandingExisting > 0) {
                return outstandingExisting;
            }
            return parseFloat(amountInput.value) || 0;
        }

        function validatePaidAmount(showAlert = false) {
            const paidAmount = parseFloat(paidAmountInput.value) || 0;
            const allowedAmount = getAllowedAmount();

            // Check if Paid Amount is greater than Amount to Pay
            if (paidAmount > allowedAmount) {
                const message = `Amount Paying should not exceed  ${allowedAmount.toFixed(2)} (Outstanding)git status.`;
                paidAmountInput.setCustomValidity(message);
                paidAmountInput.classList.add('is-invalid'); // Add invalid class for styling
                if (showAlert) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Oops!',
                            text: message
                        });
                    } else {
                        alert(message);
                    }
                }
                return false;
            } else {
                paidAmountInput.setCustomValidity(''); // Clear custom validity
                paidAmountInput.classList.remove('is-invalid'); // Remove invalid class if valid
                return true;
            }
        }

        // Attach validation on input events
        amountInput.addEventListener('input', validatePaidAmount);
        paidAmountInput.addEventListener('input', validatePaidAmount);
        outstandingInput?.addEventListener('input', validatePaidAmount);
        registrationForm.addEventListener('submit', function (event) {
            if (!validatePaidAmount(true)) {
                event.preventDefault();
            }
        });

        // Initialize validation in case there is pre-filled data
        validatePaidAmount();
    }

    // Optionally add console logs to check if things are working correctly
    console.log("Validation Script Loaded");

        });

    </script>
    <script>
        function deleteuser(id) {
            var conf = confirm('Delete User');
            if (conf == true) {
                window.location.href = "delete_user/" + id + "";
            }
        }
    </script>

    @if (session()->has('deleted'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'User Deleted Successfully!'
            })
        </script>
    @endif

@endsection()
