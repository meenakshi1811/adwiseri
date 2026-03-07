@extends('web.layout.main')

@section('main-section')
    <!---Banner-->
    @php $img = asset('web_assets/images/havedemo.png'); @endphp
    {{-- <div class="main-banner" style="background-image: url('{{ $img }}');"> --}}
        {{-- <p>One stop solution for <br>
            Visas & Immigration
            adwiseries <br> to manage cases, storing<br> documents,<br>
            Analytics & Reports and much<br> more...
        </p> --}}
        {{-- <p class="col-lg-5">One-stop solution for Visa & Immigration Consultancies with features like Cloud Storage, Users & Case Management, Analytics, Reports and much more...</p>
        <form class="Signup__form" id="newsletter" method="POST" action="{{route('email_subscription')}}">
            @csrf
            @error('email')
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: '{{$message}}'
                    })
                </script>
            @enderror

            <input required id="email" name="email" minlength="3" maxlength="100" type="email" placeholder="Enter Your email id">
           &nbsp; &nbsp; &nbsp; &nbsp;  <button form="newsletter" type="submit" class="Signup__button">Subscribe</button><br>
            <span style="font-size:14px;margin:0px;color:grey;">Subscribe to our newsletter to get the latest news, updates and advice.</span>
        </form>

    </div> --}}
    <div class="main-banner text-center" style="background-image: url('{{ $img }}');">
        <p class="col-lg-9">
            One-stop solution for Visa & Immigration Consultancies with features like Cloud Storage, Users & Case Management, Analytics, Reports, and much more...
        </p>
        <!-- <form class="Signup__form mt-4" id="newsletter" method="POST" action="{{route('email_subscription')}}" style="max-width: 500px; width: 100%; margin-top: 20px;">
            @csrf
            @error('email')
                <script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops!',
                        text: '{{$message}}'
                    })
                </script>
            @enderror

            <div class="input-container">
                <input required id="email" name="email" type="email" placeholder="Enter Your email address">
                <button type="submit">Subscribe</button>
            </div>
            <span>Subscribe our newsletters to get latest news, updates, and offers.</span>
        </form> -->
       <form class="Signup__form mt-4" id="newsletter" method="POST" 
            action="{{ route('email_subscription') }}" 
            style="max-width: 500px; width: 100%; margin-top: 20px;" novalidate>
            @csrf
            @error('email')
                <script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops!',
                        text: '{{ $message }}',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#6C63FF',
                        customClass: {
                            popup: 'small-swal-popup'
                        },
                        didOpen: (popup) => {
                            const okButton = popup.querySelector('.swal2-confirm');
                            if (okButton) okButton.focus();
                        }
                    });
                </script>
            @enderror

            <div class="input-container">
                <input id="email" name="email" type="email" placeholder="Enter your email address" required>
                <button type="submit">Subscribe</button>
            </div>
            <span>Subscribe our newsletters to get latest news, updates, and offers.</span>
        </form>

        <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("newsletter");
            const email = document.getElementById("email");

            form.addEventListener("submit", function (e) {
                e.preventDefault(); 
                const value = email.value.trim();
                const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (!value || !regex.test(value)) {
                    Swal.fire({
                        icon: "warning",
                        title: "Oops!",
                        text: "Please enter valid email address.",
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#6C63FF',
                        customClass: {
                            popup: 'small-swal-popup'
                        },
                        didOpen: (popup) => {
                            const okButton = popup.querySelector('.swal2-confirm');
                            if (okButton) okButton.focus();
                        }
                    });
                    email.focus();
                    return false;
                }else{
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: "Email Subscription Successful. Thank You!",
                        customClass: {
                            popup: "small-swal-popup" // 👈 applies your custom class
                        },
                        didOpen: () => {
                            const okButton = Swal.getConfirmButton();
                            okButton.focus(); // 👈 focuses "OK" automatically
                        }
                    });
                }
                form.submit();
            });
        });
        </script>

        <style>
        /* Make popup smaller */
        .small-swal-popup {
            transform: translateY(-60px) !important; /* 👈 moves popup 200px up */
        }
        </style>

    </div>



    <!---About us-->
    <div class="container about-container mb-5">
        <div class="row about-row">
            <div class="col-3">
                <img src="{{ asset('web_assets/images/demo.png') }}" width="100" height="100" alt="">
                <p>Instant Activation</p>
            </div>
            <div class="col-3">
                <img src="{{ asset('web_assets/images/dat.png') }}" width="110" height="100" alt="">
                <p>Secure Data</p>
            </div>
            <div class="col-3">
                <img src="{{ asset('web_assets/images/easy.png') }}" width="80" height="100" alt="">
                <p>Easy to Use</p>
            </div>
            <div class="col-3">
                <img src="{{ asset('web_assets/images/call_support.png') }}" width="100" height="100" alt="">
                <p>Dedicated support</p>
            </div>
        </div>
    </div>

    <!---Top features--->
    <div class="container-fluid top-feature mb-5">
        <div class="container">
            <div class="row feature-row mb-3">
                <div class="col-sm-12 col-lg-4 feature-img" style="overflow: hidden;">
                    <img src="{{ asset('web_assets/images/feature.png') }}" width="auto" height="330" alt="">
                </div>
                <div class="col-sm-12 col-lg-8 feature-text">
                    <h1>Key Features</h1>
                    <div class="row">
                        @foreach($features as $feature)
                        <h4 class="col-sm-12 col-md-6"> <img src="{{ asset('admin_assets/features/icon/'.$feature->icon) }}" width="30"
                                height="30" alt=""> {{ $feature->name }}</h4>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!--- Have a demo --->

        <div class="container-fluid have-demo pt-3 pb-3">
            <div class="row demo-row">
                <div class="demo-row-text col-sm-12 col-md-6 ps-lg-5 pt-3">
                    <h4>About Us</h4>
                    <p class="mt-3">We care about your business, so tried our best to offer you a solution to manage your daily tasks with efficiency & security leaving you time to focus on the core.</p>
                </div>
                <div class="demo-row-img col-sm-12 col-md-6">
                    {{-- <h2 class="mt-4">Book Demo</h2> --}}
                    <img src="{{ asset('web_assets/images/havedemo.png') }}" width="100%"
                        alt="">
                </div>
            </div>
        </div>


        <!--- Membership --->

        {{-- <div class="container-fluid member-mainbox mt-5 mb-5">
            <h1>Price Plans</h1>

            <div class="row bbx mt-3">
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 member-first mt-3">
                    <h4>Plan</h4>
                    <div class="box-extra fss">
                        <ul class="p-0">
                            <!--<li>Data Limit</li>-->
                            <li class="text-center">Client Limit</li>
                            <li class="text-center">User License </li>
                            <li class="text-center">Messages</li>
                            <li class="text-center">Invoicing</li>
                            <li class="text-center">Reports</li>
                            <li class="text-center">Analytics</li>
                            <!--<li>No. of Branches</li>-->
                            <li class="text-center">Multi-Device Support</li>
                            <li class="text-center">Secure Environment</li>
                            <li class="text-center">Multi-Currency Support</li>
                            <li class="text-center">Validity (Days)</li>
                        </ul>
                    </div>
                    <h5>Price</h5>
                </div>
                @foreach($price_plans as $plan)
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 member-first mt-3">
                    <h4>{{ $plan->plan_name }}</h4>
                    <div class="box-extra ms">
                        <ul class="p-0">
                            <!--<li>{{ $plan->data_limit }}</li>-->
                            <li class="text-center">{{ $plan->client_limit}}</li>
                            <li class="text-center">{{ $plan->no_of_users }}</li>
                            <li class="text-center">{{ $plan->messaging }}</li>
                            <li class="text-center">{{ $plan->invoicing }}</li>
                            <li class="text-center">{{ $plan->reports }}</li>
                            <li class="text-center">{{ $plan->analytics }}</li>
                            <!--<li>{{ $plan->no_of_branches }}</li>-->
                            <li class="text-center">{{ $plan->multi_device_support }}</li>
                            <li class="text-center">{{ $plan->secure_environment }}</li>
                            <li class="text-center">{{ $plan->multi_currency_support }}</li>
                            <li class="text-center">{{ $plan->validity }} Days</li>
                        </ul>
                    </div>
                    <h5>US ${{ $plan->price_per_year }}</h5>
                    <button @if(isset($user)) onclick="window.location.href = '{{ route('membership') }}';" @else onclick="window.location.href = '{{ route('user_register_plan',$plan->plan_name) }}';" @endif>Subscribe</button>
                </div>
                @endforeach
            </div>

        </div> --}}
        <div class="container-fluid member-mainbox mt-5 mb-5">
            <h1 class="text-center mb-4">Price Plans</h1>
            <div class="owl-carousel owl-theme" id="subscription-plan">
                @foreach($price_plans as $plan)
                @if(empty($myplan) )
                <div class="plan-card">
                    <h4 class="plan-title">{{ $plan->plan_name }}
                        </h4>
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
                    <h4 class="plan-title">{{ $plan->plan_name }}
                        </h4>
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
                      <button @if($user->user_type == "Subscriber") onclick="window.location.href = '{{ route('upgrade_membership', $plan->plan_name) }}';" @endif>Renew</button>
                      @else
                        @if($user->membership_type == "Trial")
                        <button @if($user->user_type == "Subscriber") onclick="window.location.href = '{{ route('upgrade_membership', $plan->plan_name) }}';" @endif>Active</button>
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


        <!--- testimonials --->
        {{-- <div class="container-fluid testimonial-mainhead mb-5" >
            <div class="container review-head">
                <div class="row pt-5">
                    <h1 class="">Client Review</h1>
                </div>
                <div class="row owl-carousel owl-theme mt-5" id="testimonials">
                    <div class="col-4 card" style="width:400px;">

                        <div class="text-img d-flex">
                            <div class="test-image">
                                <img src="{{ asset('web_assets/images/sid.png') }}" alt="">
                            </div>
                            <div class="test-text">
                                <p>Siddharth Roy <br> <span>Tech Lead - Visa adwiseri</span> </p>
                            </div>
                        </div>


                        <div class="card-body">
                            <p class="card-text"><i class="fa-solid fa-quote-left"></i>This is a good platform. I was
                                searching for
                                a complete <br> immigration data solution. Where
                                adwiseri helped me. <i class="fa-solid fa-quote-right"></i></p>

                        </div>
                    </div>
                    <div class="col-4 card" style="width:400px;">

                        <div class="text-img d-flex">
                            <div class="test-image">
                                <img src="{{ asset('web_assets/images/cella.png') }}" alt="">
                            </div>
                            <div class="test-text">
                                <p>Cella Almeda <br> <span>Secretory - IBM</span> </p>
                            </div>
                        </div>


                        <div class="card-body">
                            <p class="card-text"><i class="fa-solid fa-quote-left"></i>This is a good platform. I was
                                searching for
                                a complete <br> immigration data solution. Where
                                adwiseri helped me. <i class="fa-solid fa-quote-right"></i></p>

                        </div>
                    </div>
                    <div class="col-4 card" style="width:400px;">

                        <div class="text-img d-flex">
                            <div class="test-image">
                                <img src="{{ asset('web_assets/images/vaishali.png') }}" alt="">
                            </div>
                            <div class="test-text">
                                <p>Vaishali Birla <br> <span>Chief Accountant </span> </p>
                            </div>
                        </div>


                        <div class="card-body">
                            <p class="card-text"><i class="fa-solid fa-quote-left"></i>This is a good platform. I was
                                searching for
                                a complete <br> immigration data solution. Where
                                adwiseri helped me. <i class="fa-solid fa-quote-right"></i></p>

                        </div>
                    </div>
                    <div class="col-4 card" style="width:400px;">

                        <div class="text-img d-flex">
                            <div class="test-image">
                                <img src="{{ asset('web_assets/images/cella.png') }}" alt="">
                            </div>
                            <div class="test-text">
                                <p>Cella Almeda <br> <span>Secretory - IBM</span> </p>
                            </div>
                        </div>


                        <div class="card-body">
                            <p class="card-text"><i class="fa-solid fa-quote-left"></i>This is a good platform. I was
                                searching for
                                a complete <br> immigration data solution. Where
                                adwiseri helped me. <i class="fa-solid fa-quote-right"></i></p>

                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>





        <!--- why adwiseri --->
        
        <div class="container why-advi mb-5">
            <h1 class="text-center">Why adwiseri?</h1>

            <div class="row advi-img-row mt-5">
                <div class="col-md-6 col-lg-6 col-xl-3 hr-line">
                    <img src="{{ asset('web_assets/images/datasecurity.png') }}"
                        alt="">
                    <h3>Data Security</h3>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3 hr-line">
                    <img src="{{ asset('web_assets/images/100client.png') }}"
                        alt="">
                    <h3>Dedicated Support</h3>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3 hr-line">
                    <img src="{{ asset('web_assets/images/securepayment.png') }}"
                        alt="">
                    <h3>Secure payment <br>
                        system</h3>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3 ">
                    <img src="{{ asset('web_assets/images/50count.png') }}"
                        alt="">
                    <h3>Available in multiple regions</h3>
                </div>
            </div>

        </div>

        <!-- <div class="container-fluid testimonial-mainhead mb-5" >
            <div class="container review-head">
            <h1 class="text-center mb-4">Discounts & Offers</h1>
                <div class="row owl-carousel owl-theme mt-5" id="testimonials">
                @foreach($discounts as $discount)
                    <div class="col-4 card" style="width:400px;">

                        <div class="text-img d-flex">
                            <div class="test-image">
                                <img src="https://png.pngtree.com/png-vector/20230408/ourmid/pngtree-price-tag-with-the-discount-icon-vector-png-image_6686659.png" alt="">
                            </div>
                            <div class="test-text">
                                <h4>{{ $discount->discount_type }}</h4>
                            </div>
                        </div>

                        <div class="card-body">
                        <h5 class="plan-price">{{ $discount->discount_value }}</h5>
                            

                        </div>
                    </div>
                @endforeach
                </div>
            </div>
        </div> -->
    </div>

        <div class="collab-box" id="affiliates">
    <div class="Affiliates-banner" style="position: relative;">
        <img src="web_assets/images/collbcopy.jpg" alt="Affiliate Background" width="100%" height="auto">
        
        
        <!-- Right-aligned Text -->
        <div style="position: absolute; top: 44%; left: 35%; color: white; text-align: left;">
            <p class="item-font-fix" style="">
                Want to earn extra from referrals?<br>
                Join our Affiliate Program by clicking 
                <a href="{{ url('/') }}/Affiliates_Reg" >here</a>
            </p>
        </div>
    </div>
</div>


        {{-- <div class="container faq-section my-5">
            <h1 class="text-center mb-4">Frequently Asked Questions</h1>
            <div class="accordion" id="faqAccordion">
                <!-- Question 1 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading1">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true" aria-controls="faq1">
                            What is your refund policy?
                            <i class="ms-2 fas fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Learn about refund eligibility"></i>
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse show" aria-labelledby="faqHeading1" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            We offer a full refund within 30 days of purchase, no questions asked.
                        </div>
                    </div>
                </div>
                <!-- Question 2 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading2">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2">
                            How do I contact support?
                            <i class="ms-2 fas fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Support is available 24/7"></i>
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" aria-labelledby="faqHeading2" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            You can reach out to us via email at support@example.com or call our hotline at (123) 456-7890.
                        </div>
                    </div>
                </div>
                <!-- Question 3 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading3">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3">
                            Is there a free trial available?
                            <i class="ms-2 fas fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Wover trial options"></i>
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" aria-labelledby="faqHeading3" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes, we offer a 14-day free trial with access to all features.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="contact-us-section ">
            <div class="container ">
                <h1 class="text-center">Send Message</h1>
                <div class="row">

                    <!-- Left Column (Text) -->
                    <div class="col-md-6 contact-text">
                        <div class="contact-background" style="background-image: url('{{ asset('web_assets/images/banner.png') }}');">
                            <div class="overlay"></div>
                            <div class="content">
                                <h2>CONTACT US</h2>
                                <p>Let's talk about your problem. We're here to help!</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column (Form) -->
                    <div class="col-md-6 contact-form">

                        <div class="form-wrapper">
                            <form action="#" method="post">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="name" placeholder="Your Name" required>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" id="email" placeholder="Your Email" required>
                                </div>
                                <div class="form-group">
                                    <input type="tel" class="form-control" id="phone" placeholder="Your Phone Number" required>
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" id="message" rows="4" placeholder="Your Message" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section> --}}








@if (session()->has('subscribed'))
<script>
    // Swal.fire({
    //     icon: 'success',
    //     title: 'Success',
    //     text: 'Email Subscription Successful. Thank You!'
    // })
</script>
@endif
    @endsection()
