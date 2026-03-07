<!---Navbar-->
@php

    use App\Models\UserRoles;
    use App\Models\Affiliates;
    $client_roles = UserRoles::where('user_id', '=', $user->id)
        ->where('module', '=', 'Clients')
        ->first();
    $application_roles = UserRoles::where('user_id', '=', $user->id)
        ->where('module', '=', 'Applications')
        ->first();
    $communication_roles = UserRoles::where('user_id', '=', $user->id)
        ->where('module', '=', 'Communication')
        ->first();
    $invoice_roles = UserRoles::where('user_id', '=', $user->id)
        ->where('module', '=', 'Invoices')
        ->first();
    $payment_roles = UserRoles::where('user_id', '=', $user->id)
        ->where('module', '=', 'Payments')
        ->first();
    $report_roles = UserRoles::where('user_id', '=', $user->id)
        ->where('module', '=', 'Reports')
        ->first();
    $subscription_roles = UserRoles::where('user_id', '=', $user->id)
        ->where('module', '=', 'Subscription')
        ->first();
    $setting_roles = UserRoles::where('user_id', '=', $user->id)
        ->where('module', '=', 'Settings')
        ->first();
    $support_roles = UserRoles::where('user_id', '=', $user->id)
        ->where('module', '=', 'Support')
        ->first();
    $affiliate =Affiliates::where('email',$user->email)->first();
@endphp
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
  /* Sidebar icon wrapper (if using a span or i tag) */
.sidebar-menu-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background-color: #695EEE;
    color: white;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    margin-right: 10px;
    font-size: 14px;
}

/* Optional: make icons look consistent if they're inside <i> or <img> */
.sidebar-menu-icon i,
.sidebar-menu-icon img {
    max-width: 16px;
    max-height: 16px;
}

/* Align the entire menu item */
.sidebar-menu-item {
    display: flex;
    align-items: center;
    padding: 6px 10px;
    font-weight: 500;
    border-radius: 6px;
    background-color: white;
    margin-bottom: 6px;
    color: #333;
    text-decoration: none;
    transition: background 0.2s;
}

/* Hover effect */
.sidebar-menu-item:hover {
    background-color: #f0f0ff;
    text-decoration: none;
}

</style>

<div class="mainav-head" style="padding-top: 20px;">
    {{-- <p class=" help-text"><img src="{{ asset('web_assets/images/callus.png') }}" width="15" height="15" alt="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Helpline number <strong>+91-9137196317</strong> </p> --}}
    {{-- <a href="{{ route('contactus') }}" class="contact-texthead"><img src="{{ asset('web_assets/images/contactus.png') }}" width="15" height="15" alt=""> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Contact Us</a> --}}
    <div class="ms-auto px-3 mb-3">
        <img class="px-3"
           @if($affiliate->type != 'Individual')
            @if ($user->organization_logo != null) src="{{ asset('web_assets/users/user' . $user->id . '/' . $user->organization_logo) }}" @else src="{{ asset('web_assets/images/Style2_blue.png') }}" @endif
            height="30px;">
            @endif
        <p class="p-2 text-primary" style="display: inline;margin-left:auto;font-weight:550;">{{ $user->name }}
        </p>
    </div>
 </div>
<nav class="navbar navbar-expand-lg navbar-light" style="background:#695EEE;">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="{{ route('/') }}"><img width="200" src="{{ asset('web_assets/images/Style2.png') }}" /></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse text-end" id="navbarSupportedContent">
            {{-- <ul class="navbar-nav me-auto mb-1 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" href="{{ route('features') }}">Features</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="{{ route('membership') }}">Subscription</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="{{ ('aboutadwiseri') }}">About Adwiseri</a>
          </li>
          <li class="nav-item">
            <a href="{{ ('contactus') }}" class="contact-texthead li-contact"><img src="{{ asset('web_assets/images/contactus.png') }}" width="15" height="15" alt=""> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Contact Us</a>
          </li>
        </ul> --}}
            <form class="log-btn" style="width: 100%;margin:auto;">
                @if (isset($user))
                    <a href="{{ route('userprofile_affiliate') }}" class="btn btn-outline-success login-btn"><img
                            src="{{ asset('web_assets/images/login.png') }}" width="20" height="20"
                            alt=""> {{ $user->name }}</a>
                    <a href="{{ route('logout_affiliate') }}" class="btn btn-outline-success demo-btn"><img
                            src="{{ asset('web_assets/images/logout.png') }}" width="20" height="20"
                            alt=""> Logout</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-success login-btn"><img
                            src="{{ asset('web_assets/images/login.png') }}" width="20" height="20"
                            alt=""> Login</a>
                    <a href="{{ route('user_register') }}" class="btn btn-outline-success demo-btn">Get a Demo</a>
                @endif
            </form>
        </div>
    </div>
</nav>
<!---Navbar END-->
@if (
    $page != 'index' and
        $page != 'features' and
        $page != 'membership' and
        $page != 'about_adwiseri' and
        $page != 'contact_us' and
        $page != 'privacy_policy' and
        $page != 'terms_conditions' and
        $page != 'terms_use' and
        $page != 'refund_policy')
    <div class="container-fluid dashboard-box mt-3 mb-5">

        <div class="row  client-row">
            <div class="col-lg-2 column-dashbox">
                <div class="dash-box">
                    @if (isset($user))
                            <a href="{{ route('affiliate.dashboard_affiliate') }}" @if($page == "dashboard") style="font-weight:700;background-color:#9f9aed;color:white" @endif class="sidebar-menu-item">
                                <span class="sidebar-menu-icon">
                                    <i class="fas fa-home"></i> <!-- Font Awesome icon -->
                                </span>
                                Dashboard
                            </a>
                            <!-- <div class="dashbox-btn d-flex">
                                <img src="{{ asset('web_assets/images/dash.png') }}" width="30" height="30"
                                    alt="">
                                <a href="{{ route('affiliate.dashboard_affiliate') }}"
                                    @if ($page == 'dashboard') style="font-weight:700;" @endif>Dashboard</a>
                            </div> -->
                            <a href="{{ route('referrals_affiliate') }}" @if($page == "referrals") style="font-weight:700;background-color:#9f9aed;color:white" @endif class="sidebar-menu-item">
                                <span class="sidebar-menu-icon">
                                    <i class="fas fa-asterisk"></i> <!-- Font Awesome icon -->
                                </span>
                                Referrals
                            </a>
                        <!-- <div class="dashbox-btn d-flex">
                            <img src="{{ asset('web_assets/images/affiliates.png') }}" width="30" height="30"
                                alt="">
                            <a href="{{ route('referrals_affiliate') }}"
                                @if ($page == 'referrals') style="font-weight:700;" @endif>Referrals</a>
                        </div> -->
                        <a href="{{ route('wallet_affiliate') }}" @if($page == "wallet") style="font-weight:700;background-color:#9f9aed;color:white" @endif class="sidebar-menu-item">
                                <span class="sidebar-menu-icon">
                                    <i class="fas fa-dollar"></i> <!-- Font Awesome icon -->
                                </span>
                                Wallet
                            </a>
                        <!-- <div class="dashbox-btn d-flex">
                            <img src="{{ asset('web_assets/images/documents.png') }}" width="30" height="30"
                                alt="">
                            <a href="{{ route('wallet_affiliate') }}"
                                @if ($page == 'wallet') style="font-weight:700;" @endif>Wallet</a>
                        </div> -->
                        <a href="{{ route('support_affiliate') }}" @if($page == "support") style="font-weight:700;background-color:#9f9aed;color:white" @endif class="sidebar-menu-item">
                                <span class="sidebar-menu-icon">
                                <i class="fa-solid fa-circle-info"></i>
                                </span>
                                Support
                            </a>
                        <!-- <div class="dashbox-btn d-flex">
                            <img src="{{ asset('web_assets/images/supports.png') }}" width="30" height="30"
                                alt="">
                            <a href="{{ route('support_affiliate') }}"
                                @if ($page == 'support') style="font-weight:700;" @endif>Support</a>
                        </div> -->
                    @endif

















                    {{-- @if (isset($user))
                  @if ($user->user_type == 'Subscriber')
                  <div class="dashbox-btn d-flex">
                      <img src="{{ asset('web_assets/images/admin_client.png') }}" width="30" height="30" alt="">
                      <a href="{{ route('job_role') }}" @if ($page == 'job_role') style="font-weight:700;" @endif>Job Role</a>
                  </div>
                  @endif
                @endif --}}
                </div>
            </div>
@endif
