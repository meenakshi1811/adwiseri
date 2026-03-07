@extends('admin.layout.main')

@section('main-section')
    <style>
        .error {
            border: 2px red solid !important;
        }
        #affiliateReferalTable th, #affiliateReferalTable td {
        text-align: center;
        vertical-align: middle;
        }
        #affiliateReferalTable th {
            font-weight: bold;
        }
    </style>
    <div class="col-lg-10 column-client">
        <div class="client-dashboard">
            <div class="client-btn d-flex justify-content-between mb-4">

                <h3 class="text-primary text-center flex-grow-1 text-center m-0">Affiliates Wallets</h3>

            </div>
            <div class="row m-0 pb-2">
                <div class="col-4 border p-1 text-center tab-anchor " onclick="window.location.href = '{{ route('affiliates') }}';">
                    Affiliates
                </div>
                <div class="col-4 border p-1 text-center  tab-anchor" onclick="window.location.href = '{{ route('affiliates_referrals') }}';">
                    Affiliates Referrals
                </div>
                <div class="col-4 border p-1 text-center bg-info text-white tab-anchor" onclick="window.location.href = '{{ route('affiliates_wallet') }}';">
                    Affiliates Wallets
                </div>
              </div>

        </div>
        <div class="table-wrapper mt-3">
            <table class="fl-table table table-hover p-0 m-0" id="affiliateReferalTable">
                <thead>
                <tr>
                    <th class="p-1 text-center">Sr.No.</th>
                    <th class="p-1 text-center">Affiliate Name(id)</th>
                    <th class="p-1 text-center">Transaction Amount (Cr/Dr)</th>
                    <th class="p-1 text-center">Description </th>
                    <th class="p-1 text-center">Previous Balance</th>
                    <th class="p-1 text-center">New Balance</th>
                    <th class="p-1 text-center">Transaction Date</th>

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

            var dataTable = $('#affiliateReferalTable').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: true,
                    "lengthMenu": [
                        [10, 20, 50, -1],
                        [10, 20, 50, "All"]
                    ],
                    "pageLength": 10,
                    destroy: true,
                    buttons: [],
                    ajax: {
                        url: "{{ route('affiliateWalletReportAdmin') }}",
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
                            data: 'affName',
                            name: 'affName',


                        },
                        {
                            data: 'transaction',
                            name: 'transaction',
                            orderable: false,
                        },
                        {
                            data:'type',
                            name:'type',
                        },
                        {
                            data:'previous_balance',
                            name:'previous_balance',


                        },
                        {
                            data:'wallet_balance',
                            name:'wallet_balance',

                        },
                        {
                            data:'transactionDate',
                            name:'transactionDate',

                        },


                    ],


                });
         });

    </script>
@endpush
