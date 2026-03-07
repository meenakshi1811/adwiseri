@extends('admin.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
            <div class="client-dashboard">
                <div class="client-btn d-flex justify-content-between mb-4">
                    <h3 class="text-primary text-center flex-grow-1 text-center m-0">Wallets</h3>
                </div>
                <div class="row m-0 pb-2">

                  <div class="col-6 border p-1 text-center top_modules tab-anchor" onclick="window.location.href = '{{ route('admin_referral') }}';">
                    Referrals
                  </div>
                  <div class="col-6 border p-1 text-center bg-info text-white tab-anchor">
                    Wallets
                  </div>
                </div>

                <div class="table-wrapper mt-3">
                    <table class="fl-table table table-hover p-0 m-0" id="userTable">
                        <thead>
                        <tr>
                            <th class="p-1 text-center">Sr.No.</th>

                            <th class="p-1 text-start"> Wallet_TransID</th>
                            <th class="p-1 text-start"> Subscriber(SUB_ID)</th>
                            <th class="p-1 text-start">Transaction Type </th>
                            <th class="p-1 text-start"> Description</th>
                            <th class="p-1 text-start">Transaction Amount (USD) </th>
                            <th class="p-1 text-start"> Previous Balance (USD) </th>
                            <th class="p-1 text-start"> New Balance (USD)</th>
                            <th class="p-1 text-start">Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($referrals as $key => $ref)
                        <tr>
                            <td class="p-1 text-center">{{ $key+1 }}</td>
                            <td class="p-1 text-center">{{ $ref->id }}</td>
                            @if(in_array($ref->type, ['cashback', 'one_off', 'double_term']))
                           <td class="p-1">{{ $ref->user ? $ref->user->name.'('.$ref->user->id.')' :'' }}</td>
                            @else
                            <td class="p-1">{{ $ref->getRefferedByUser ? $ref->getRefferedByUser->id :'' }}</td>
                            @endif
                            {{-- <td class="p-1" style="position: relative;">@if(strlen($ref->user_name) > 15){{ substr($ref->user_name, 0, 15) }}... <span onmouseover="this.style.opacity='1';" onmouseout="this.style.opacity='0';" style="display:flex;opacity:0;align-items:center;padding:5px;position: absolute;left:0px;top:25px;height:100%;background:lightgrey;min-width:100%; width:fit-content;">{{$ref->user_name}} ({{$ref->userid}})</span> @else {{$ref->user_name}} ({{$ref->userid}})@endif</td> --}}
                            <td class="p-1">
                                @php
                                $wallet_balance = round($ref->wallet_balance,2) ?? 0;
                                $previous_balance = round($ref->previous_balance,2) ?? 0;
                            @endphp

                            @if ($wallet_balance > 0 && $wallet_balance > $previous_balance)
                                +  {{ $wallet_balance - $previous_balance }}
                            @elseif ($previous_balance > 0 && $wallet_balance < $previous_balance)
                                - {{ $previous_balance - $wallet_balance }}
                            @else
                                0
                            @endif

                            </td>
                            <td class="p-1">
                                @php
                                // Mapping types to their corresponding display names
                                $displayText = '';
                                switch ($ref->type) {
                                    case 'cashback':
                                        $displayText = 'Cashback';
                                        break;
                                    case 'one_off':
                                        $displayText = 'One-off Credit';
                                        break;
                                    case 'double_term':
                                        $displayText = 'Double the Subscription term';
                                        break;
                                    default:
                                        $displayText = $ref->type;
                                }
                            @endphp
                            {{ $displayText }}

                            </td>


                            <td class="p-1">{{ round($ref->amount_added,2) }}</td>

                            <td class="p-1">{{ round($ref->previous_balance,2) }}</td>
                            <td class="p-1">{{ round($ref->wallet_balance,2) }}</td>
                            <td class="p-1">{{ date("d-m-Y", strtotime($ref->created_at)) }}</td>
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
<script>

</script>

@if(session()->has('amount_added'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Congratulations',
        text: 'Amount added Successfully.'
    })
</script>
@endif

@endsection()
