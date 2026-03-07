@extends('web.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
            <div class="client-dashboard">
                {{-- <div class="client-btn d-flex justify-content-between mb-3 px-2">
                    <h3 class="text-primary">Wallet</h3> --}}
                    <div class="client-btn d-flex justify-content-between align-items-center mb-3 ">
                        <h3 class="text-primary text-center flex-grow-1 text-center m-0">Wallet</h3>

                    {{-- <a onclick="document.getElementById('update_box').style.display='flex';" class="btn btn-primary p-2 px-4 m-0">Add Amount</a> --}}
                </div>
                <div class="profile-detail">
                    <div class="col-12 profile-data" style="border: 1px solid lightgrey;">
                        {{-- <div class="row">
                            <div class="col-11"></div>
                            <div class="col-1 editss">
                                <img style="cursor: pointer;" onclick="document.getElementById('update_box').style.display='flex';" src="{{ asset('web_assets/images/edit.png') }}"width="20" height="20" alt="">
                            </div>
                        </div> --}}
                        <div class="row det-row">
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-6">
                                        <p style="font-weight:550;font-size:18px;">Wallet Balance</p>
                                    </div>
                                    @if($expiry != null)
                                    <div class="col-6">
                                        <p style="font-size:18px;">Upgrade or Renew membership to View Balance.</p>
                                    </div>
                                    @else
                                    <div class="col-6">
                                        <p style="font-size:18px;">${{ $user->wallet }}</p>
                                    </div>
                                    @endif
                                    <div class="col-6">
                                        <p style="font-weight:550;font-size:18px;">Referral Code</p>
                                    </div>
                                    <div class="col-6">
                                        <p style="font-size:18px;">{{ $user->referral }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-lg-4 profile-pic" style="border: 1px solid lightgrey;">
                        <div class="row">
                            <div class="col-10"></div>
                            <div class="col-2">
                                <img onclick="document.getElementById('update_img_box').style.display='flex';" src="{{ asset('web_assets/images/edit.png') }}"width="20" height="20" alt="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-7 profilepic-row">
                                @if($user->profile_img != "")
                                <img src="{{ asset('web_assets/users/user'.$siteuser->id.'/'.$siteuser->profile_img) }}" width="200" height="200" alt="">
                                @else
                                <img src="{{ asset('web_assets/images/profile.jpg') }}" width="200" height="200" alt="">
                                @endif
                            </div>
                            <div class="col-lg-5"></div>
                        </div>
                    </div> --}}
                </div>
                <div class="table-wrapper mt-3">
                    <table class="fl-table table table-hover p-0 m-0" id="userTable">
                        <thead>
                        <tr>
                            <th class="p-1 text-center">Sr.No.</th>

                            <th class="p-1 text-center"> Wallet_TransID</th>
                            <th class="p-1 text-center"> Subscriber(SUB_ID)</th>
                            <th class="p-1 text-center">Transaction Amount (Cr/Dr) </th>
                            <th class="p-1 text-center"> Description</th>
                            {{-- <th class="p-1 text-start">Transaction Amount (USD) </th> --}}
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
                            {{-- @if(in_array($ref->type, ['cashback', 'one_off', 'double_term']))
                           <td class="p-1 text-center">{{ $ref->user ? $ref->user->name.'('.$ref->user->id.')' :'' }}</td>
                            @else
                            <td class="p-1 text-center">{{ $ref->getRefferedByUser ? $ref->getRefferedByUser->name .'('.$ref->getRefferedByUser->id.')' :''  }}</td>
                            @endif --}}
                            <td class="p-1" style="position: relative;">@if(strlen($ref->user_name) > 15){{ substr($ref->user_name, 0, 15) }}... <span onmouseover="this.style.opacity='1';" onmouseout="this.style.opacity='0';" style="display:flex;opacity:0;align-items:center;padding:5px;position: absolute;left:0px;top:25px;height:100%;background:lightgrey;min-width:100%; width:fit-content;">{{$ref->user_name}} ({{$ref->userid}})</span> @else {{$ref->user_name}} ({{$ref->userid}})@endif</td>
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
                                switch ($ref->type) {
                                    case 'cashback':
                                        $displayText = 'Cashback';
                                        break;
                                    case 'one_off':
                                        $displayText = 'One-off credit';
                                        break;
                                    case 'double_term':
                                        $displayText = 'Double the subscription term';
                                        break;
                                    default:
                                        $displayText = $ref->type;
                                }
                                if($displayText == 'Referral Commission'){
                                    echo $displayText . ' ('.$ref->getRefferedByUser->id.')';
                                }
                                else{
                                    echo $displayText;
                                }
                            @endphp
                            
                                        
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
                <hr>
                {{-- @if($transactions)
                <h4>Wallet Transactions</h4>
                <div class="table-wrapper mt-3">
                    <table class="fl-table table table-hover p-0 m-0" id="usersTable">
                        <thead>
                        <tr>
                            <th class="p-1 text-center">Sr.No.</th>
                            <th class="p-1 text-start">Subscriber Name</th>
                            <th class="p-1 text-start">Purchase Amount</th>
                            <th class="p-1 text-start">Debit Amount</th>
                            <th class="p-1 text-start">Date</th>
                            <th class="p-1 text-start">Pevious Balance</th>
                            <th class="p-1 text-start">New Balance</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($transactions as $key => $ref)
                        <tr>
                            <td class="p-1 text-center">{{ $key+1 }}</td>
                            <td class="p-1" style="position: relative;">@if(strlen($ref->user_name) > 15){{ substr($ref->user_name, 0, 15) }}... <span onmouseover="this.style.opacity='1';" onmouseout="this.style.opacity='0';" style="display:flex;opacity:0;align-items:center;padding:5px;position: absolute;left:0px;top:25px;height:100%;background:lightgrey;min-width:100%; width:fit-content;">{{$ref->user_name}} ({{$ref->userid}})</span> @else {{$ref->user_name}} ({{$ref->userid}})@endif</td>
                            <td class="p-1">{{ $ref->total_amount }}</td>
                            <td class="p-1">{{ ($ref->debit_amount) ? '-' :''}} &nbsp; {{ $ref->debit_amount }}</td>
                            <td class="p-1">{{ $ref->formatted_created_at }}</td>
                            <td class="p-1">{{ $ref->previous_balance }}</td>
                            <td class="p-1">{{ $ref->wallet_balance }}</td>
                        </tr>
                        @endforeach
                        <tbody>
                    </table>
                </div>
                @endif --}}

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
<div id="update_box" style="width:100%;display: none;flex-direction: column;position: fixed;top: 0;left: 0;height: 100vh;overflow: scroll; background: rgba(0, 0, 0, 0.3);">
    <button class="btn btn-danger" style="position:fixed;top:10px;right:20px;" onclick="document.getElementById('update_box').style.display='none';">Close</button>
    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-4 loginouter-box">
            <form class="details-box login-box" method="POST" action="{{ route('add_amount') }}">
            @csrf
            <input type="hidden" name="local_time" class="localtime" />
            <input type="hidden" name="id" value="{{ $subscriber->id }}">
            <input type="hidden" name="profile" value="profile">
                <h3 class="mb-5 pt-3 text-center">Add Amount</h3>
                <div class="log-img mb-5">
                @if($user->profile_img == "")
                <img src="{{ asset('web_assets/images/loginimg.png') }}" width="60" height="60" alt="">
                @else
                    <img src="{{ asset('web_assets/users/user'.$user->id.'/'.$user->profile_img) }}" width="60" height="60" alt="">
                @endif
                </div>
                <div class="mb-4">
                    <input name="amount" required type="number" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Amount">
                </div>
                <button type="submit" class="form-control btn btn-primary mb-4">Add</button>
                <!-- <a href="dashboard.html" class="btn btn-primary mb-4">Next</a> -->
                <!-- <p class="text-center reg-logbtn">Already have an account! <a href="{{ route('login') }}" class="text-dark"> <strong>Login</strong></a></p> -->
            </form>
        </div>
        <div class="col-lg-4"></div>
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
