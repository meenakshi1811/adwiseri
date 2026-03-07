@extends('web.layout.main')

@section('main-section')
    <style>
        body {
            background-color: #F5F5F5;
        }
    </style>
    <div class="membership-page">
        <img src="{{ asset('web_assets/images/membership.png') }}" width="1519" height="440" alt="">
        <h1>Upgrade Subscription</h1>
    </div>
    <br>
    <div class="container mt-5 pt-5 mb-5">
        <h1 class="text-primary text-center">Complete Payment to Upgrade Subscription</h1>
        <div class="col mt-4">
            <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8 loginouter-box">
                    <form id="loginform" class="login-box" method="POST" action="{{ route('make_payment') }}">
                        @csrf
                        <input type="hidden" name="local_time" class="localtime" />
                        <input type="hidden" name="id" value="{{ $user->id }}" />
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <p class="text-dark" style="font-size: 16px;">Subscription Plan</p>
                            </div>
                            <div class="col-md-6">
                                <input type="hidden" name="plan_name" value="{{ $membership->plan_name }}" />
                                <p class="text-dark" style="font-size: 16px;">{{ $membership->plan_name }}</p>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <p class="text-dark" style="font-size: 16px;">Duration</p>
                            </div>
                            <div class="col-md-6">
                                <Select id="plan_duration" name="plan_duration">
                                    <option value="1">1 Year</option>
                                    <option value="2">2 Years</option>
                                    <option value="3">3 Years</option>
                                    <option value="5">5 Years</option>
                                </Select>
                                {{-- <p class="text-dark" style="font-size: 16px;">{{ $membership->price_per_year }} USD</p> --}}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <p class="text-dark" style="font-size: 16px;">Amount (USD)</p>
                            </div>
                            <div class="col-md-6">
                                <input type="number" id="plan_amount" style="display: none;" name="plan_amount" value="{{ $membership->price_per_year }}" />
                                <p class="text-dark" id="plan_amt" style="font-size: 16px;">$ {{ $membership->price_per_year }}</p>
                            </div>
                        </div>
                        @if(!$expired)
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <p class="text-dark" style="font-size: 16px;">Wallet Credit (USD)</p>
                            </div>
                            <div class="col-md-6">
                                <input type="hidden" name="wallet_amount" id="wallet_amount" value="{{ $user->wallet }}" />
                                <p class="text-dark" style="font-size: 16px;" id="wallet_amt">{{ $user->wallet }}</p>
                            </div>
                        </div>
                        @endif
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <p class="text-dark" style="font-size: 16px;">Payable (USD)</p>
                            </div>
                            <div class="col-md-6">
                                <input type="number" id="payable_amount" style="display: none;" name="plan_price" value="{{ $membership->price_per_year }}" />
                                <p class="text-dark" id="payable_amt" style="font-size: 16px;">{{ $membership->price_per_year }} </p>
                            </div>
                        </div>
                        <!-- <div class="row mb-2">
                            <div class="col-md-6">
                                <p class="text-dark" style="font-size: 16px;">Referral Code</p>
                            </div>
                            <div class="col-md-6">
                                <input type="text" maxlength="20" @if (session()->has('error')) class="px-1 is-invalid" @else class="px-1" @endif value="{{ old('referral_code') }}" name="referral_code" placeholder="Enter Referral Code(if any)" />
                                @if (session()->has('error'))
                                <p class="text-danger">Invalid Referral Code</p>
                                @elseif(session()->has('used'))
                                <p class="text-danger">Referral Code already used by you.</p>
                                @endif
                                {{-- <p class="text-dark" style="font-size: 16px;">{{ $membership->price_per_year }} USD</p> --}}
                            </div>
                        </div> -->
                        <div class="row mb-2">
                            <div class="col-md-6">
                            </div>
                            @if($user->wallet > $membership->price_per_year and !$expired)
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <button type="submit" name="wallet_pay" value="wallet" class="btn btn-primary">Pay From Wallet</button>
                                    </div>
                                    {{-- <div class="col-lg-6">
                                        <button type="submit" name="submit" class="btn btn-primary">Pay Securely</button>
                                    </div> --}}
                                </div>
                            </div>
                            @else
                            <div class="col-md-6">
                                <button type="submit" name="submit" class="btn btn-primary">Pay Securely</button>
                            </div>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="col-lg-2"></div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
        @if (isset($user))
            function upgrade_plan(name) {
                var myformData = new FormData();
                myformData.append('id', "{{ $user->id }}");
                myformData.append('plan_name', name);
                myformData.append('_token', "{{ csrf_token() }}");
                console.log(myformData);
                $.ajax({
                    url: "{{ route('upgrade_membership') }}",
                    method: 'POST',
                    data: myformData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (data == "success") {
                            window.location.reload();
                        }
                    }
                });
            }
        @endif
        $(document).ready(function() {
            var pay_amount = {{ $membership->price_per_year}};
                amount = Math.round((pay_amount * 1) * 1);
                var wallet = document.getElementById('wallet_amount');
                $("#plan_amount").attr('value',amount);
                $("#plan_amt").html(amount);
                if(wallet){
                    amount = amount - $("#wallet_amount").val();
                    if(amount <= 0){
                        amount = 0;
                    }
                }
                $("#payable_amount").attr('value',amount);
                $("#payable_amt").html(amount);

            $("#plan_duration").change(function(){
                var duration = $(this).val();
                var amt = {{ $membership->price_per_year }};
                if(duration == 2){
                    amount = Math.round((amt * 2) * 0.9);
                    $("#plan_amount").attr('value',amount);
                    $("#plan_amt").html(amount);
                    if(wallet){
                        amount = amount - $("#wallet_amount").val();
                        if(amount <= 0){
                            amount = 0;
                        }
                    }
                    // console.log(amount);
                    $("#payable_amount").attr('value',amount);
                    $("#payable_amt").html(amount);
                }
                else if(duration == 3){
                    amount = Math.round((amt * 3) * 0.8);
                    $("#plan_amount").attr('value',amount);
                    $("#plan_amt").html(amount);
                    if(wallet){
                        amount = amount - $("#wallet_amount").val();
                        if(amount <= 0){
                            amount = 0;
                        }
                    }
                    // console.log(amount);
                    $("#payable_amount").attr('value',amount);
                    $("#payable_amt").html(amount);
                }
                else if(duration == 5){
                    amount = Math.round((amt * 5) * 0.5);
                    $("#plan_amount").attr('value',amount);
                    $("#plan_amt").html(amount);
                    if(wallet){
                        amount = amount - $("#wallet_amount").val();
                        if(amount <= 0){
                            amount = 0;
                        }
                    }
                    // console.log(amount);
                    $("#payable_amount").attr('value',amount);
                    $("#payable_amt").html(amount);
                }
                else {
                    amount = Math.round((amt * 1) * 1);
                    $("#plan_amount").attr('value',amount);
                    $("#plan_amt").html(amount);
                    if(wallet){
                        amount = amount - $("#wallet_amount").val();
                        if(amount <= 0){
                            amount = 0;
                        }
                    }
                    // console.log(amount);
                    $("#payable_amount").attr('value',amount);
                    $("#payable_amt").html(amount);
                }
            })
        });
    </script>

    {{-- @if (session()->has('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Invalid Refferal Code'
            })
        </script>
    @endif --}}

@endsection()
