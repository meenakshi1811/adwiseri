<!---Navbar-->


  {{-- <div class="mainav-head">
    <p class=" help-text"><img src="{{ asset('web_assets/images/callus.png') }}" width="15" height="15" alt="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Helpline number <strong>+91-9137196317</strong> </p>
    <a href="{{ route('contactus') }}" class="contact-texthead"><img src="{{ asset('web_assets/images/contactus.png') }}" width="15" height="15" alt=""> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Contact Us</a>
</div> --}}
{{-- <nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background:#695EEE;">
    <div class="container-fluid">
      <a class="navbar-brand text-white" href="{{ route('/') }}">adwiseri</a>

      <div class="collapse navbar-collapse" id="navbarSupportedContent" style="display:flex !important;">
        <ul class="navbar-nav me-auto mb-1 mb-lg-0">

          <li class="nav-item">
            <a class="nav-link active" href="{{ route('/') }}">Home</a>
          </li>

          <li class="nav-item">
            <a class="nav-link active" href="{{ route('aboutadvisori') }}">About Us</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="{{ route('features') }}">Features</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="{{ route('membership') }}">Pricing</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="{{ route('/')}}#affiliates">Affiliates</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="{{ route('contactus') }}">Contact Us</a>
          </li>
        </ul>
        <form class="log-btn">
          @if(isset($user))
          <a href="{{ route('userprofile') }}" class="btn btn-outline-success login-btn"><img src="{{ asset('web_assets/images/login.png') }}" width="20"
            height="20" alt=""> {{ $user->name }}</a>
          <a href="{{ route('logout') }}" class="btn btn-outline-success demo-btn"><img src="{{ asset('web_assets/images/logout.png') }}" width="20"
            height="20" alt=""> Logout</a>
          @else
          <a href="{{ route('login') }}" class="btn btn-outline-success login-btn"><img src="{{ asset('web_assets/images/login.png') }}" width="20"
            height="20" alt=""> Login</a>
            <a href="{{ route('get_demo') }}" class="btn btn-outline-success demo-btn">Get A Demo</a>
          @endif
        </form>
      </div>
    </div>
  </nav> --}}
  <!---Navbar END-->
  <!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background:#695EEE;">
    <div class="container-fluid">
      <!-- Brand -->
      <a class="navbar-brand text-white" href="{{ route('/') }}"><img class="logo-fix" width="170" src="{{ asset('web_assets/images/Style2.png') }}" /></a>

      <!-- Hamburger Menu Button -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Collapsible Content -->
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          {{-- <li class="nav-item">
            <a class="nav-link active text-white" href="{{ route('/') }}">Home</a>
          </li> --}}
          <li class="nav-item">
            <div class="dropdown">
                <a class="nav-link active  dropdown-toggle" data-bs-toggle="dropdown" href="#">Use Cases</a>
                <ul class="dropdown-menu" style="background-color:#695EEE;">
                    <li><p class="dropdown-item mb-0">General (All countries) Visa Consultants</p></li>
                    <li><p class="dropdown-item mb-0">Study Abroad / Visa Consultants</p></li>
                    <li><p class="dropdown-item mb-0">Business / Work Visa Specialists</p></li>
                    <li><p class="dropdown-item mb-0">PR / Immigration Advisories</p></li>
                    <li><p class="dropdown-item mb-0">USA - Immigration Attorneys</p></li>
                    <li><p class="dropdown-item mb-0">UK - OISC Advisors</p></li>
                    <li><p class="dropdown-item mb-0">Australia - MARA Advisors</p></li>
                    <li><p class="dropdown-item mb-0">Canada - ICCRC Advisors</p></li>
                    <li><p class="dropdown-item mb-0">CBI - Citizenship By Investment Consultants</p></li>
                    <li><p class="dropdown-item mb-0">Visit Visa / Travel Agents</p></li>

                    <li><p class="dropdown-item mb-0">Immigration Law Firms</p></li>


                </ul>
            </div>
          </li>
          {{-- <li class="nav-item">
            <a class="nav-link active text-white" href="{{ route('aboutadvisori') }}">About Us</a>
          </li> --}}
          <li class="nav-item">
            <a class="nav-link active text-white" href="{{ route('features') }}">Features</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active text-white" href="{{ route('membership') }}">Pricing</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active text-white" href="{{ route('affiliate.createLogin')}}">Affiliates</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active text-white" href="{{ route('contactus') }}">Contact Us</a>
          </li>
        </ul>
        <!-- Login and Demo Buttons -->
        <form class="d-flex">
          @if(isset($user))
          <a href="{{ route('userprofile') }}" class="btn btn-outline-light login-btn me-2">
            <img src="{{ asset('web_assets/images/login.png') }}" width="20" height="20" alt=""> {{ $user->name }}
          </a>
          <a href="{{ route('logout') }}" class="btn btn-outline-light demo-btn">
            <img src="{{ asset('web_assets/images/logout.png') }}" width="20" height="20" alt=""> Logout
          </a>
          @else
          <a href="{{ route('login') }}" class="btn btn-outline-light login-btn me-2">
            <img src="{{ asset('web_assets/images/login.png') }}" width="20" height="20" alt=""> Login
          </a>
          <a href="{{ route('get_demo') }}" class="btn btn-outline-light demo-btn">Get A Demo</a>
          @endif
        </form>
      </div>
    </div>
  </nav>
  <!-- Navbar END -->


