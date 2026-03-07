<!---Navbar-->
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
      @if(Auth::user()->user_type === 'admin')
          <img class="px-3" @if($user->organization_logo != null) src="{{ asset('web_assets/users/user'.$user->id.'/'.$user->organization_logo) }}" @else src="{{ asset('web_assets/images/Style2.png') }}" @endif height="30px;">
      @else
          <img class="px-3" @if($user->organization_logo != null) src="{{ asset('web_assets/users/user'.$user->id.'/'.$user->organization_logo) }}" @endif height="30px;">
      @endif

      
      <p class="p-2 text-primary" style="display: inline;margin-left:auto;font-weight:550;">{{ $user->organization }}</p>
    </div>
</div>
<nav class="navbar navbar-expand-lg navbar-light" style="background:#695EEE;">
    <div class="container-fluid">
      <!-- <a class="navbar-brand text-white" href="{{ route('/') }}"><img width="50" @if($user->organization_logo != null) src="{{ asset('web_assets/users/user'.$user->id.'/'.$user->organization_logo) }}" @else src="{{ asset('web_assets/images/Style2.png') }}" @endif /></a> -->
       <a class="navbar-brand text-white" href="{{ route('/') }}"><img width="120" src="{{ asset('web_assets/images/Style2.png') }}" /></a>
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
          @if(isset($user))

          <a href="{{ route('userprofile') }}" class="btn btn-outline-success login-btn"><img src="{{ asset('web_assets/images/login.png') }}" width="20"
            height="20" alt=""> {{ $user->name }}</a>
          <a href="{{ route('logout') }}" class="btn btn-outline-success demo-btn"><img src="{{ asset('web_assets/images/logout.png') }}" width="20"
            height="20" alt=""> Logout</a>
          @else
          <a href="{{ route('login') }}" class="btn btn-outline-success login-btn"><img src="{{ asset('web_assets/images/login.png') }}" width="20"
            height="20" alt=""> Login</a>
            <a href="{{ route('user_register') }}" class="btn btn-outline-success demo-btn">Get a Demo</a>
          @endif
        </form>
      </div>
    </div>
  </nav>
  <!---Navbar END-->
@if($page != "index" and $page != "features" and $page != "membership" and $page != "about_adwiseri" and $page != "contact_us" and $page != "privacy_policy" and $page != "terms_conditions" and $page != "terms_use" and $page != "refund_policy")
  <div class="container-fluid dashboard-box mt-3 mb-5">

    <div class="row  client-row">
        <div class="col-lg-2 column-dashbox">
            <div class="dash-box">
                @if(isset($user))
                  @if($user->user_type == "Subscriber" || $user->user_type == "admin")
                  <a href="{{ route('dashboard') }}" @if($page == "dashboard") style="font-weight:700;background-color:#9f9aed;color:white" @endif class="sidebar-menu-item">
                      <span class="sidebar-menu-icon">
                          <i class="fas fa-home"></i> <!-- Font Awesome icon -->
                      </span>
                      Dashboard
                  </a>
                  @endif
                @endif

              <div class="dashbox-btn" id="manage_box" @if($page == "contactus" or $page == "about_adwiseri" or $page == "features" or $page == "membership") style="flex-direction: column;display:flex;" @else style="flex-direction: column;display:none;" @endif>
                <div class="dashbox-btn d-flex">
                  <a href="{{ route('manage_contactus') }}" @if($page == "contactus") style="font-weight:700;" @endif>Contact Us</a>
                </div>
                <div class="dashbox-btn d-flex">
                  <a href="{{ route('manage_about_adwiseri') }}" @if($page == "about_adwiseri") style="font-weight:700;" @endif>About adwiseri</a>
                </div>
                <div class="dashbox-btn d-flex">
                  <a href="{{ route('manage_features') }}" @if($page == "features") style="font-weight:700;" @endif>Features</a>
                </div>
                <div class="dashbox-btn d-flex">
                  <a href="{{ route('manage_membership') }}" @if($page == "membership") style="font-weight:700;" @endif>Subscriptions</a>
                </div>
                <div class="dashbox-btn d-flex">
                  <a href="{{ route('demo_requests') }}" @if($page == "demo_request") style="font-weight:700;" @endif>Demo Requests</a>
                </div>
              </div>
                @if($user->user_type == "admin" || ($client_roles->read_only == 1 or $client_roles->read_write_only == 1))
                <a href="{{ route('client') }}" @if($page == "clients") style="font-weight:700;background-color:#9f9aed;color:white" @endif class="sidebar-menu-item">
                    <span class="sidebar-menu-icon">
                        <i class="fas fa-users"></i> <!-- Font Awesome icon -->
                    </span>
                    Clients
                </a>
                @endif
                @if($user->user_type == "admin" || ($application_roles->read_only == 1 or $application_roles->read_write_only == 1))
                <a href="{{ route('applications') }}" @if($page == "applications") style="font-weight:700;background-color:#9f9aed;color:white" @endif class="sidebar-menu-item">
                    <span class="sidebar-menu-icon">
                    <i class="fa-solid fa-window-restore"></i> <!-- Font Awesome icon -->
                    </span>
                    Applications
                </a>
                @endif
                @if($user->user_type == "admin" || ($invoice_roles->read_only == 1 or $invoice_roles->read_write_only == 1))
                <a href="{{ route('invoices') }}" @if($page == "invoices") style="font-weight:700;background-color:#9f9aed;color:white" @endif class="sidebar-menu-item">
                    <span class="sidebar-menu-icon">
                        <i class="fas fa-file"></i> <!-- Font Awesome icon -->
                    </span>
                    Invoices
                </a>
                @endif
                @if($user->user_type == "admin" || ($payment_roles->read_only == 1 or $payment_roles->read_write_only == 1))
                <a href="{{ route('my_payments') }}" @if($page == "payments") style="font-weight:700;background-color:#9f9aed;color:white" @endif class="sidebar-menu-item">
                    <span class="sidebar-menu-icon">
                        <i class="fas fa-dollar"></i> <!-- Font Awesome icon -->
                    </span>
                    Payments
                </a>
                @endif
                @if(isset($user))
                  @if($user->user_type == "Subscriber" || $user->user_type == "admin")
                  <a href="{{ route('users') }}" @if($page == "users" || $page == 'user_role') style="font-weight:700;background-color:#9f9aed;color:white" @endif class="sidebar-menu-item">
                    <span class="sidebar-menu-icon">
                        <i class="fas fa-user"></i> <!-- Font Awesome icon -->
                    </span>
                    Users&nbsp;(Staff) 
                </a>
                  {{-- <div class="dashbox-btn d-flex">
                      <img src="{{ asset('web_assets/images/user_roles.png') }}" width="30" height="30" alt="">
                      <a href="{{ route('user_role') }}" @if($page == "user_role") style="font-weight:700;" @endif>User Roles</a>
                  </div> --}}
                  @endif
                @endif






                @if($user->user_type == "admin" || ($communication_roles->read_only == 1 or $communication_roles->read_write_only == 1))
                <a href="{{ route('messaging') }}" @if($page == "messaging" || $page == 'communications') style="font-weight:700;background-color:#9f9aed;color:white" @endif class="sidebar-menu-item">
                    <span class="sidebar-menu-icon">
                    <i class="fa-solid fa-comment"></i> <!-- Font Awesome icon -->
                    </span>
                    Communications
                </a>
                <!-- <div class="dashbox-btn d-flex">
                    <img src="{{ asset('web_assets/images/applications.png') }}" width="30" height="30" alt="">
                    <a href="{{ route('messaging') }}" @if($page == "messaging" || $page == "communications") style="font-weight:700;" @endif>Communications</a>
                </div> -->
                @endif

                @if($user->user_type == "admin" || $report_roles->read_only == 1 or $report_roles->read_write_only == 1)
                @if ($user->membership == "Adwiseri" || $user->membership == "Adwiseri+" || $user->membership == "Enterprise" || $user->user_type == "admin")
                <a href="{{ route('sub_reports') }}" @if($page == "reports") style="font-weight:700;background-color:#9f9aed;color:white" @endif class="sidebar-menu-item">
                    <span class="sidebar-menu-icon">
                    <i class="fa-solid fa-flag"></i> <!-- Font Awesome icon -->
                    </span>
                    Reports
                </a>
                  <!-- <div class="dashbox-btn d-flex">
                    <img src="{{ asset('web_assets/images/reports.png') }}" width="30" height="30" alt="">
                    <a href="{{ route('sub_reports') }}" @if($page == "reports") style="font-weight:700;" @endif>Reports</a>
                  </div> -->
                  <a href="{{ route('sub_analytics') }}" @if($page == "analytics") style="font-weight:700;background-color:#9f9aed;color:white" @endif class="sidebar-menu-item">
                    <span class="sidebar-menu-icon">
                        <i class="fa-solid fa-chart-simple"></i> <!-- Font Awesome icon -->
                    </span>
                    Analytics
                </a>
                  <!-- <div class="dashbox-btn d-flex">
                    <img src="{{ asset('web_assets/images/analytics.png') }}" width="30" height="30" alt="">
                    <a href="{{ route('sub_analytics') }}" @if($page == "analytics") style="font-weight:700;" @endif>Analytics</a>
                  </div> -->
                  @if ($user->user_type == "admin" || $user->user_type == "Affiliate")
                  <a href="{{ route('Affiliates') }}" @if($page == "Affiliates") style="font-weight:700;background-color:#9f9aed;color:white" @endif class="sidebar-menu-item">
                      <span class="sidebar-menu-icon">
                          <i class="fas fa-payment"></i> <!-- Font Awesome icon -->
                      </span>
                      Affiliates
                  </a>
                    <!-- <div class="dashbox-btn d-flex">
                      <img src="{{ asset('web_assets/images/reports.png') }}" width="30" height="30" alt="">
                      <a href="{{ route('Affiliates') }}" @if($page == "Affiliates") style="font-weight:700;" @endif>Affiliates</a>
                    </div> -->
                  @endif
                  @else
                  <a href="{{ route('sub_reports') }}" @if($page == "reports") style="font-weight:700;background-color:#9f9aed;color:white" @endif class="sidebar-menu-item">
                      <span class="sidebar-menu-icon">
                          <i class="fa-solid fa-flag"></i> <!-- Font Awesome icon -->
                      </span>
                      Reports
                  </a>
                  <!-- <div class="dashbox-btn d-flex">
                    <img src="{{ asset('web_assets/images/reports.png') }}" width="30" height="30" alt="">
                    <a href="{{ route('sub_reports') }}" @if($page == "reports") style="font-weight:700;" @endif>Reports </a>
                  </div> -->
                @endif

                @endif
                @if(isset($user))
                  @if($user->user_type == "Subscriber" || $user->user_type == "admin")
                  <a href="{{ route('referrals') }}" @if($page == "referrals") style="font-weight:700;background-color:#9f9aed;color:white" @endif class="sidebar-menu-item">
                      <span class="sidebar-menu-icon">
                      <i class="fa-solid fa-asterisk"></i> <!-- Font Awesome icon -->
                      </span>
                      Referrals
                  </a>
                <!-- <div class="dashbox-btn d-flex">
                    <img src="{{ asset('web_assets/images/affiliates.png') }}" width="30" height="30" alt="">
                    <a href="{{ route('referrals') }}" @if($page == "referrals") style="font-weight:700;" @endif>Referrals</a>
                </div> -->
                <a href="{{ route('wallet') }}" @if($page == "wallet") style="font-weight:700;background-color:#9f9aed;color:white" @endif class="sidebar-menu-item">
                      <span class="sidebar-menu-icon">
                          <i class="fas fa-wallet"></i> <!-- Font Awesome icon -->
                      </span>
                      Wallet
                  </a>
                <!-- <div class="dashbox-btn d-flex">
                    <img src="{{ asset('web_assets/images/documents.png') }}" width="30" height="30" alt="">
                    <a href="{{ route('wallet') }}" @if($page == "wallet") style="font-weight:700;" @endif>Wallet</a>
                </div> -->
                @endif
                @if($user->user_type == "admin" || ($subscription_roles->read_only == 1 or $subscription_roles->read_write_only == 1))
                <a href="{{ route('user_membership') }}" @if($page == "user_membership") style="font-weight:700;background-color:#9f9aed;color:white" @endif class="sidebar-menu-item">
                      <span class="sidebar-menu-icon">
                      <i class="fa-solid fa-subscript"></i> <!-- Font Awesome icon -->
                      </span>
                      Subscription
                  </a>
                <!-- <div class="dashbox-btn d-flex">
                    <img src="{{ asset('web_assets/images/invoices.png') }}" width="30" height="30" alt="">
                    <a href="{{ route('user_membership') }}" @if($page == "user_membership") style="font-weight:700;" @endif>Subscription</a>
                </div> -->
                @endif
                @if($user->user_type == "admin" || ($setting_roles->read_only == 1 or $setting_roles->read_write_only == 1))
                <a href="{{ route('my_settings') }}" @if($page == "settings") style="font-weight:700;background-color:#9f9aed;color:white" @endif class="sidebar-menu-item">
                      <span class="sidebar-menu-icon">
                          <i class="fa-solid fa-gear"></i> <!-- Font Awesome icon -->
                      </span>
                      Settings
                  </a>
                <!-- <div class="dashbox-btn d-flex">
                    <img src="{{ asset('web_assets/images/settings.png') }}" width="30" height="30" alt="">
                    <a href="{{ route('my_settings') }}" @if($page == "settings") style="font-weight:700;" @endif>Settings</a>
                </div> -->
                @endif
                @endif
                @if($user->user_type == "admin" || ($support_roles->read_only == 1 or $support_roles->read_write_only == 1))
                <a href="{{ route('support') }}" @if($page == "support") style="font-weight:700;background-color:#9f9aed;color:white" @endif class="sidebar-menu-item">
                      <span class="sidebar-menu-icon">
                          <i class="fa-solid fa-circle-info"></i> <!-- Font Awesome icon -->
                      </span>
                      Support
                  </a>
                <!-- <div class="dashbox-btn d-flex">
                    <img src="{{ asset('web_assets/images/supports.png') }}" width="30" height="30" alt="">
                    <a href="{{ route('support') }}" @if($page == "support") style="font-weight:700;" @endif>Support</a>
                </div> -->
                @endif
                {{-- @if(isset($user))
                  @if($user->user_type == "Subscriber")
                  <div class="dashbox-btn d-flex">
                      <img src="{{ asset('web_assets/images/admin_client.png') }}" width="30" height="30" alt="">
                      <a href="{{ route('job_role') }}" @if($page == "job_role") style="font-weight:700;" @endif>Job Role</a>
                  </div>
                  @endif
                @endif --}}
            </div>
        </div>
@endif
