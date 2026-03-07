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
                <h3 class="text-primary text-center flex-grow-1 text-center m-0">Affiliates Referrals</h3>
            </div>
            <div class="row m-0 pb-2">
                <div class="col-4 border p-1 text-center  tab-anchor" onclick="window.location.href = '{{ route('affiliates') }}';">
                    Affiliates
                </div>
                <div class="col-4 border p-1 text-center bg-info text-white tab-anchor" onclick="window.location.href = '{{ route('affiliates_referrals') }}';">
                    Affiliates Referrals
                </div>
                <div class="col-4 border p-1 text-center tab-anchor" onclick="window.location.href = '{{ route('affiliates_wallet') }}';">
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
                    <th class="p-1 text-center">Referral Code</th>
                    <th class="p-1 text-center">Subscriber Name(id)</th>
                    <th class="p-1 text-center">Plan</th>
                    <th class="p-1 text-center"> DOS</th>
                    <th class="p-1 text-center">Amount Paid(USD)</th>
                    <th class="p-1 text-center">Payment Date</th>
                    <th class="p-1 text-center">Commission Earnt</th>
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
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script>
         $(document).ready(function () {

            var dataTable = $('#affiliateReferalTable').DataTable({
                    processing: true,
                    serverSide: true,

                    "lengthMenu": [
                        [10, 20, 50, -1],
                        [10, 20, 50, "All"]
                    ],
                    "pageLength": 10,
                    destroy: true,
                    // dom: 'Blfrtip',
                    ajax: {
                        url: "{{ route('affiliateReferralReportAdmin') }}",
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
                            data: 'referral_code',
                            name: 'referral_code',
                        },
                        {
                            data: 'sub',
                            name: 'sub',
                        },
                        {
                            data:'plan',
                            name:'plan',
                        },
                        {
                            data:'membership_duration',
                            name:'membership_duration',
                        },
                        {
                            data:'total_amount',
                            name:'total_amount',
                            render:function(data){
                                return '$'+data;
                            }
                        },
                        {
                            data:'created_at',
                            name:'created_at',

                        },
                        {
                            data:'amount_added',
                            name:'amount_added',
                            render:function(data){
                                return '$'+data;
                            }
                        },

                    ],


                });
         });

    </script>
@endpush
