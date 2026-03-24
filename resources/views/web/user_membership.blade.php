@extends('web.layout.main')

@section('main-section')
@php

use App\Models\UserRoles;
$client_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Clients')->first();
$application_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Applications')->first();
$communication_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Communication')->first();
$invoice_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Invoices')->first();
$payment_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Payments')->first();
$report_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Reports')->first();
$subscription_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Subscription')->first();
$setting_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Settings')->first();
$support_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Support')->first();
@endphp

        <div class="col-lg-10 column-client">
            <div class="client-dashboard">
                <div class="client-btn d-flex justify-content-between mb-3 px-2">
                    <div>
                        <h3 class="text-primary m-0">{{ $myplan->plan_name }} Plan @if((new DateTime("now")) > (new DateTime($subscriber->membership_expiry_date))) Plan Expired @endif</h3>
                        <span class="p-0 m-0" style="font-size: 14px;">@if((new DateTime("now")) > (new DateTime($subscriber->membership_expiry_date))) Plan Expired @else Expires @endif on : {{ date("d-m-Y",strtotime($user->membership_expiry_date)) }}</span>
                    </div>
                    <div>
                        @if($myplan->plan_name != "Enterprise")
                            <a @if($user->user_type == "Subscriber") href="{{ route('membership') }}" @else href="#" @endif class="btn btn-primary p-2 px-4 m-0" style="height: fit-content">Upgrade Plan</a>
                        @endif
                        <!-- <a @if($user->user_type == "Subscriber") href="{{ route('membership') }}" @else href="#" @endif class="btn btn-primary" style="height: fit-content">Renew Subscription</a> -->
                        @php
                            $showRenewButton = false;
                            $today = \Carbon\Carbon::now();
                            $expiryDate = \Carbon\Carbon::parse($subscriber->membership_expiry_date);
                            $isPaidPlan = strtoupper(trim($myplan->plan_name ?? '')) !== 'FREE';

                            $daysBeforeExpiry = $today->diffInDays($expiryDate, false); // Negative = expired

                            if ($isPaidPlan && $daysBeforeExpiry <= 60 && $daysBeforeExpiry >= -30) {
                                $showRenewButton = true;
                            }
                        @endphp

                        @if($user->user_type == "Subscriber")
                            @if($showRenewButton)
                                <a href="{{ route('membership_renewal', ['renew' => true]) }}" class="btn btn-primary" style="height: fit-content">
                                    Renew Subscription
                                </a>
                            @endif
                        @endif

                        @if($user->user_type == "Subscriber")
                            <a href="{{ route('download_all_data') }}" class="btn btn-outline-primary" style="height: fit-content">
                                Download All Data
                            </a>
                        @endif

                    </div>
                </div>
                <div class="alert alert-info mb-3" role="alert">
                    After subscription expiry or termination, subscriber data remains available for up to <strong>60 days</strong>.
                    During this retention period, you can use <strong>Download All Data</strong> to download a compressed archive containing Clients, Applications, Users (Staff), Invoices, Payments, Communications, and all uploaded Documents.
                    Documents are organized as <strong>ClientName - Application</strong> folders. After 60 days without renewal, related subscriber data and documents are permanently deleted.
                </div>

                <div class="profile-detail">
                    <div class="col-12 profile-data" style="border: 1px solid lightgrey;">
                        {{-- <div class="row">
                            <div class="col-11"></div>
                            <div class="col-1 editss">
                                <img style="cursor: pointer;" onclick="document.getElementById('update_box').style.display='flex';" src="{{ asset('web_assets/images/edit.png') }}"width="20" height="20" alt="">
                            </div>
                        </div> --}}
                        @if($myplan == null)
                        @else
                        <div class="row det-row">
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-6">
                                        <p style="font-weight:550;">Plan Name</p>
                                    </div>
                                    <div class="col-6">
                                        <p>{{ $myplan->plan_name }}</p>
                                    </div>
                                    <!--<div class="col-6">-->
                                    <!--    <p style="font-weight:550;">Data Limit</p>-->
                                    <!--</div>-->
                                    <!--<div class="col-6">-->
                                    <!--    <p>{{ $myplan->data_limit }}</p>-->
                                    <!--</div>-->
                                    <div class="col-6">
                                        <p style="font-weight:550;">Client Limit</p>
                                    </div>
                                    <div class="col-6">
                                        <p>{{ $myplan->client_limit }}</p>
                                    </div>
                                    <div class="col-6">
                                        <p style="font-weight:550;">User License </p>
                                    </div>
                                    <div class="col-6">
                                        <p>{{ $myplan->no_of_users }}</p>
                                    </div>
                                    <div class="col-6">
                                        <p style="font-weight:550;">Messages</p>
                                    </div>
                                    <div class="col-6">
                                        <p>{{ $myplan->messaging }}</p>
                                    </div>
                                    <div class="col-6">
                                        <p style="font-weight:550;">Invoicing</p>
                                    </div>
                                    <div class="col-6">
                                        <p>{{ $myplan->invoicing }}</p>
                                    </div>
                                    <div class="col-6">
                                        <p style="font-weight:550;">Reports</p>
                                    </div>
                                    <div class="col-6">
                                        <p>{{ $myplan->reports }}</p>
                                    </div>
                                    <div class="col-6">
                                        <p style="font-weight:550;">Analytics</p>
                                    </div>
                                    <div class="col-6">
                                        <p>{{ $myplan->analytics }}</p>
                                    </div>
                                    <!--<div class="col-6">-->
                                    <!--    <p style="font-weight:550;">No. of Branches</p>-->
                                    <!--</div>-->
                                    <!--<div class="col-6">-->
                                    <!--    <p>{{ $myplan->no_of_branches }}</p>-->
                                    <!--</div>-->
                                    <div class="col-6">
                                        <p style="font-weight:550;">Price(in USD)</p>
                                    </div>
                                    @php
                                    $date1=date_create(date("Y-m-d",strtotime($user->membership_start_date)));
                                    $date2=date_create(date("Y-m-d",strtotime($user->membership_expiry_date)));
                                    $diff=date_diff($date1,$date2);
                                    $validity = $diff->format("%y");
                                    if($validity < 1)
                                    {
                                        $validity = $diff->format("%a Days");
                                    }
                                    else{
                                        $validity = $diff->format("%y Years");
                                    }
                                    if($validity == 1){
                                        $plan_amount = round(( $myplan->price_per_year * 1) * 1);
                                    }
                                    elseif($validity == 2){
                                        $plan_amount = round(( $myplan->price_per_year * 2) * 0.9);
                                    }
                                    elseif($validity == 3){
                                        $plan_amount = round(( $myplan->price_per_year * 3) * 0.8);
                                    }
                                    elseif($validity == 5){
                                        $plan_amount = round(( $myplan->price_per_year * 5) * 0.5);
                                    }
                                    else{
                                        $plan_amount = $myplan->price_per_year;
                                    }
                                    @endphp
                                    <div class="col-6">
                                        <p>{{ $plan_amount }}</p>
                                    </div>
                                    <div class="col-6">
                                        <p style="font-weight:550;">Validity</p>
                                    </div>
                                    <div class="col-6">
                                        <p>{{ $validity }}</p>
                                    </div>
                                    <div class="col-6">
                                        <p style="font-weight:550;">Multi-Device Support</p>
                                    </div>
                                    <div class="col-6">
                                        <p>{{ $myplan->multi_device_support }}</p>
                                    </div>
                                    <div class="col-6">
                                        <p style="font-weight:550;">Secure Environment</p>
                                    </div>
                                    <div class="col-6">
                                        <p>{{ $myplan->secure_environment }}</p>
                                    </div>
                                    <div class="col-6">
                                        <p style="font-weight:550;">Multi-Currency Support</p>
                                    </div>
                                    <div class="col-6">
                                        <p>{{ $myplan->multi_currency_support }}</p>
                                    </div>
                                    <div class="col-6">
                                        <p style="font-weight:550;">Subscription Start Date</p>
                                    </div>
                                    <div class="col-6">
                                        <p>{{ $user->formatted_membership_start }}</p>
                                    </div>
                                    <div class="col-6">
                                        <p style="font-weight:550;">Subscription End Date</p>
                                    </div>
                                    <div class="col-6">
                                        <p>{{  $user->formatted_membership_expiry }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
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
                                @if($siteuser->profile_img != "")
                                <img src="{{ asset('web_assets/users/user'.$siteuser->id.'/'.$siteuser->profile_img) }}" width="200" height="200" alt="">
                                @else
                                <img src="{{ asset('web_assets/images/profile.jpg') }}" width="200" height="200" alt="">
                                @endif
                            </div>
                            <div class="col-lg-5"></div>
                        </div>
                    </div> --}}
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

@if(session()->has('deleted'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: 'User Deleted Successfully!'
    })
  </script>

@endif
@if(session()->has('price_plan_expiry'))
  <script>
    Swal.fire({
      icon: 'warning',
      title: 'Your Subscription Plan has Expired',
      html: 'Please <a @if($user->user_type == "Subscriber") href="{{ route('membership') }}" @else href="#" @endif>Renew/Upgrade</a> to Continue!'
    })
  </script>

@endif
@if(session()->has('payment_success'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Congratulations',
      text: 'Payment Done Successfully! Your subscription updated successfully!'
    })
  </script>

@endif
@endsection()

@if(session()->has('download_error'))
  <script>
    Swal.fire({
      icon: 'error',
      title: 'Download Failed',
      text: '{{ session('download_error') }}'
    })
  </script>
@endif
