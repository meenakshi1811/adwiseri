@extends('admin.layout.main')
@php

use App\Models\User;
@endphp
@section('main-section')

        <div class="col-lg-10 column-client">
            <div class="client-dashboard">
                <div class="client-btn d-flex justify-content-between mb-4">
                    <h3 class="text-primary text-center flex-grow-1 text-center m-0"> Referrals</h3>

                </div>
                <div class="row m-0 pb-2">

                    <div class="col-6 border p-1 text-center bg-info text-white tab-anchor">
                      Referrals
                    </div>
                    <div class="col-6 border p-1 text-center top_modules tab-anchor" onclick="window.location.href = '{{ route('admin_wallet') }}';">
                        Wallets
                    </div>
                </div>

                <div class="table-wrapper mt-3">
                    <table class="fl-table table table-hover p-0 m-0" id="userTable">
                        <thead>
                        <tr>
                            <th class="p-1 text-center">Sr.No.</th>

                            <th class="p-1 text-center">Referred By(Sub_ID)</th>
                            <th class="p-1 text-center">Referred To(Sub_ID)</th>
                            <th class="p-1 text-center">Referral Code</th>
                            <th class="p-1 text-center">Sub_Plan</th>
                            <th class="p-1 text-center"> Amount_Paid (USD)</th>

                            <th class="p-1 text-center"> DOS </th>
                            <th class="p-1 text-center">Commission (USD)</th>
                            <th class="p-1 text-center">Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($referrals as $key => $ref)
                        <tr>
                            <td class="p-1 text-center">{{ $key+1 }}</td>
                            <td class="p-1 text-center">{{ User::where('referral',$ref->referral_code)->first()->name }}({{User::where('referral',$ref->referral_code)->first()->id}})</td>
                            <td class="p-1 text-center">{{ $ref->user_name }}({{$ref->userid}})</td>
                            <td class="p-1 text-center">{{ $ref->referral_code }}</td>
                            <td class="p-1 text-center">{{ $ref->user ? $ref->user->membership : '' }}</td>
                            <td class="p-1 text-center">{{ $ref->total_amount }}</td>
                            <td class="p-1 text-center">   {{ $ref->user && $ref->user->membership_start_date && $ref->user->membership_expiry_date
                                ? (\Carbon\Carbon::parse($ref->user->membership_start_date)->diffInYears(\Carbon\Carbon::parse($ref->user->membership_expiry_date)) > 0
                                    ? \Carbon\Carbon::parse($ref->user->membership_start_date)->diffInYears(\Carbon\Carbon::parse($ref->user->membership_expiry_date)) . ' year' . (\Carbon\Carbon::parse($ref->user->membership_start_date)->diffInYears(\Carbon\Carbon::parse($ref->user->membership_expiry_date)) > 1 ? 's' : '')
                                    : '')
                                : '' }} </td>
                            <td class="p-1 text-center">{{ $ref->amount_added }}</td>
                            <td class="p-1 text-center">{{  \Carbon\Carbon::parse($ref->created_at)->format('d-m-Y') }}</td>
                        </tr>
                        @endforeach
                        <tbody>
                    </table>
                </div>
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

@if(session()->has('deleted'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: 'Client Deleted Successfully!'
    })
  </script>

@endif
@endsection()
