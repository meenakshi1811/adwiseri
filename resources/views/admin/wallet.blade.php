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

                            <th class="p-1 text-center"> Wallet_Trans (ID)</th>
                            <th class="p-1 text-center"> Subscriber(SUB_ID)</th>
                            <th class="p-1 text-center">Transaction Amount (Cr/Dr) </th>
                            <th class="p-1 text-center"> Description</th>
                            {{-- <th class="p-1 text-center">Transaction Amount (USD) </th> --}}
                            <th class="p-1 text-center"> Previous Balance (USD) </th>
                            <th class="p-1 text-center"> New Balance (USD)</th>
                            <th class="p-1 text-center">TransactionDate</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($referrals as $key => $ref)
                        <tr>
                            <td class="p-1 text-center">{{ $key+1 }}</td>
                            <td class="p-1 text-center">{{ $ref->id }}</td>
                           {{--  @if(in_array($ref->type, ['cashback', 'one_off', 'double_term']))
                           <td class="p-1 text-center">{{ $ref->user ? $ref->user->name.'('.$ref->user->id.')' :'' }}</td>
                            @else   --}}
                            <td class="p-1 text-center">{{ $ref->getRefferedByUser ? $ref->getRefferedByUser->name .'('.$ref->getRefferedByUser->id.')' :''  }}</td>
                           {{--   @endif  --}}
                            {{-- <td class="p-1" style="position: relative;">@if(strlen($ref->user_name) > 15){{ substr($ref->user_name, 0, 15) }}... <span onmouseover="this.style.opacity='1';" onmouseout="this.style.opacity='0';" style="display:flex;opacity:0;align-items:center;padding:5px;position: absolute;left:0px;top:25px;height:100%;background:lightgrey;min-width:100%; width:fit-content;">{{$ref->user_name}} ({{$ref->userid}})</span> @else {{$ref->user_name}} ({{$ref->userid}})@endif</td> --}}
                            <td class="p-1 text-center">
                                @php
                                $wallet_balance = round($ref->wallet_balance,2) ?? 0;
                                $previous_balance = round($ref->previous_balance,2) ?? 0;
                            @endphp

                                @if ($wallet_balance > 0 && $wallet_balance > $previous_balance)
                                +  {{ round(($wallet_balance - $previous_balance),2) }}
                                @elseif ($previous_balance > 0 && $wallet_balance < $previous_balance)
                                - {{ round(($previous_balance - $wallet_balance),2) }}
                                @else
                                0
                                @endif

                            </td>

                            <td class="p-1 text-center">
                                @php
                                // Mapping types to their corresponding display names
                                $displayText = '';
                                if($ref->type == 'cashback' && $ref->offer){

                                    $displayText = isset($ref->offer)
                                        ? 'Cashback (' .number_format($ref->offer->discount_value,0) . '%)'
                                        : 'Cashback';
                                }elseif( ($ref->type == 'one_off' && $ref->offer)){

                                        $displayText = isset($ref->offer)
                                            ? 'One-off (' .number_format( $ref->offer->discount_value,0) . ' USD)'
                                            : 'One-off';
                                }elseif($ref->type ==   'double_term'){
                                        $displayText = 'Double the subscription term';
                                }else{
                                    $displayText = $ref->type;
                                }

                            @endphp
                                        {{ $displayText }}
                            </td>


                            {{-- <td class="p-1">{{ round($ref->amount_added,2) }}</td> --}}

                            <td class="p-1 text-center">{{ round($ref->previous_balance,2) }}</td>
                            <td class="p-1 text-center">{{ round($ref->wallet_balance,2) }}</td>
                            <td class="p-1 text-center">{{ date("d-m-Y", strtotime($ref->created_at)) }}</td>
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
