@extends('web.layout.main')

@section('main-section')
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
            body {
                font-family: 'Lato', sans-serif!important;
            }
        </style>
    <div class="contaier-fluid ">
        <div class="row p-policy">
            <h1>Privacy Policy</h1>
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
                    <a href="{{ route('privacy_policy') }}" style="font-weight: 600;"> Privacy Policy </a> <br>
                </div>
                <div class="btn1">
                    <img src="images/terms-and-conditions 1.png" width="12" height="15" alt="">
                    <a href="{{ route('terms_conditions') }}"> GDPR </a> <br>
                </div>
                <div class="btn1">
                    <img src="images/Group 150.png" width="12" height="15" alt="">
                    <a href="{{ route('refund_policy') }}"> Cookie Notice </a> <br>
                </div>
            </div>
            <div class="col-lg-8 mb-5 p-policytext">
                <div class="pri-text">
                    <h3>Privacy Policy</h3>
                    <p>Updated: July 21, 2022</p>
                    <p><strong>About Us</strong></p>
                    <p>We are a Private Limited company based in London, UK. We provide Consultancy Management System
                        &ndash; a Software-as-a-Service that helps advisories streamline daily work of clients&rsquo;
                        records, documents and case management. Advisories that use our services can provide their staff and
                        customers with a more professional, timely and engaging experience.&nbsp;</p>
                    <p><strong>About this Privacy Notice</strong></p>
                    <p>This Privacy Notice sets forth the handling practices of adwiseri.com. (variously,
                        &ldquo;Adwiseri&rdquo;, &ldquo;we&rdquo;, &ldquo;our&rdquo; or &ldquo;us&rdquo;) and its affiliates
                        in regard to the collection, usage and disclosure of personal data or personal information that you
                        may provide to us through using this website (<a
                            href="https://www.pandadoc.com/">www.adwiseri.com</a>) (the &ldquo;Website&rdquo;), or by using
                        any product or service provided by Adwiseri (the &ldquo;Services&rdquo;).</p>
                    <p>If you do not accept this Privacy Notice and/or do not meet and/or comply with the provisions set
                        forth herein, then you should not use our Website.&nbsp;</p>
                    <p><strong>Types of information we collect</strong></p>
                    <p>The following provides examples of the type of information that we collect from you and how we use
                        that information.&nbsp;</p>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>
                                    <p><strong>Context</strong></p>
                                </td>
                                <td>
                                    <p><strong>Types of data</strong></p>
                                </td>
                                <td>
                                    <p><strong>Primary purpose for collection and use of data</strong></p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Client information&nbsp;</p>
                                </td>
                                <td>
                                    <p>We collect the name, username, and contact information, of our clients and their
                                        employees with whom we may interact.&nbsp;</p>
                                </td>
                                <td>
                                    <p>We have a legitimate interest in contacting our clients and communicating with them
                                        concerning normal business administration such as projects, services, and billing.
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Client user account information</p>
                                </td>
                                <td>
                                    <p>We collect personal data from our clients when they create an account to access and
                                        use the Services or request certain free Services from our Website. This information
                                        could include business contact information such as name, email address, title,
                                        company information, industry, and password for our services.&nbsp;</p>
                                </td>
                                <td>
                                    <p>We have a legitimate interest in providing account related functionalities to our
                                        users, monitoring account log-ins, and detecting potential fraudulent logins or
                                        account misuse. Additionally, we use this information to fulfill our contract to
                                        provide you with Services.</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Contact information of vendors</p>
                                </td>
                                <td>
                                    <p>Users of our service may ask their vendors or service providers to submit company and
                                        security related information on our platform (e.g., to complete a security
                                        questionnaire). When a user invites a vendor we collect the name and email address
                                        of the vendor.</p>
                                </td>
                                <td>
                                    <p>We have a legitimate interest in contacting vendors on behalf of our clients in order
                                        to invite them to communicate with companies through our platform. Among other
                                        things, the communication allows our clients to efficiently solicit, and receive,
                                        security questionnaires, and allows vendors to efficiently solicit, and transmit,
                                        security questionnaires. Additionally, we use this information to fulfill our
                                        contract to provide Services which may include soliciting, receiving, transmitting,
                                        and hosting responses to security questions.</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Account information &mdash; vendors</p>
                                </td>
                                <td>
                                    <p>We collect personal data from vendors when they create an account to access and use
                                        the Services or request certain free Services from our Website. This information
                                        could include business contact information such as name, email address, title,
                                        company information, and password for our services.&nbsp;</p>
                                </td>
                                <td>
                                    <p>We have a legitimate interest in providing account related functionalities to our
                                        vendor-users, monitoring account log-ins, and detecting potential fraudulent logins
                                        or account misuse. Additionally, in some cases, we use this information to fulfill
                                        our contract to provide vendor-users with Services.</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Cookies and first party tracking</p>
                                </td>
                                <td>
                                    <p>We use cookies and clear GIFs. &ldquo;Cookies&rdquo; are small pieces of information
                                        that a website sends to a computer&rsquo;s hard drive while a web site is viewed.
                                        See our Cookie Notice for further information.</p>
                                </td>
                                <td>
                                    <p>We have a legitimate interest in making our website operate efficiently.&nbsp;</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Cookies and third-party tracking</p>
                                </td>
                                <td>
                                    <p>We participate in behavior-based advertising, this means that a third party uses
                                        technology (<em>e.g.,&nbsp;</em>a cookie) to collect information about your use of
                                        our website so that they can provide advertising about products and services
                                        tailored to your interests on our website, or on other websites. See our Cookie
                                        Preference Center for more information.&nbsp;</p>
                                </td>
                                <td>
                                    <p>We have a legitimate interest in understanding our users and providing tailored
                                        services. Non-essential/non-service provider cookies will not be deployed until
                                        opt-in consent is obtained.</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Demographic information</p>
                                </td>
                                <td>
                                    <p>We use IP information to 1). Ensure the legality of our documents (under eSignature
                                        law); 2). Understand how user behavior varies in different locations in order to
                                        improve our software; 3.) Depending on location, provide a better support and
                                        success service.&nbsp;</p>
                                </td>
                                <td>
                                    <p>We have a legitimate interest in ensuring that our product/service is legal and
                                        providing tailored services based on the location (Country) &ndash; such as
                                        appropriate 1) support, 2) contract content, and 3) templates. IP information will
                                        not be used for behavioral purposes absent explicit consent.</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Email interconnectivity</p>
                                </td>
                                <td>
                                    <p>If you receive email from us, we use certain tools to capture data related to when
                                        you open our message, click on any links or banners it contains and make purchases.
                                        If you receive email from us, we use certain tools to capture data related to when
                                        you open our message, click on any links or banners it contains and make purchases.
                                    </p>
                                </td>
                                <td>
                                    <p>We have a legitimate interest in understanding how you interact with our
                                        communications to you. Such data capture will only be deployed following receipt of
                                        explicit consent.</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Employment</p>
                                </td>
                                <td>
                                    <p>When you apply for a job posting, or become an employee, we collect information
                                        necessary to process your application or to retain you as an employee. This may
                                        include, among other things, your Social Security Number. Providing this information
                                        is required for employment.</p>
                                </td>
                                <td>
                                    <p>We use information about current employees to perform our contract of employment, or
                                        the anticipation of a contract of employment with you. In some contexts, we are also
                                        required by law to collect information about our employees. We also have a
                                        legitimate interest in using your information to have efficient staffing and work
                                        force operations.&nbsp;</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Feedback/Support</p>
                                </td>
                                <td>
                                    <p>We collect personal data from you contained in any inquiry you submit to us regarding
                                        our Website or Services, such as completing our online forms, calling, or emailing
                                        for the purposes of general inquiries, support requests, or to report an issue. When
                                        you communicate with us over the phone, your calls may be recorded and analyzed for
                                        training, quality control and for sales and marketing purposes. During such calls we
                                        will notify you of the recording via either voice prompt or script.&nbsp;</p>
                                </td>
                                <td>
                                    <p>We have a legitimate interest in receiving, and acting upon, your feedback, issues,
                                        or inquiries.&nbsp;</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Mailing list</p>
                                </td>
                                <td>
                                    <p>When you sign up for one of our mailing lists, we collect your email address.</p>
                                </td>
                                <td>
                                    <p>We share information about our products and services with individuals that consent to
                                        receive such information. We also have a legitimate interest in sharing information
                                        about our products or services.</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Marketing data</p>
                                </td>
                                <td>
                                    <p>When you subscribe to one of our mailing list(s), we collect your email
                                        address.&nbsp;</p>
                                </td>
                                <td>
                                    <p>We share information about our products and services with individuals that consent to
                                        receive such information. We also have a legitimate interest in sharing information
                                        about our products or services.</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Mobile device data</p>
                                </td>
                                <td>
                                    <p>We collect information from your mobile device when visiting our Website. Such
                                        information may include operating system type and/or mobile device model, browser
                                        type, domain, and other system settings, the language your system uses and the
                                        country and time zone of your device, geo-location, unique device identifier and/or
                                        other device identifier, mobile phone carrier identification, and device software
                                        platform and firmware information.</p>
                                </td>
                                <td>
                                    <p>We have a legitimate interest in identifying unique visitors, and in understanding
                                        how users interact with us on their mobile devices.</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Order placement</p>
                                </td>
                                <td>
                                    <p>Subsequent to Service enrollment (where we collect name, email and phone number, job
                                        role, company name and size), to place an order, we collect billing address, and
                                        credit card details.</p>
                                </td>
                                <td>
                                    <p>We use your information to perform our contract to provide you with products or
                                        services.</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Surveys</p>
                                </td>
                                <td>
                                    <p>When you participate in a survey we collect information that you provide through the
                                        survey. If the survey is provided by a third-party service provider, the third
                                        party&rsquo;s privacy policy applies to the collection, use, and disclosure of your
                                        information. Participation in any such surveys is completely voluntary and you
                                        therefore have a choice whether to disclose such information.&nbsp;</p>
                                </td>
                                <td>
                                    <p>We have a legitimate interest in understanding your opinions, and collecting
                                        information relevant to our organization.</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Website interactions</p>
                                </td>
                                <td>
                                    <p>We use technology to monitor how you interact with our website. This may include
                                        which links you click on, or information that you type into our online forms. This
                                        may also include information about your device or browser.</p>
                                </td>
                                <td>
                                    <p>We have a legitimate interest in understanding how you interact with our website to
                                        better improve it, and to understand your preferences and interests in order to
                                        select offerings that you might find most useful. We also have a legitimate interest
                                        in detecting and preventing fraud.</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Web logs</p>
                                </td>
                                <td>
                                    <p>We collect information, including your browser type, operating system, Internet
                                        Protocol (IP) address (a number that is automatically assigned to a computer when
                                        the Internet is used), domain name, click-activity, referring website, and/or a
                                        date/time stamp for visitors.</p>
                                </td>
                                <td>
                                    <p>We have a legitimate interest in monitoring our networks and the visitors to our
                                        websites. Among other things, it helps us understand which of our services is the
                                        most popular.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p>In addition to the information that we collect from you directly, we may also receive information
                        about you from other sources, including third parties, business partners, our affiliates, or
                        publicly available sources. For example, if you submit a job application, or become an employee, we
                        may conduct a background check.</p>
                    <p><strong>Use and processing of personal information</strong></p>
                    <p>In addition to the purposes and uses described above, we use information in the following ways:&nbsp;
                    </p>
                    <ul>
                        <li><p>To identify you when you visit our websites.&nbsp;</p></li>
                        <li><p>To provide our Services.</p></li>
                        <li><p>To improve our Services and offerings.</p></li>
                        <li><p>To promote the security of our Website and Services.</p></li>
                        <li><p>To conduct analytics.</p></li>
                        <li><p>To respond to inquiries related to support, employment opportunities, or other requests.&nbsp;</p>
                        </li>
                        <li><p>To send marketing and promotional materials including information relating to our products,
                            services, sales, or promotions, or those of our business partners.</p></li>
                        <li><p>For internal administrative purposes, as well as to manage our relationships.</p></li>
                    </ul>
                    <p>Although the sections above describe our primary purpose in collecting your information, in many
                        situations we have more than one purpose. For example, if you sign up for Services, we may collect
                        your information to complete that transaction, but we also collect your information as we have a
                        legitimate interest in maintaining your information after your transaction is complete so that we
                        can quickly and easily respond to any questions about your Services. As a result, our collection and
                        processing of your information is based in different contexts upon your consent, our need to perform
                        a contract, our obligations under law, and/or our legitimate interest in conducting our business.
                    </p>
                    <p>&nbsp;</p>
                    <p><strong>Sharing of information</strong></p>
                    <p>In addition to the specific situations discussed elsewhere in this policy, we may share personal
                        information in the following situations:&nbsp;</p>
                    <ul>
                        <li><p><strong>Affiliates and acquisitions</strong>. We may share information with our corporate
                            affiliates (<em>g</em>., parent company, sister companies, subsidiaries, joint ventures, or
                            other companies under common control). If another company acquires, or plans to acquire, our
                            company, business, or our assets, we will also share information with that company, including at
                            the negotiation stage.&nbsp;</p></li>
                        <li><p><strong>Other disclosures with your consent.</strong>We may ask if you would like us to share
                            your information with other unaffiliated third parties who are not described elsewhere in this
                            policy.</p></li>
                        <li><p><strong>Other disclosures without your consent</strong>. We may disclose information in response
                            to subpoenas, warrants, or court orders, or in connection with any legal process, or to comply
                            with relevant laws. We may also share your information in order to establish or exercise our
                            rights, to defend against a legal claim, to investigate, prevent, or take action regarding
                            possible illegal activities, suspected fraud, safety of person or property, or a violation of
                            our policies, or to comply with your request for the shipment of products to or the provision of
                            services by a third-party intermediary.</p></li>
                        <li><p><strong>Public</strong>. Some of our websites may provide the opportunity to post comments, or
                            reviews, in a public forum. If you decide to submit information on these pages, that information
                            may be publicly available.&nbsp;</p></li>
                        <li><p><strong>Service providers</strong>. We share your information with service providers. Among
                            other things service providers help us to administer our website, send e-mail communications,
                            conduct surveys, provide technical support, detect fraud, process payments, and assist in the
                            fulfillment of orders. Our service providers will be given access to your personal information
                            as is reasonably necessary to provide the Website and related Services. Our service providers
                            are contractually obligated to use your personal information only at our direction and in
                            accordance with our Privacy Notice; to handle your personal information in confidence; and to
                            not disclose your personal information to unauthorized third parties. Service providers who
                            violate these obligations are subject to appropriate discipline including, but not limited to,
                            termination as a service provider.&nbsp;</p></li>
                    </ul>
                    <p>Except as otherwise stated in this Privacy Notice, we do not sell, trade, rent or otherwise share for
                        marketing purposes your Personal Data with third parties without your consent.&nbsp;</p>
                    <p><strong>Retention of your personal information</strong></p>
                    <p>The length of time for which we retain personal information depends on the purposes for which we
                        collected and use it and/or as required to comply with applicable laws. Where there are technical
                        limitations that prevent deletion or anonymization, we safeguard personal information and limit
                        active use of it.</p>
                    <p>See the Section &ldquo;Your choices&rdquo; about storage of your personal information.</p>
                    <p><strong>How we protect your personal information&nbsp;</strong></p>
                    <p>We implement security measures designed to protect your personal information from unauthorized
                        access. We apply these tools based on the sensitivity of the personal information we collect, use,
                        and store, and the current state of technology. We protect your personal information through
                        technical and organizational security measures to minimize risks associated with data loss, misuse,
                        unauthorized access, and unauthorize disclosure and alteration. We periodically review our
                        information collection, storage and processing practices, including technical and organizational
                        measures, to guard against unauthorized access to systems.&nbsp; Your account is protected by your
                        account password and we urge you to take steps to keep your personal information safe by not
                        disclosing your password and by logging out of your account after each use.&nbsp;&nbsp;</p>
                    <p>Because the internet is not a completely secure environment, Adwiseri cannot warrant the security of
                        any information you transmit to Adwiseri or guarantee that information on the Website may not be
                        accessed, disclosed, altered and/or destroyed by breach of any of our physical, technical and/or
                        managerial safeguards. In addition, while we take reasonable measure to ensure that service
                        providers keep your information confidential and secure, such service provider&rsquo;s practices are
                        ultimately beyond our control.&nbsp;</p>
                    <p>We are not responsible for the functionality, privacy and/or security measures of any other
                        organization. By using our Website, you acknowledge that you understand and agree to assume these
                        risks. You may ask for a list of technical and organizational measures taken to protect your
                        personal data by e-mailing us at:&nbsp;<a href="mailto:care@adwisery.com">care@adwiseri.com</a>.</p>
                    <p><strong>Your choices&nbsp;</strong></p>
                    <p>You may take the below actions to change or limit the collection or use of your personal
                        information.&nbsp;</p>
                    <p><strong>Promotional/Marketing emails</strong>. You may choose to provide us with your email address
                        for the purpose of allowing us to send free newsletters, surveys, offers, and other
                        promotional/marketing materials to you, as well as targeted offers from third parties. You can stop
                        receiving promotional/marketing emails by following the unsubscribe instructions in e-mails that you
                        receive. and also adjust your email &amp; communication preferences from &ldquo;E-mail
                        Preferences&rdquo; and &ldquo;Communication Preferences&rdquo; sections given under
                        &ldquo;Settings&rdquo; Module respectively.</p>
                    <p>&nbsp;If you decline to receive promotional and/or marketing emails, we may still send you
                        transactional and service-related messages.</p>
                    <p><strong>Online tracking.</strong>&nbsp;We do not currently recognize automated browser signals
                        regarding tracking mechanisms, which may include &ldquo;Do not track&rdquo; instructions.</p>
                    <p><strong>Device and usage information.</strong>&nbsp;If you do not want us to see your device
                        location, you can turn off location sharing on your device, change your device privacy settings, or
                        decline to share location on your browser.</p>
                    <p><strong>Closing your account</strong>. If you wish to close your account, please log in to your
                        account and send a request to close the account from &ldquo;Price Plans&rdquo; module.</p>
                    <p><strong>Your privacy rights</strong></p>
                    <p>Under the GDPR, EU residents have the certain rights with respect to their personal information. You
                        can make the following choices regarding your personal information:</p>
                    <p><strong>Access to your personal information</strong>. You may request access to your personal
                        information by contacting us at the address described below. If required by law, upon request, we
                        will grant you reasonable access to the personal information that we have about you. We will provide
                        this information in a portable format, if required. Note that California residents may be entitled
                        to ask us for a notice describing what categories of personal information (if any) we share with
                        third parties or affiliates for direct marketing.&nbsp;</p>
                    <p><strong>Changes to your personal information</strong>. We rely on you to update and correct your
                        personal information. Our website(s) allow you to modify or delete your account profile. If our
                        website does not permit you to update or correct certain information, you may contact us at the
                        address described below in order to request that your information by modified. Note that we may keep
                        historical information in our backup files as permitted by law.&nbsp;</p>
                    <p><strong>Objections/Restriction to your personal information.&nbsp;&nbsp;</strong>You have the right
                        to object to how personal data is processed in relation to public interest/official authority and
                        our legitimate interests as well as direct marketing purposes &ndash; including profiling under
                        both.<strong>&nbsp;&nbsp;</strong>You also have the right to request that processing of your
                        personal information be restricted where its accuracy or lawfulness is contested, you need it in
                        response to legal claims or in relation to verification as to whether legitimate interests for
                        processing exist (resulting from objection made under Art 21(1).</p>
                    <p><strong>Deletion of your personal information</strong>. Typically, we retain your personal
                        information for the period necessary to fulfill the purposes outlined in this notice, unless a
                        longer retention period is required or permitted by law. Where certain grounds apply, the law
                        authorizes you to make a request that your personal information be deleted and triggers our
                        corresponding obligation to comply, unless exceptions apply.&nbsp;</p>
                    <p><strong>Move, copy or export personal data.&nbsp;</strong>This is known as the Right of Portability.
                        You have the right to request that your personal data be forwarded to a third party.</p>
                    <p><strong>Provision/Revocation of consent</strong>. You have the right to provide or decline consent to
                        processing of personal information.&nbsp; If you&rsquo;ve already provided consent, you also have
                        the right to revoke it. This will not impact the legality of processing prior to revocation. If you
                        revoke your consent for the processing of personal information, then we may no longer be able to
                        provide you services. In some cases, we may deny your request to revoke consent if the law permits
                        or requires us to do so &ndash; such as when we are unable to adequately verify your identity. You
                        may revoke consent to processing (where such processing is based upon consent) by contacting us at
                        the address described below.</p>
                    <p><strong>Complaints.</strong>&nbsp;We are committed to resolving valid complaints about your privacy
                        and our collection or use of your personal information.&nbsp; For questions or complaints regarding
                        our data use practices or this Privacy Notice, please contact us as provided below. Should you
                        remain unsatisfied with our response to your complaint, you have the right to contact your local
                        data protection authority.</p>
                    <p>Please note that your rights are not absolute, meaning that in some circumstances, exceptions exist
                        under applicable law. The law may provide exemptions from requests involving your personal data. For
                        example, in order to provide our Services to you, deleting your personal information may prevent you
                        from accessing or using it.&nbsp;&nbsp;</p>
                    <p>You may exercise these rights by contacting us at&nbsp;<a
                            href="mailto:care@adwisery.com">care@adwiseri.com</a>. We will respond to any such request in a
                        timely manner as specified by the GDPR. If we need more time to fulfill your request, we will let
                        you know in advance. We will not exceed the legally specified time limit under any circumstance.</p>
                    <p>Note that, as required by law, we will require you to prove your identity. We may verify your
                        identity by phone call or email. Depending on your request, we will ask for information such as your
                        name or other account information. We may also ask you to provide a signed declaration confirming
                        your identity. Following a request, we will use reasonable efforts to supply, correct or delete
                        personal information about you in our files.</p>
                    <p>In some circumstances, you may designate an authorized agent to submit requests to exercise certain
                        privacy rights on your behalf. We will require verification that you provided the authorized agent
                        permission to make a request on your behalf. You must provide us with a copy of the signed
                        permission you have given to the authorized agent to submit the request on your behalf and verify
                        your own identity directly with us. If you are an authorized agent submitting a request on behalf of
                        an individual you must attach a copy of the following information to the request:</p>
                    <ol>
                        <li><p>A completed, signed&nbsp;&ldquo;Authorized Agent Designation&rdquo;form indicating that you have
                            authorization to act on the consumer&rsquo;s behalf.</p></li>
                        <li><p>If you are a business, proof that you are registered with the Secretary of State to conduct
                            business in California.</p></li>
                    </ol>
                    <p>If we do not receive both pieces of information, the request will be denied.</p>
                    <p><strong>Other important information</strong></p>
                    <p>The following additional information relates to our privacy practices:</p>
                    <p><strong>International data transfers.&nbsp;</strong>Our company operates globally and has a global
                        infrastructure. We utilize cloud computing which means your&nbsp; personal data may be transferred
                        to a country with data protection laws not as strong as where you reside.&nbsp; We will transfer
                        your Personal Data to countries deemed having adequate levels of data protection as determined by
                        the European Commission.</p>
                    <p>If we share your personal information with entities located in the United States or other non-EEA
                        jurisdictions which, according to the European Commission and the Court of Justice of the European
                        Union through its Schrems II decision, do not offer an adequate level of protection to personal
                        information, the GDPR authorizes other solutions to address lawful cross-border transfers. Adwiseri
                        may rely on data processing agreements (DPAs) with attached standard contractual clauses (SCCs)
                        approved by the European Commission or other appropriate solutions to address cross-border transfers
                        as required or permitted by Articles 46 and 49 of the GDPR.&nbsp; Where required by such laws, you
                        may request a copy of the suitable mechanisms we have in place by contacting us. For further
                        information, see our GDPR Compliance Addendum.</p>
                    
                    <p><strong>Children and minors.&nbsp;</strong>Adwiseri does not knowingly collect personal data from
                        children under the age of thirteen (13). If we learn that we have collected Personal Information
                        from a child under age thirteen (13), we will delete such information as quickly as possible. If you
                        believe that a child under the age of thirteen (13) may have provided us Personal Information,
                        please contact us at:&nbsp;<a href="mailto:care@adwisery.com">care@adwiseri.com</a>&nbsp;. By using
                        the Website, you represent that you are at least eighteen (18) years old and understand that you
                        must be at least eighteen (18) years old in order to create an account and/or purchase the goods
                        and/or services through the Website.</p>
                    <p><strong>Third party websites and services.&nbsp;</strong>We have no control over the privacy
                        practices of websites or applications that we do not own. We are not responsible for the practices
                        employed by any websites and/or services linked to and/or from our Website, including the
                        information and/or content contained therein. Please remember that when you use a link to go from
                        our Website to another website and/or service, our Privacy Notice does not apply to such third-party
                        websites and/or services. Your browsing and interaction on any third-party website and/or service,
                        including those that have a link on our Website, are subject to such third-party&rsquo;s own rules
                        and policies. In addition, you agree that we are not responsible and do not have control over any
                        third-parties that you authorize to access your personal data. If you are using a third-party
                        website and/or service and you allow them to access your personal data, you do so at your own risk.
                    </p>
                    <p><strong>Accessibility.</strong>&nbsp;If you are visually impaired, you may access this notice through
                        your browser&rsquo;s audio reader.</p>
                    <p><strong>Changes to our Privacy Notice</strong></p>
                    <p>In general, changes will be made to this Privacy Notice to address new or modified laws and/or new or
                        modified business procedures. However, we may update this Privacy Notice at any time, with or
                        without advance notice, so please review it periodically. We may provide you additional forms of
                        notice of modifications and/or updates as appropriate under the circumstances. Your continued use of
                        the Website after any modification to this Privacy Notice will constitute your acceptance of such
                        modifications and/or updates. You can determine when this Privacy Notice was last revised by
                        referring to the date it was last &ldquo;Updated&rdquo; above.</p>
                    <p><strong>Contacting us</strong></p>
                    <p>For questions or complaints regarding our use of your personal information or Privacy Notice or to
                        forward deletion requests, please contact us at:&nbsp;<a
                            href="mailto:care@adwisery.com">care@adwiseri.com</a>&nbsp;or Adwiseri, Attention: Privacy
                        Department, 182-184 High Street North, Eastham, London E6 2JA.</p>
                    <p><strong>California Privacy Notice addendum</strong></p>
                    <p><strong>YOUR CALIFORNIA PRIVACY RIGHTS</strong></p>
                    <p>This section applies only to California residents. Under California Civil Code Sections
                        1798.83-1798.84, California residents are entitled to receive: (a) information identifying any
                        third-party companies to whom Adwiseri may have disclosed Personal Information to for direct
                        marketing, within the past year; and (b) a description of the categories of Personal Information
                        disclosed. To obtain such information, please email your request to&nbsp;<a
                            href="mailto:care@adwisery.com">care@adwiseri.com</a>&nbsp;and we will provide a list of
                        categories of Personal Information disclosed within thirty (30) days after receiving such a request.
                        This request may be made no more than once per calendar year. We reserve the right not to respond to
                        requests submitted in ways other than those specified above.&nbsp;</p>
                    <p><strong>PERSONAL INFORMATION WE COLLECT AND HOW WE COLLECT IT</strong></p>
                    <p>We collect the type of information described in this California Privacy Notice Addendum and in the
                        Privacy Notice, which includes Personal Information, in the manner described herein and in the
                        Privacy Notice.&nbsp;</p>
                    <p><strong>&ldquo;Personal information&rdquo;</strong>&nbsp;means information that identifies, relates
                        to, or could reasonably be linked directly or indirectly with a particular California resident,
                        including without limitation information that identifies or could reasonably be linked, directly or
                        indirectly, with a particular consumer or device. Personal Information does not include (i) publicly
                        available information from government records; (ii) deidentified or aggregated consumer information;
                        or (iii) information excluded from the scope of the California Consumer Privacy Act
                        (&ldquo;CCPA&rdquo;) such as health and medical information. If you do not provide the information
                        that we ask for, we may not be able to provide you with the requested services.&nbsp;</p>
                    <p>We collect Personal Information for the business purposes described in our Privacy Notice. The CCPA
                        defines a &ldquo;business purpose&rdquo; as the use of Personal Information for the business&rsquo;s
                        operational purposes, or other notified purposes, provided the use of Personal Information is
                        reasonably necessary and proportionate to achieve the operational purpose for which the Personal
                        Information was collected or another operational purpose that is compatible with the context in
                        which the Personal Information was collected.</p>
                    <p>The categories of other individuals or entities with whom we may share your Personal Information are
                        listed in our Privacy Notice under &ldquo;Sharing of information&rdquo;.</p>
                    <p>We have collected the following categories of Personal Information within the last twelve (12)
                        months:</p>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>
                                    <p><strong>Category</strong>&nbsp;</p>
                                </td>
                                <td>
                                    <p><strong>Information</strong>&nbsp;</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Identifiers.&nbsp;&nbsp;</p>
                                </td>
                                <td>
                                    <p>First name, last name, postal address, unique personal identifier, online identifier,
                                        internet protocol address, email address, email data, website usage data, account
                                        name, or other similar identifiers.&nbsp;</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Personal information categories listed in the California Customer Records statute
                                        (Cal. Civ. Code &sect; 1798.80(e)).&nbsp;</p>
                                </td>
                                <td>
                                    <p>First name, last name, postal address, unique personal identifier, online identifier,
                                        internet protocol address, email address, email data, website usage data, account
                                        name, financial information, or other similar identifiers.&nbsp;Note, some personal
                                        information included in this category may overlap with other categories.&nbsp;</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Commercial information.&nbsp;</p>
                                </td>
                                <td>
                                    <p>Records of services purchased.&nbsp;</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Internet or other similar network activity.&nbsp;</p>
                                </td>
                                <td>
                                    <p>Browsing history, search history, information on a consumer&rsquo;s interaction with
                                        our website.&nbsp;</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Geolocation data.&nbsp;</p>
                                </td>
                                <td>
                                    <p>Physical location via internet protocol address.&nbsp;</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Professional or employment-related information.&nbsp;</p>
                                </td>
                                <td>
                                    <p>Current or past job history or performance evaluations, background information.&nbsp;
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p><strong>USE OF PERSONAL INFORMATION</strong></p>
                    <p>For more information about how we collect your personal information, please see the &ldquo;Types of
                        information we collect&rdquo; and &ldquo;Use and processing your information&rdquo; sections of our
                        Privacy Notice.</p>
                    <p><strong>SHARING PERSONAL INFORMATION&nbsp;</strong></p>
                    <p>We share Personal Information as further described in the &ldquo;Sharing of information&rdquo;
                        section of the Privacy Notice. We also disclose the categories of third-parties to whom we disclosed
                        Personal Information for business purposes is described in the same section.&nbsp;</p>
                    <p><strong>RIGHTS OF CALIFORNIA RESIDENTS</strong></p>
                    <p>If you are a California resident, the CCPA provides you with specific rights regarding your Personal
                        Information, subject to certain exceptions.&nbsp; For instance, we cannot disclose specific pieces
                        of Personal Information if the disclosure would create a substantial, articulable, and unreasonable
                        risk to the security of the Personal Information, your account with us, or the security of our
                        network systems.&nbsp; These rights are explained below:</p>
                    <ul>
                        <li><p><strong>Right against discrimination</strong>. You have the right not to be discriminated
                            against for exercising any of the rights described in this section. We will not discriminate
                            against you for exercising your right to know, delete or opt-out of sales.</p></li>
                        <li><p><strong>Right to access.</strong>You have the right to request that we disclose certain
                            information to you about our collection and use of your Personal Information over the past
                            twelve (12) months.&nbsp; Adwiseri will provide personal information to a consumer upon request
                            a maximum of two times in a 12-month period. Once we receive and confirm your verifiable
                            consumer request, we will disclose the following to you: (i) the categories of Personal
                            Information we collected about you; (ii) the categories of sources for the Personal Information
                            we collected about you; (iii) the business purpose for collecting (or selling, if applicable)
                            the Personal Information; (iv) the categories of third parties with whom we share such Personal
                            Information; and (v) the specific information we collected about you.</p></li>
                        <li><p><strong>Right to delete.</strong>You have the right to request that we delete any of your
                            Personal Information we collected from you and retained, subject to certain exceptions. Once we
                            receive and confirm your verifiable consumer request, we will delete and will direct our service
                            providers to delete your Personal Information from our records, unless an exception applies.
                            Keep in mind, we may deny your request if it is necessary for us or our service providers to:
                            (i) complete the transaction for which we collected the personal information, provide a good or
                            service that you requested, take actions reasonably anticipated within the context of our
                            ongoing business relationship with you, fulfill the terms of a written warranty or product
                            recall conducted in accordance with federal law, or otherwise perform services pursuant to our
                            contract with you; (ii) detect security incidents, protect against malicious, deceptive,
                            fraudulent, or illegal activity, or prosecute those responsible for such activities; (iii) debug
                            our website and/or identify and repair errors that impair existing intended functionality; (iv)
                            exercise free speech, ensure the right of another consumer to exercise their free speech rights,
                            or exercise another right provided for by law; (v) comply with the California Electronic
                            Communications Privacy Act (Cal. Penal Code &sect; 1546 et. seq.); (vi) engage in public or
                            peer-reviewed scientific, historical, or statistical research in the public interest that
                            adheres to all other applicable ethics and privacy laws, when the information&rsquo;s deletion
                            may likely render impossible or seriously impair the research&rsquo;s achievement, if you
                            previously provided informed consent; (vii) enable solely internal uses that are reasonably
                            aligned with consumer expectations based on your relationship with us; (viii) make other
                            internal and lawful uses of that information that are compatible with the context in which you
                            provided it; or (xi) comply with a legal obligation.</p></li>
                        <li><p><strong>Right to opt-out of selling.</strong>You have the right to opt-out of having your
                            Personal Information sold. Adwiseri does not sell Personal Information for monetary or other
                            valuable consideration.</p></li>
                    </ul>
                    <p><strong>REQUEST FOR INFORMATION</strong></p>
                    <p>Pursuant to Section 1798.83 of the California Civil Code (California&rsquo;s &ldquo;Shine the
                        Light&rdquo; law), residents of California have the right to request from a business, with whom the
                        California resident has an established business relationship, certain information with respect to
                        the types of personal information the business shares with third-parties for such
                        third-parties&rsquo; direct marketing purposes and the identities of the third-parties with whom the
                        business has shared such information during the immediately preceding twelve (12) month
                        period.&nbsp;</p>
                    <p><strong>VERIFICATION ON CONSUMER REQUEST AND TIMELINE</strong></p>
                    <p>To assert your right to know, to access, or to delete your Personal Information, please contact us as
                        set forth below.&nbsp;&nbsp;</p>
                    <p>To confirm your identity, It is imperative that we verify the consumer request and so you must
                        provide information that allows us to reasonably verify that you are the person about whom we
                        collected the Personal Information or are an authorized representative. If you make a request on
                        behalf of another person, we will need to verify that you have the authority to do so. You must also
                        describe the request with sufficient detail that allows us to properly understand, evaluate and
                        respond to such request. We cannot respond to your request or provide you with Personal Information
                        if we cannot verify your identity or authority to make the request and confirm the Personal
                        Information relates to you. We will not honor your request if an exception to the law applies.</p>
                    <p>We will respond to requests within forty-five (45) days after our receipt of such verifiable request
                        (or within such other time as required by applicable law). If we need additional time, we will
                        notify you in writing prior to the expiration of the forty-five (45) day period and inform you of
                        the reason for an additional forty-five (45) day extension of time. For the avoidance of doubt, any
                        such requests for Personal Information will cover the twelve (12) month period immediately preceding
                        the date of such verifiable request. A disclosure of Personal Information in response to such a
                        request will be provided in a commonly used format. For more information about requests, please see
                        the &ldquo;Your rights and controlling your personal information&rdquo; section of the Privacy
                        Notice.&nbsp;</p>
                    <p>Send us an email at&nbsp;<a href="mailto:care@adwisery.com">care@adwiseri.com&nbsp;</a>or you can
                        also send a request in writing to Adwiseri, Attention: Privacy Department, 182-184 High Street
                        North, Eastham, London E6 2JA to exercise any of the foregoing.</p>
                    <p>&nbsp;</p>
                </div>
            </div>
        </div>
    </div>
@endsection()
