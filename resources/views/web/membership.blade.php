@extends('web.layout.main')

@section('main-section')
@php
use App\Models\UserRoles;
if(isset($user)){
$client_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Clients')->first();
$application_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Applications')->first();
$communication_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Communication')->first();
$invoice_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Invoices')->first();
$payment_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Payments')->first();
$report_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Reports')->first();
$subscription_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Subscription')->first();
$setting_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Settings')->first();
$support_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Support')->first();
}
@endphp
<style>
        body{
            background-color: #F5F5F5;
        }
        .plan-title {
            font-size:20px !important;
            /* font-weight: 500; */
        }
    </style>
  <div class="membership-page">
        <img src="{{ asset('web_assets/images/membership.png') }}" width="1519" height="440" alt="">
        <h1>Subscription Plans</h1>
    </div>

    <div class="container-fluid member-mainbox mt-5 mb-5">
        <h1 class="text-center mb-4">Price Plans</h1>
        <div class="owl-carousel owl-theme" id="subscription-plan">
            @foreach($membership as $plan)
            @if(empty($myplan))
            <div class="plan-card">
                <h3 class="plan-title">{{ $plan->plan_name }} Plan
                    </h3>
                <ul class="plan-features">
                    <li>Client Limit: {{ $plan->client_limit }}</li>
                    <li>User License: {{ $plan->no_of_users }}</li>
                    <li>Messages: {{ $plan->messaging }}</li>
                    <li>Reports: {{ $plan->reports }}</li>
                    <li>Invoicing:
                        @if($plan->invoicing == 'Yes')
                            <i class="fa fa-check icon-circle text-success"></i>
                        @else
                            <i class="fa fa-times icon-circle text-danger"></i>
                        @endif
                    </li>
                    <li>Analytics:
                        @if($plan->analytics === 'Yes')
                            <i class="fa fa-check icon-circle text-success"></i>
                        @else
                            <i class="fa fa-times icon-circle text-danger"></i>
                        @endif
                    </li>
                    <li>Multi-Device Support:
                        @if($plan->multi_device_support === 'Yes')
                            <i class="fa fa-check icon-circle text-success"></i>
                        @else
                            <i class="fa fa-times icon-circle text-danger"></i>
                        @endif
                    </li>
                    <li>Secure Environment:
                        @if($plan->secure_environment === 'Yes')
                            <i class="fa fa-check icon-circle text-success"></i>
                        @else
                            <i class="fa fa-times icon-circle text-danger"></i>
                        @endif
                    </li>
                    <li>Multi-Currency Support:
                        @if($plan->multi_currency_support === 'Yes')
                            <i class="fa fa-check icon-circle text-success"></i>
                        @else
                            <i class="fa fa-times icon-circle text-danger"></i>
                        @endif
                    </li>
                    <li>Validity: {{ $plan->validity }} Days

                    </li>


                </ul>
                <h5 class="plan-price"> {{ ($plan->price_per_year != 0 ) ? 'USD '.$plan->price_per_year  : 'Free'}}</h5>
                {{-- <button class="subscribe-btn"
                    @if(isset($user))
                        onclick="window.location.href = '{{ route('membership') }}';"
                    @else
                        onclick="window.location.href = '{{ route('user_register_plan', $plan->plan_name) }}';"
                    @endif>
                    Subscribe
                </button> --}}

                <button  class="subscribe-btn" onclick="window.location.href = '{{ route('user_register_plan',$plan->plan_name) }}';">Subscribe</button>

            </div>
            @elseif( $plan->plan_name != 'Free')

            <div class="plan-card">
                <h3 class="plan-title">{{ $plan->plan_name }} Plan
                    </h3>
                <ul class="plan-features">
                    <li>Client Limit: {{ $plan->client_limit }}</li>
                    <li>User License: {{ $plan->no_of_users }}</li>
                    <li>Messages: {{ $plan->messaging }}</li>
                    <li>Reports: {{ $plan->reports }}</li>
                    <li>Invoicing:
                        @if($plan->invoicing == 'Yes')
                            <i class="fa fa-check icon-circle text-success"></i>
                        @else
                            <i class="fa fa-times icon-circle text-danger"></i>
                        @endif
                    </li>
                    <li>Analytics:
                        @if($plan->analytics === 'Yes')
                            <i class="fa fa-check icon-circle text-success"></i>
                        @else
                            <i class="fa fa-times icon-circle text-danger"></i>
                        @endif
                    </li>
                    <li>Multi-Device Support:
                        @if($plan->multi_device_support === 'Yes')
                            <i class="fa fa-check icon-circle text-success"></i>
                        @else
                            <i class="fa fa-times icon-circle text-danger"></i>
                        @endif
                    </li>
                    <li>Secure Environment:
                        @if($plan->secure_environment === 'Yes')
                            <i class="fa fa-check icon-circle text-success"></i>
                        @else
                            <i class="fa fa-times icon-circle text-danger"></i>
                        @endif
                    </li>
                    <li>Multi-Currency Support:
                        @if($plan->multi_currency_support === 'Yes')
                            <i class="fa fa-check icon-circle text-success"></i>
                        @else
                            <i class="fa fa-times icon-circle text-danger"></i>
                        @endif
                    </li>
                    <li>Validity: {{ $plan->validity }} Days

                    </li>


                </ul>
                <h5 class="plan-price"> {{ ($plan->price_per_year != 0 ) ? 'USD '.$plan->price_per_year  : 'Free'}}</h5>
                {{-- <button class="subscribe-btn"
                    @if(isset($user))
                        onclick="window.location.href = '{{ route('membership') }}';"
                    @else
                        onclick="window.location.href = '{{ route('user_register_plan', $plan->plan_name) }}';"
                    @endif>
                    Subscribe
                </button> --}}
                @if(isset($user))
                @if($plan->plan_name == $myplan->plan_name)
                  @if((new DateTime("now")) > (new DateTime($subscriber->membership_expiry_date)))
                  <button class="subscribe-btn" @if($user->user_type == "Subscriber") onclick="window.location.href = '{{ route('upgrade_membership', $plan->plan_name) }}';" @endif>Renew</button>
                  @else
                    @if($user->membership_type == "Trial")
                    <button class="subscribe-btn" @if($user->user_type == "Subscriber") onclick="window.location.href = '{{ route('upgrade_membership', $plan->plan_name) }}';" @endif>Active</button>
                    @else
                    <button class="subscribe-btn" >Active</button>
                    @endif
                  @endif
                @else
                  @if(isset($myplan))
                    @if($plan->plan_order < $myplan->plan_order)
                      @if($plan->plan_name == "Free" or $plan->plan_name == "Free Plan")
                      <button class="subscribe-btn" onclick="Swal.fire({ icon: 'warning', title: 'New Subscriber Only', html: 'FREE plan is available to new subscribers only.' });">Free</button>
                      @elseif(count($total_users)>$plan->no_of_users or count($total_clients)>$plan->client_limit)
                      <button class="subscribe-btn" onclick="Swal.fire({ icon: 'warning', title: 'User/Client Limit', text: 'User/Client limit of this plan is less than your registered no. of users/clients.' });">Downgrade</button>
                      @elseif((new DateTime("now")) > (new DateTime($subscriber->membership_expiry_date)))
                      <button class="subscribe-btn" @if($user->user_type == "Subscriber") @endif >
                        Downgrade
                      </button>
                      @else
                      <button class="subscribe-btn" @if($user->user_type == "Subscriber") @endif >
                        Downgrade
                      </button>
                      @endif
                    @else
                    <button class="subscribe-btn" @if($user->user_type == "Subscriber") onclick="window.location.href = '{{ route('upgrade_membership', $plan->plan_name) }}';" @endif >
                      Upgrade
                    </button>
                    @endif
                  @endif
                @endif
              @else
                <button  class="subscribe-btn" onclick="window.location.href = '{{ route('user_register_plan',$plan->plan_name) }}';">Subscribe</button>
              @endif

            </div>
            @endif

            @endforeach
        </div>
    </div>


      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
      @if(isset($user))
      function downgrade_plan(name){
        var myformData = new FormData();
        myformData.append('id', "{{ $user->id }}");
        myformData.append('plan_name', name);
        myformData.append('_token', "{{ csrf_token() }}");
        console.log(myformData);
        $.ajax({
            url: "{{ route('downgrade_plan') }}",
            method: 'POST',
            data: myformData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                if(data == "success"){
                  window.location.reload();
                }
            }
        });
    }
    @endif
        $(document).ready(function() {


        });
    </script>

@if (session()->has('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Congratulations',
        text: 'Profile Updated Successfully.'
    })
</script>
@endif
@if(session()->has('membership_expiry'))
    <script>
      Swal.fire({
        icon: 'warning',
        title: 'Membership Expired!',
        text: 'Please Renew or Upgrade Your Membership!'
      })
    </script>

@endif

@endsection()
