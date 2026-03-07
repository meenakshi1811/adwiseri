@extends('admin.layout.main')

@section('main-section')
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
    #referalTable th, #referalTable td {
        text-align: center;
        vertical-align: middle;
    }

    #referalTable th {
        font-weight: bold;
    }
</style>
    </style>
    <div class="col-lg-10 column-client">
        <div class="client-dashboard">
            <div class="client-btn d-flex mb-2 ">
                {{-- <form class="form-inline d-flex justify-content-between w-100">
                    <h3 class="text-primary">Affiliates</h3> --}}
                    <h3 class="text-primary text-center flex-grow-1 text-center m-0">Affiliates</h3>
                    <p class="mt-2">
                        <a id="payCommission" class="btn btn-outline-success login-btn">Add Payout</a>
                        <a href="javascript:void(0)" id="addAffiliate" class="btn btn-outline-success login-btn">Add Affiliate</a>

                      {{-- <a href="{{ route('applications_export') }}" class="m-0">Export</a>
                      <a href="{{ route('new_application') }}" class="m-0">Add New</a> --}}
                    </p>
                    {{-- <div class="d-flex ">
                        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                    </div> --}}
                  {{-- </form> --}}
                  {{-- <i class="fa-solid fa-magnifying-glass"></i> --}}
            </div>

            {{-- <h3 class="text-primary px-3">Affiliates</h3>

            <p class>
                <button id="payCommission" class="btn btn-outline-success login-btn">Add Payout</button>
                <button id="addAffiliate" class="btn btn-outline-success login-btn">Add Affiliate</button>
            </p> --}}
            @if ($errors->has('msg'))
                <div class="alert alert-danger">
                    {{ $errors->first('msg') }}
                </div>
            @endif
            @if (session('msg'))
                <div class="alert alert-success">
                    {{ session('msg') }}
                </div>
            @endif
            <div class="row m-0 pb-2">
                <div class="col-4 border p-1 text-center bg-info text-white tab-anchor"
                    onclick="window.location.href = '{{ route('affiliates') }}';">
                    Affiliates
                </div>
                <div class="col-4 border p-1 text-center tab-anchor"
                    onclick="window.location.href = '{{ route('affiliates_referrals') }}';">
                    Affiliates Referrals
                </div>
                <div class="col-4 border p-1 text-center tab-anchor"
                    onclick="window.location.href = '{{ route('affiliates_wallet') }}';">
                    Affiliates Wallets
                </div>
            </div>
            <div class="client-btn d-flex justify-content-between mb-4">


                <!-- The Modal -->
                <div id="myModal" class="modal">

                    <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <h5 class="text-primary">Pay Commission <span class="close">&times;</span></h5>
                        <hr>
                        <form action="{{ route('affiliateCommissionPaid') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="affiliateID" class="form-label">Select Affiliate</label>
                                <select onchange="onClickAffiliate(this)" class="form-select" name="affiliateID" id="affiliateID">
                                    <option value="">Select Affiliate</option>
                                    @foreach ($affiliates as $item)
                                        <option value="{{ $item->getAffiliate->id }}">
                                            {{ $item->getAffiliate->name }} ({{ $item->getAffiliate->id }}) --  USD ({{ $item->wallet}})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="commissionearnt" class="form-label">Total</label>
                                <input type="text" class="form-control" name="commissionearnt" id="commissionearnt" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="commissionpaid" class="form-label">Paid</label>
                                <input type="text" class="form-control" name="commissionpaid" id="commissionpaid" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="commissionpay" class="form-label">Commission to Pay</label>
                                <input type="number" class="form-control"  name="commissionpay"min="0.00001" step="0.00001"  id="commissionpay">
                                <p id="pending_amount" class="text-muted mt-2"></p>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Add Payout Record</button>
                            </div>
                        </form>





                    </div>
                    </div>

                </div>
                <!-- The Modal -->
                <div id="myModalAffiliate" class="modal">

                    <!-- Modal content -->
                    <div class="modal-content">
                        <h5 class="text-primary">Add Affiliate <span class="close">&times;</span></h5>
                        <hr>
                        <form id="loginform" method="POST" action="{{ route('Affiliates.store') }}">
                            @csrf
                            <input type="hidden" name="local_time" class="localtime" />
                            <input name="terms" type="hidden" value="admin" required>
                            <input name="url_model" type="hidden" value="affiliates" required>

                            <div class="row">
                                <div class="mb-4 col-md-3">
                                    <input type="text" name="name" required
                                        class="form-control @error('name') is-invalid @enderror" id="exampleInputname1"
                                        aria-describedby="nameHelp" value="{{ old('name') }}" placeholder="Name">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <!---  <img src="{{ asset('web_assets/images/user.png') }}" width="20" height="20" class="useimg" alt=""> --->
                                </div>
                                <div class="mb-4 col-md-3">
                                    <input type="tel" name="phone" required
                                        class="form-control @error('phone') is-invalid @enderror" id="exampleInputphone1"
                                        aria-describedby="phoneHelp" value="{{ old('phone') }}" placeholder="Phone">
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <!---  <img src="{{ asset('web_assets/images/user.png') }}" width="20" height="20" class="useimg" alt=""> --->
                                </div>

                                <div class="mb-4 col-md-3">
                                    <input type="email" name="email" required
                                        class="form-control @error('email') is-invalid @enderror" id="exampleInputEmail1"
                                        aria-describedby="emailHelp" value="{{ old('email') }}" placeholder="Email">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <!---  <img src="{{ asset('web_assets/images/user.png') }}" width="20" height="20" class="useimg" alt=""> --->
                                </div>
                                <div class="mb-4 col-md-3">
                                    <input type="password" name="password" required
                                        class="form-control @error('password') is-invalid @enderror"
                                        id="exampleInputpassword1" aria-describedby="passwordHelp"
                                        value="{{ old('password') }}" placeholder="Password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <!---  <img src="{{ asset('web_assets/images/user.png') }}" width="20" height="20" class="useimg" alt=""> --->
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-4 col-md-4">
                                    <select name="type" class="form-select" id="">
                                        <option value="" selected>Select Type</option>
                                        <option value="Individual">Individual</option>
                                        <option value="Agency">Agency</option>
                                        <option value="Corporate">Corporate</option>
                                    </select>
                                    @error('type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <!---  <img src="{{ asset('web_assets/images/user.png') }}" width="20" height="20" class="useimg" alt=""> --->
                                </div>
                                <div class="mb-4  col-md-4">


                                    <select name="country" id="country" required class="form-select"
                                        aria-label="Default select example">
                                        <option selected value="">Country</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->country_name }}">{{ $country->country_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('country')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <!---  <img src="{{ asset('web_assets/images/user.png') }}" width="20" height="20" class="useimg" alt=""> --->
                                </div>
                                <div class="mb-4  col-md-4">
                                    <input type="text" name="city" required
                                        class="form-control @error('city') is-invalid @enderror" id="exampleInputcity1"
                                        aria-describedby="cityHelp" value="{{ old('city') }}" placeholder="City/Town">
                                    @error('city')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <!---  <img src="{{ asset('web_assets/images/user.png') }}" width="20" height="20" class="useimg" alt=""> --->
                                </div>
                            </div>



                            <button type="submit" class="btn btn-primary form-control mt-2">Submit</button>

                        </form>




                    </div>

                </div>

            </div>
        </div>
        <div class="table-wrapper mt-3">
            <table class="fl-table table table-hover p-0 m-0" id="referalTable">
                <thead>
                    <tr>
                        <th class="p-1 text-center">Sr.No.</th>
                        <th class="p-1 text-center">Affiliate Name(id)</th>
                        <th class="p-1 text-center">Referral Code</th>
                        <th class="p-1 text-center">Type</th>
                        <th class="p-1 text-center">Country</th>
                        <th class="p-1 text-center">City/Town</th>
                        <th class="p-1 text-center">CE</th>
                        <th class="p-1 text-center">Activation Date</th>
                        <th class="p-1 text-center">Status</th>
                        <th class="p-1 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>

                <tbody>
            </table>
        </div>
    </div>
    </div>

    </div>
@endsection
    @push('scripts')
    <script>
         $(document).ready(function () {



            var dataTable = $('#referalTable').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ordering: true,
                info: false,

                "lengthMenu": [
                    [10, 20, 50, -1],
                    [10, 20, 50, "All"]
                ],
                "pageLength": 10,
                destroy: true,
                buttons: [],
                ajax: {
                    url: "{{ route('affiliateReportAdmin') }}",
                    data: function(d) {
                        // Add additional data here
                        d.type = 'category';


                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name',


                    },
                    {
                        data: 'referral',
                        name: 'referral',
                        orderable: false,
                    },
                    {
                        data: 'type',
                        name: 'type',
                        orderable: false,
                    },
                    {
                        data: 'country',
                        name: 'country',
                        orderable: false,
                    },
                    {
                        data: 'city',
                        name: 'city',
                        orderable: false,
                    },



                    {
                        data: 'commission',
                        name: 'commission',
                        orderable: false,
                        render: function(data) {
                            return '$' + data;
                        }
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',

                    },
                    {
                        data: 'get_affiliate.status',
                        name: 'get_affiliate.status',
                        render: function(data) {
                            if (data == 1) {
                                return 'Active';
                            }
                            return 'Deactive';
                        },
                    },
                    {
                        data: 'action',
                        name: 'action',
                    }

                ],


            });
        });

        function onClickAffiliate(affiliate) {
            console.log(affiliate.value);
            $.ajax({
                url: "{{ route('getCommision') }}",
                type: 'get',
                dataType: "json",
                data: {
                    affiliateid: affiliate.value,
                },
                success: function(data) {
                    // log response into console
                    if (data.data) {
                        if (data.data.total_earned > 0) {
                            const availableBalance = (data.data.total_earned - data.data.paid_amount).toFixed(2);

                            $('#commissionearnt').val('$ ' + data.data.total_earned);
                            $('#commissionpaid').val('$ ' + data.data.paid_amount);
                            $('#commissionpay').val(0.00);
                            $('#pending_amount').html("Available Balance to pay $ " + availableBalance);
                            $('#commissionpay').attr('max', availableBalance);
                        } else {

                            $('#commissionearnt').val('$ 0');
                            $('#commissionpaid').val('$ 0');
                            $('#commissionpay').val('0');
                            $('#pending_amount').html("");
                        }
                    } else {
                        $('#commissionearnt').val('$ 0');
                        $('#commissionpaid').val('$ 0');
                        $('#commissionpay').val('0');
                        $('#pending_amount').html("");

                    }

                }
            });

        }

        function clickStatus(status) {

            $.ajax({
                url: "{{ route('changeAffiliateStatus') }}",
                type: 'get',
                dataType: "json",
                data: {
                    id: status,
                },
                success: function(data) {
                    // log response into console
                    Swal.fire("Status changed successfully!");
                    $('#referalTable').DataTable().ajax.reload();

                }
            });
        }





        // Get the modal
        var modal = document.getElementById("myModal");
        var modalAffiliate = document.getElementById("myModalAffiliate");

        // Get the button that opens the modal
        var btn = document.getElementById("payCommission");
        var btnAffiliate = document.getElementById("addAffiliate");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];
        var spanClose = document.getElementsByClassName("close")[1];

        // When the user clicks the button, open the modal
        btn.onclick = function() {
            modal.style.display = "block";
        }
        btnAffiliate.onclick = function() {
            modalAffiliate.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }
        spanClose.onclick = function() {
            modalAffiliate.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
  @endpush
