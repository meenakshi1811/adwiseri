@extends('web.layout.main')

@section('main-section')

    <div class="col-lg-10 column-client">
            <div class="client-dashboard">
                <div class="client-btn d-flex mb-2 ">
                    <form class="form-inline d-flex justify-content-between w-100">
                        <h3 class="text-primary">Add Invoice (AP) Record</h3>
                    </form>
                </div>
                <div class="col">
                    <form id="registration_form" class="register-box login-box" method="POST"
                        action="{{ route('create_new_invoice_ap') }}" onsubmit="document.getElementById('invoice_submit').setAttribute('disabled','true');">
                        @csrf
                        <input type="hidden" name="local_time" class="localtime" />
                        <div class="row">
                            <div class="col-md-4 p-1">
                                <label>Invoice ID<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="invoice_vendor_id" type="text" minlength="2" maxlength="100" required
                                    class="form-control @error('invoice_vendor_id') is-invalid @enderror"
                                    value="{{ old('invoice_vendor_id') }}" placeholder="Invoice ID">
                                @error('invoice_vendor_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Vendor Name<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="vendor_name" id="vendor_name" type="text" minlength="2" maxlength="150" required
                                    class="form-control @error('vendor_name') is-invalid @enderror"
                                    value="{{ old('vendor_name') }}" placeholder="Vendor Name">
                                @error('vendor_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Product/Service Taken<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="service_taken" id="service_taken" type="text" minlength="2" maxlength="200" required
                                    class="form-control @error('service_taken') is-invalid @enderror"
                                    value="{{ old('service_taken') }}" placeholder="Product/Service Taken">
                                @error('service_taken')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Amount To Pay<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="amount" required type="number" min="0"
                                    class="form-control @error('amount') is-invalid @enderror" id="exampleInputEmail1"
                                    aria-describedby="emailHelp" value="{{ old('amount') }}" placeholder="Amount"
                                    autocomplete="amount">
                                @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            {{-- <div class="col-md-4 p-1">
                                <label>Discount</label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="discount" type="number"
                                    class="form-control @error('discount') is-invalid @enderror" id="exampleInputEmail1"
                                    aria-describedby="emailHelp" value="{{ old('discount') }}" required
                                    placeholder="Discount" autocomplete="discount">
                                @error('discount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Tax (%)</label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="tax" type="number" min=0 max="100"
                                    class="form-control @error('tax') is-invalid @enderror" id="exampleInputEmail1"
                                    aria-describedby="emailHelp" value="{{ old('tax') }}" required
                                    placeholder="tax percent(%)" autocomplete="tax">
                                @error('tax')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div> --}}
                            <div class="col-md-4 p-1">
                                <label>Invoice Status<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <select name="status" id="status"
                                    class="form-control form-select @error('status') is-invalid @enderror" id="exampleInputEmail1"
                                    aria-describedby="emailHelp" required>
                                    <option value="">Select Status</option>
                                    <option {{ (old('status') == "PartiallyPaid") ? 'selected' : ''}} value="PartiallyPaid">PartiallyPaid</option>
                                    <option {{(old('status') == "UnPaid") ? 'selected':''}} value="UnPaid">UnPaid</option>
                                    <option {{(old('status') == "Paid") ? 'selected':''}} value="Paid">Paid</option>
                                    <option {{(old('status') == "Cancelled") ? 'selected':''}} value="Cancelled">Cancelled</option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                                <label>Payment Due Date<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-8 p-1">
                                <input name="due_date" type="text" required min="{{date('d-m-Y')}}"
                                    class="form-control date @error('due_date') is-invalid @enderror"
                                    id="exampleInputEmail1" aria-describedby="emailHelp"
                                      value="{{ old('due_date', date('Y-m-d')) }}"
                                      
                                         autocomplete="due_date"
                                    min={{ date('Y-m-d')}}
                                    required
                                   onfocus="(this.type='date')"
                                    onblur="(this.type='text')" />

                                   
                                @error('due_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 p-1">
                            </div>
                            <div class="col-md-4 text-left p-1">
                                <button type="submit" id="invoice_submit" class="form-control btn btn-primary"
                                    style="width: fit-content;">Submit</button>
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
         document.addEventListener("DOMContentLoaded", function () {
            let dueDateInput = document.getElementsByName("due_date");

            // If there's no old value, set the current date
            if (!dueDateInput.value) {
                let today = new Date();
                let formattedDate = today.toISOString().split('T')[0]; // Format: YYYY-MM-DD
                dueDateInput.value = formattedDate;
            }
        });
        $(document).ready(() => {

            $("#country").change(function(){
            var country = $(this).val();
            // console.log(counrty);
            $.ajax({
                url: 'get_states',
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    country: country,
                },
                cache:false,
                success: function(data){
                  console.log(data);
                    $("#state").html(data);
                }
            });
          });
          $("#subscriber").change(function(){
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
                cache:false,
                success: function(data){
                  console.log(data);
                    $("#job_role").html(data);
                }
            });
          });
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
