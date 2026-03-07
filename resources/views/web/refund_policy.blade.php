@extends('web.layout.main')

@section('main-section')
    <div class="contaier-fluid ">
        <div class="row p-policy">
            <h1>Cookie Notice</h1>
        </div>
    </div>
    <div class="container-fluid bg-white pt-4">
        <div class="row p-policycontent ">

            <div class="col-lg-3 p-policybutton">
                <div class="btn1">
                    <img src="images/terms-of-use 1.png" width="12" height="15" alt="">
                    <a href="{{ route('terms_use') }}"> Terms of Use </a> <br>
                </div>
                <div class="btn1">
                    <img src="images/ref.png" width="12" height="15" alt="">
                    <a href="{{ route('privacy_policy') }}"> Privacy Policy </a> <br>
                </div>
                <div class="btn1">
                    <img src="images/terms-and-conditions 1.png" width="12" height="15" alt="">
                    <a href="{{ route('terms_conditions') }}"> GDPR </a> <br>
                </div>
                <div class="btn1">
                    <img src="images/Group 150.png" width="12" height="15" alt="">
                    <a href="{{ route('refund_policy') }}" style="font-weight: 600;"> Cookie Notice </a> <br>
                </div>
            </div>
            <div class="col-lg-8 p-policytext">
                <div class="pri-text">
                    {{-- <p>Refund Policy <br>
                        General Terms <br>
                        Last update: April 07, 2020 </p> --}}
                    <h3>Cookie Notice</h3>
                    <p>Updated: September 24, 2021</p>
                    <p><strong>&nbsp;</strong></p>
                    <p><strong>Introduction</strong></p>
                    <p>Adwiseri (variously, &ldquo;Adwisery&rdquo;, &ldquo;we&rdquo;, &ldquo;its&rdquo; or &ldquo;us&rdquo;)
                        is a web-based solution offered by a company based in London, United Kingdom. We provide Practice
                        Management System &ndash; Software-as-a-Service that helps companies streamline daily work like to
                        create, store and manage clients&rsquo; records and application/case related documents along with
                        assigning cases, performing Analytics, fetching custom reports and manage their users/staff through
                        this single solution.&nbsp;</p>
                    <p>Where Adwisery&rsquo;s Privacy Notice sets forth its personal data handling practices when using
                        its&nbsp;<a href="http://www.adwiseri.com">www.adwiseri.com</a>&nbsp;website &nbsp;(the
                        &ldquo;Website &rdquo;) or its products or services (&ldquo;Services&rdquo;), this Cookie Notice
                        provides further explanation as well as options available to you regarding Cookie
                        use.&nbsp;&nbsp;&nbsp;</p>
                    <p><strong>Cookie Notice</strong></p>
                    <p>Adwisery uses cookies and other similar technologies on its Website &nbsp;and Services. Where some of
                        these cookies are strictly necessary (essential) for Adwisery to operate its Website &nbsp;and
                        provide its services, others are not. For example, non-essential cookies may provide benefits to us
                        (like helping to improve our Website &rsquo;s performance) and simultaneously enhance your
                        experience when you visit the Website.&nbsp;</p>
                    <p>Except as otherwise stated in this Cookie Notice and our Privacy Notice, we do not sell, trade, rent
                        or otherwise share for marketing purposes your Personal Data with third parties without your
                        consent.&nbsp;</p>
                    <p><strong>About cookies</strong></p>
                    <p>Before we get into the details, it&rsquo;s important to clarify what we&rsquo;re talking about.
                        &ldquo;Cookies&rdquo; are small data files that are sent to your computer when you browse a website.
                        Cookies are ubiquitous on the internet and as mentioned above, can provide enormous benefits to
                        businesses and users alike. They can also impact your privacy.</p>
                    <p><strong>Cookie attributes and how we use them&nbsp;</strong></p>
                    <p>Cookies come in all different shapes and sizes. What we mean by this is that there are several ways
                        to describe cookies by, as we call them, &ldquo;cookie attributes.&rdquo; These attributes include
                        the following and are not limited to just one classification:</p>
                    <p><strong>By purpose.</strong>&nbsp;Cookies can be classified into general categories depending on
                        their purpose (and yes, the broadest cookie classification by purpose is essential and
                        non-essential!)</p>
                    <p><strong>Essential.</strong>&nbsp;These cookies are essential or &ldquo;strictly necessary&rdquo; for
                        the Website &nbsp;or service to operate as intended. Examples of this include logging in you on the
                        website, sending a document for an eSignature, inviting users, and enabling access to various
                        features such as setting your privacy preferences, logging in or filling in forms, creating a unique
                        user session, and enabling access to secure areas.&nbsp;&nbsp;</p>
                    <p><strong>Functionality.&nbsp;</strong>These cookies are used to remember website &nbsp;preferences and
                        choices (like your language preference). This can include log-in details (name and password), region
                        you reside in (Country); and customizable elements on a webpage. We, or our third-party providers
                        whose services we incorporated into our pages, may set these cookies. These cookies do not track
                        browsing on other website s and do not collect data for marketing. Certain website
                        &nbsp;functionality may cease to be available to you by disabling them.&nbsp;</p>
                    <p><strong>Performance.*</strong>&nbsp;These cookies are used to enhance and improve the performance of
                        our Website &nbsp;and our Services. They collect information that helps us understand how our
                        customers are using the Website &nbsp;and our Services and evaluating the effectiveness of our
                        marketing campaigns. For example, we may utilize Google Analytics, ReadMe or other third-party
                        services to gather Website &nbsp;analytics. These cookies may collect IP addresses and may generate
                        unique identifiers to analyze how frequently you visit our Website &nbsp;and gather other metrics
                        such as page views. These cookies collect aggregated information and are therefore anonymous. If you
                        do not allow these cookies, we will not know when you have visited our website &nbsp;and will not be
                        able to monitor its performance.</p>
                    <p><strong>Targeting</strong>.* Targeting cookies are used by our advertising and marketing partners and
                        work primarily through uniquely identifying your device (e.g., advertising ID, IP address,
                        geolocation), and are used to build a profile of your interests and show you relevant ads on other
                        website s you may visit. These cookies are primarily placed by third parties and may be used to
                        connect to social media platforms. If you do not allow these cookies, you will experience less
                        targeted advertising.</p>
                    <p><em><strong>NOTE:&nbsp;</strong></em><em>&nbsp;Performance and Targeting Cookie Categories above are
                            non-essential.</em></p>
                    <p><strong>By party placement.&nbsp;</strong>Cookies are also distinguishable by the party setting them
                        in relation to the website.&nbsp;</p>
                    <p><strong>First party.&nbsp;</strong>These cookies are set (sent to your computer) by the website
                        &nbsp;owner. When you visit our website, any cookies we set are called first-party cookies.&nbsp;
                    </p>
                    <p><strong>Third party.&nbsp;</strong>These cookies are set (sent to your computer) by&nbsp;<em>anyone
                            but the website &nbsp;owner</em>. When you visit a website, any cookies set in this manner are
                        called third party cookies. Third party cookies are set specifically to track and record a web
                        surfer&rsquo;s activity online such as browsing history, online behavior, and demographics. This
                        information is then used to develop a user profile in order to understand user&rsquo;s activity and
                        website &rsquo;s performance, and create custom made advertisements.</p>
                    <p><strong>By duration.&nbsp;</strong>Cookies are also distinguishable by the length of time they are
                        operational.</p>
                    <p><strong>Session.</strong>&nbsp;Session Cookies exist temporarily &ndash; they operate only during the
                        period when the browser is open. They allow the user to continue to be recognized from page to page
                        within a website. During the session, these cookies remember actions that a user takes and then
                        terminates when the browser is closed out.&nbsp;</p>
                    <p><strong>Persistent.&nbsp;</strong>Persistent Cookies are set for significantly longer periods than
                        Session Cookies (usually 1 &ndash; 2 years).&nbsp; These cookies terminate on a specific date or
                        after a period of time has elapsed. During the time they are operational, they can provide benefits
                        including helping authenticate a user, keeping a user logged in and remembering how preferences are
                        set such as menu setting, themes, language selection and internal bookmarks. They can also be used
                        to track browsing habits and create a digital dossier during this time, unless disabled.&nbsp;&nbsp;
                    </p>
                    <p><strong>Putting it all together.</strong>&nbsp;So, how does this all work? Let&rsquo;s take an
                        example: When you sign a Adwisery document, we track your email address and IP address through
                        placement of a cookie to ensure the legality of your eSignature. This means that the website owner
                        (Adwisery) has set this cookie (it&rsquo;s a first party cookie); it will end when you sign your
                        document (it&rsquo;s a session cookie); and it is strictly necessary for the website to operate
                        properly (it&rsquo;s an essential cookie).</p>
                    <p><strong>Other technologies we use</strong></p>
                    <p>Cookies are not the only way to track visitors to a website. We, and our third-party partners, may
                        use other similar technologies such as clear GIFs (also known as &ldquo;tracking pixels&rdquo; and
                        &ldquo;web beacons&rdquo;). Clear GIFs are small graphics files that contain an identifier that
                        recognizes when a user visits our website. This allows our third-party partners to monitor traffic
                        patterns between website s, communicate with cookies, to understand if you have come from an
                        advertisement displayed on a third-party website, to monitor the success of marketing campaigns and
                        to improve site performance. These technologies typically rely on cookies to function, so disabling
                        cookies in your browser will impair their functionality.<strong>&nbsp;</strong></p>
                    <p><strong>Your options&nbsp;</strong></p>
                    <p><strong>Managing use of cookies in your browser.&nbsp;</strong>Many internet browsers allow you as a
                        user to change or even delete cookie settings &ndash; and this includes strictly necessary cookies.
                        To find out more about how to do this, see the following links to major browsers below, provided for
                        your convenience. If your browser isn&rsquo;t listed below, go to the help pages of your specific
                        browser for guidance. For further information about cookies and how to modify cookie settings,
                        see&nbsp;<a
                            href="http://www.allaboutcookies.org/browsers/index.html">http://www.allaboutcookies.org/browsers/index.html</a>&nbsp;and&nbsp;<a
                            href="http://www.allaboutcookies.org/">www.allaboutcookies.org</a>.&nbsp;</p>
                    <p>For Google Chrome, go to:&nbsp;&nbsp;<a href="https://support.google.com/chrome/answer/95647">Google
                            Chrome Cookie Help</a></p>
                    <p>For Microsoft Edge, go to:&nbsp;&nbsp;<a
                            href="https://support.microsoft.com/en-us/windows/microsoft-edge-browsing-data-and-privacy-bb8174ba-9d73-dcf2-9b4a-c582b4e640dd">MicroSoft
                            Edge Cookie Help</a>&nbsp;</p>
                    <p>For Opera, go to:&nbsp;<a href="https://help.opera.com/en/latest/security-and-privacy/">Opera Cookie
                            Help</a>&nbsp;</p>
                    <p>For Firefox, go to:&nbsp;<a
                            href="https://support.mozilla.org/en-US/kb/enhanced-tracking-protection-firefox-desktop">FireFox
                            Desktop Tracking Protection</a></p>
                    <p>For Apple Safari, go to:&nbsp;<a
                            href="https://support.apple.com/en-gb/guide/safari/sfri11471/mac">Apple Cookie Help</a></p>
                    <h3><strong>Managing use of cookies through cookie banner and third parties</strong></h3>
                    <p><strong>Cookie Banner.</strong>&nbsp;Adwisery gives you the option to either accept (opt-in) or
                        decline (opt-out) its use of cookies through a Cookie Banner. By accepting cookies, we will use
                        cookies for the purposes explained in this notice.&nbsp; By exercising the option to decline,
                        non-essential cookies will not be used. This will not affect our use of strictly necessary cookies
                        as without them, the website may not function/display properly.</p>
                    <p><strong>Third Parties.</strong>&nbsp;Any cookies that are placed on your browsing device by a third
                        party can be managed through your browser (as described above) or by checking the third
                        party&rsquo;s website for more information about cookie management and how to &ldquo;opt-out&rdquo;
                        of receiving cookies from them. You can learn more at the following third party websites:</p>
                    <ul>
                        <li>All About Cookies:&nbsp;<a
                                href="http://www.allaboutcookies.org/">http://www.allaboutcookies.org/</a></li>
                        <li>Network Advertising Initiative:&nbsp;<a
                                href="http://www.networkadvertising.org/">http://www.networkadvertising.org/</a></li>
                        <li>Wikipedia:&nbsp;<a
                                href="https://en.wikipedia.org/wiki/HTTP_cookie">https://en.wikipedia.org/wiki/HTTP_cookie</a>
                        </li>
                    </ul>
                    <p><strong>Opting-out of interest-based advertising cookies</strong></p>
                    <p>We do not control interest-based advertising cookies on our Services. However, many advertising
                        companies that collect information for interest-based advertising are members of the Digital
                        Advertising Alliance (DAA) or the Network Advertising Initiative (NAI), both of which maintain
                        website s where people can opt out of interest-based advertising from their members.</p>
                    <p>To opt-out of website interest-based advertising provided by each organization&rsquo;s respective
                        participating companies, visit:</p>
                    <ul>
                        <li>the DAA&rsquo;s opt-out portal available at&nbsp;<a
                                href="http://optout.aboutads.info/">http://optout.aboutads.info/</a>,&nbsp;</li>
                        <li>the DAA of Canada&rsquo;s opt-out portal available at&nbsp;<a
                                href="https://youradchoices.ca/en/tools">https://youradchoices.ca/en/tools</a>, or&nbsp;
                        </li>
                        <li>the NAI&rsquo;s opt-out portal available at&nbsp;<a
                                href="http://optout.networkadvertising.org/?c=1">http://optout.networkadvertising.org/?c=1</a>.
                        </li>
                    </ul>
                    <p>Residents of the European Union may opt-out of online behavioral advertising served by the European
                        Interactive Digital Advertising Alliance&rsquo;s participating member organizations by
                        visiting&nbsp;<a
                            href="https://www.youronlinechoices.eu/">https://www.youronlinechoices.eu/</a>.&nbsp;</p>
                    <p><strong>Contacting us</strong></p>
                    <p>For questions or complaints regarding our Privacy Cookie Notice, please contact us at:&nbsp;<a
                            href="mailto:care@adwiseri.com">care@adwiseri.com</a>&nbsp;or Adwiseri, Attention: Privacy
                        Department, 182-184 High Street North, Eastham, London E6 2JA</p>
                    
                </div>
            </div>
        </div>
    </div>
@endsection()
