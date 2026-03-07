@extends('web.layout.main')

@section('main-section')
    <div class="contaier-fluid ">
        <div class="row p-policy">
            <h1>GDPR</h1>
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
                    <a href="{{ route('terms_conditions') }}" style="font-weight: 600;">GDPR </a> <br>
                </div>
                <div class="btn1">
                    <img src="images/Group 150.png" width="12" height="15" alt="">
                    <a href="{{ route('refund_policy') }}"> Cookie Notice </a> <br>
                </div>
            </div>
            <div class="col-lg-8 p-policytext">
                <div class="pri-text">
                    <h3>GDPR </h3>
                    <p>
                        Adwiseri’s GDPR Compliance <br>
                        Updated October 19, 2022 </p>
                    <p><strong>What it is, what we are doing, and what you can do</strong></p>
                    <p>The GDPR became enforceable on May 25, 2018, and increased oversight for global privacy rights and
                        compliance. We, at Adwiseri, have embraced GDPR requirements and this guide is intended to help our
                        customers understand Adwiseri’s GDPR posture. It is not intended as a thorough treatise on GDPR
                        application and should be read with this in mind.</p>
                    <p><strong>What is the GDPR?</strong></p>
                    <p>The General Data Protection Regulation (the “GDPR”) is a European data protection and privacy law
                        adopted April 14, 2016, which became officially enforceable beginning on May 25, 2018. The two (2)
                        year delay between adoption and enforcement was intended to give organizations time to prepare
                        before enforcement. </p>
                    <p>The GDPR is an ambitious attempt to strengthen, harmonize, and modernize EU data protection law and
                        enhance individual rights and freedoms, consistent with the European understanding of privacy as a
                        fundamental human right. The GDPR regulates, among other things, how individuals and organizations
                        may obtain, use, store, and erase personal data. It replaced a prior European Union privacy
                        directive known as Directive 95/46/EC (the “Directive”), which had been the basis of European data
                        protection law from 1995 to early 2018. Unlike its predecessor, the GDPR applies immediately
                        throughout the European Union (“EU”) across all member states without the need for further member
                        state legislative action. </p>
                    <p>Since mid-May 2018, the GDPR has been in force and there is no further “grace period.” It is
                        important that organizations impacted by the GDPR are now compliant with its provisions. </p>
                    <p><strong>How does the GDPR work?</strong></p>
                    <p>There are many principles and requirements introduced by the GDPR, so it is important to review the
                        GDPR in its entirety to ensure a full understanding of its requirements and how they may apply to
                        your organization. While the GDPR preserves many principles established by the Directive, it
                        introduces several important and ambitious changes. Here are a few that we believe are particularly
                        relevant to Adwiseri and our customers:</p>
                    <ol class="mb-3">
                        <li>
                            <p> Expansion of scope: The GDPR applies to all organizations established in the EU or
                                processing data of Data Subjects, thus introducing the concept of extraterritoriality, and
                                broadening the scope of EU data protection law well beyond the borders of just the EU.</p>
                        </li>
                        <li>
                            <p> Expansion of definitions of personal data and special categories of data.</p>
                        </li>
                        <li>
                            <p> Expansion of individual rights: Data Subjects have several important rights under the GDPR,
                                including the right to be forgotten, the right to object, the right to rectification, the
                                right of access, and the right of portability. Your organization must ensure that it can
                                accommodate these rights if it is processing the personal data of Data Subjects.</p>
                            <ul class="mb-3" type="disc">
                                <li> Right to be forgotten: An individual may request that an organization delete all data
                                    on that individual without undue delay.</li>
                                <li> Right to object: An individual may prohibit certain data uses.</li>
                                <li> Right to rectification: Individuals may request that incomplete data be completed or
                                    that incorrect data be corrected.</li>
                                <li> Right of access: Individuals have the right to know what data about them is being
                                    processed and how.</li>
                                <li> Right of portability: Individuals may request that personal data held by one
                                    organization be transported to another.</li>
                            </ul>
                        </li>
                        <li>
                            <p> Stricter consent requirements: Consent is one of the fundamental legal bases of the GDPR,
                                and organizations must ensure that consent is obtained in accordance with the GDPR’s
                                requirements. Your organization will need to obtain consent from its subscribers and
                                contacts for every usage of their personal data unless it can rely on a separate legal
                                basis. The route to compliance is to obtain explicit consent. Keep in mind that:</p>
                            <ul class="mb-3"type="disc">
                                <li> Consent must be specific to distinct purposes.</li>
                                <li> Silence, pre-populated boxes, or inactivity do not constitute consent; data subjects
                                    must explicitly opt-in to the storage, use, and management of their personal data.</li>
                                <li> Separate consent must be obtained for different processing activities, which means your
                                    organization must be clear about how the data will be used when consent is obtained.
                                </li>
                            </ul>
                        </li>
                        <li>
                            <p> Strict processing requirements: Individuals have the right to receive “fair and transparent”
                                information about the processing of their Personal Data, including:</p>
                            <ul class="mb-3" type="disc">
                                <li>Contact details for the data controller.</li>
                                <li>Purpose of the data: This should be as specific (“purpose limitation”) and minimized
                                    (“data minimization”) as possible. Your organization should carefully consider what data
                                    it is collecting and why, and be able to validate that to a regulator.</li>
                                <li>Retention period: This should be as short as possible (“storage limitation”).</li>
                                <li>Legal basis: An organization cannot process personal data just because it wants to. It
                                    must have a “legal basis” for doing so, such as where the processing is necessary to the
                                    performance of a contract, an individual has consented (see consent requirements above),
                                    or the processing is in the organization’s “legitimate interest.”</li>
                            </ul>
                        </li>
                    </ol>
                    <p><strong>Whom does it affect?</strong></p>
                    <p>As mentioned above, the territorial scope of the GDPR is very broad. The two most common GDPR
                        territorial conditions for application are, the GDPR applies (1) to the processing of personal data
                        in the context of the activities of an establishment of a controller or a processor in the Union,
                        regardless of whether the processing takes place in the Union or not; and (2) to the processing (a)
                        the offering of goods or services , irrespective of whether a payment of the data subject is
                        required , to such data subjects in the Union; or (b) the monitoring of their behavior as far as
                        their behavior takes place within the Union. The latter is the GDPR’s introduction of the principle
                        of “extraterritoriality” – meaning, the GDPR applies to any organization processing personal data of
                        data subjects —regardless of where it is established, and regardless of where its processing
                        activities take place. This means the GDPR could apply to any organization anywhere in the world,
                        and all organizations should perform an analysis to determine whether or not they are processing the
                        personal data of EU citizens. The GDPR also applies across all industries and sectors.</p>
                    <p>Here are a few definitions that will aid in understanding the GDPR’s broad scope.</p>
                    <p><strong>What is a “data subject”?</strong></p>
                    <p>The GDPR defines a Data Subject within its definition of “Personal Data” discussed below. A Data
                        Subject is an identifiable natural person who can be identified, directly or indirectly, in
                        particular by reference to an identifier, such as a name, an identification number, location data,
                        an online identifier or to one or more factors specific to the physical, psychological, genetic,
                        mental, economic, cultural or social identity of that natural person.</p>
                    <p>A Data Subject is not limited to EU Citizenship. The impact of this is apparent in the territorial
                        application of the GDPR described above. An organization processing personal data in the context of
                        an establishment in the EU means personal data processing of any identifiable natural person
                        regardless of the natural person’s physical location – provided the processing is in the context of
                        the establishment. An organization not established in the EU, but offering goods or services to a
                        Data Subject located within the EU also comes under the GDPR. Note that in this instance, in
                        addition to its application to a natural person, it also requires that the natural person be
                        physically present in the EU.</p>
                    <p><strong>What is considered “personal data”?</strong></p>
                    <p>The GDPR defines Personal Data as any information relating to an identified or identifiable natural
                        individual; meaning, information that could be used, on its own or in conjunction with other data,
                        to identify a Data Subject. Consider the extremely broad reach of this definition. Personal Data now
                        includes not only data that is commonly considered to be personal in nature (e.g., social security
                        numbers, names, physical addresses, email addresses), but also data such as IP addresses, behavioral
                        data, location data, biometric data, financial information, and much more. This means that, for
                        Adwiseri users, information that an organization collects about its subscribers and contacts will be
                        considered Personal Data under the GDPR. It’s also important to note that even Personal Data that
                        has been “pseudonymized” can be considered Personal Data if the pseudonym can be linked to any
                        particular individual, so due care should be made when evaluating its application. Classification of
                        data as Personal Data under the GDPR will require Organizations to comply with certain duties and
                        obligations relating to what can broadly be termed transparency involving the use of that Personal
                        Data – and this includes its security. </p>
                    <p>Special Categories of data, such as health information or information that reveals a person’s racial
                        or ethnic origin, will require even greater protection under the GDPR. An organization should not
                        store data of this nature within its Adwiseri account.</p>
                    <p><strong>What does it mean to “process” data?</strong></p>
                    <p>Processing under the GDPR is “any operation or set of operations which is performed on personal data
                        or on sets of personal data, whether or not by automated means, such as collection, recording,
                        organization, structuring, storage, adaptation or alteration, retrieval, consultation, use,
                        disclosure by transmission, dissemination or otherwise making available, alignment or combination,
                        restriction, erasure or destruction.” Basically, if your organization is collecting, managing, using
                        or storing any personal data of Data Subjects, it is processing EU personal data within the meaning
                        prescribed by the GDPR. This means, for example, that if any of its Adwiseri lists contain the email
                        address, name, or other personal data of any Data Subject, then your organization is processing EU
                        personal data under the GDPR. Application of the GDPR, of course, is contingent on meeting the
                        threshold territorial requirements explained above.</p>
                    <p>Keep in mind that even if your organization does not believe its business will be affected by the
                        GDPR, the GDPR and its underlying principles may still be important to it. European law tends to set
                        the trend for international privacy regulation, and increased privacy awareness now may give it a
                        competitive advantage later.</p>
                    <p><strong>Who processes Personal Data under the GDPR?</strong></p>
                    <p>If an organization ‘processes’ personal data, it does so as either a Controller or a Processor, and
                        there are different requirements and obligations for each. A Controller is the organization that
                        determines the purposes and means of processing personal data. A Controller also determines the
                        specific personal data that is collected from a data subject for processing. A Processor is the
                        organization that processes the data on behalf of the controller. Think of the Processor as a
                        service provider or vendor in the relationship.</p>
                    <p>The GDPR has not changed the fundamental definitions of Controller and Processor found in the
                        Directive, but it has expanded the responsibilities of each party. Controllers will retain primary
                        responsibility for data protection (including, for example, the obligation to report data breaches
                        to data protection authorities); however, the GDPR does place some direct responsibilities on the
                        Processor, as well. It is important to understand whether your organization is acting as a
                        Controller or a Processor, and to familiarize yourself with your responsibilities accordingly.</p>
                    <p>In the context of the Adwiseri application and our related services, in the majority of
                        circumstances, our customers are acting as the Controller. Our customers, for example, decide what
                        information from their contacts or subscribers is uploaded or transferred into their Adwiseri
                        account. How Adwiseri processes Personal Data is addressed below.</p>
                    <p><strong>How does Adwiseri comply with the GDPR?</strong></p>
                    <p>Adwiseri takes GDPR compliance very seriously and started GDPR preparation well before its effective
                        date. As part of this process, we reviewed (and updated where necessary) all of our internal
                        processes, procedures, systems, and documentation to ensure that we were ready when the GDPR went
                        into effect. Compliance is not a static accomplishment, mandating monitoring vigilance in the face
                        of changed circumstances and legal requirements. </p>
                    <p>One recent change involves the Court of Justice of the European Union (“CJEU”) ruling in what is
                        referred to as the Schrems II decision. This decision revolves around the transfer of Personal Data
                        from EU member states to third-party countries, such as the United States. The GDPR, like the
                        Directive, does not contain any specific requirement that the Personal Data of EU citizens be stored
                        only in EU member states. Rather, the GDPR requires that certain conditions be met before Personal
                        Data is transferred outside the EU, identifying a number of different legal grounds that
                        organizations can rely on to perform such data transfers. One legal ground for transferring Personal
                        Data set out in the GDPR is an “adequacy decision.” An adequacy decision is a decision by the
                        European Commission that an adequate level of protection exists for the Personal Data in the
                        country, territory, or organization where it is being transferred. The Schrems II decision
                        invalidated the adequacy decision for transatlantic data transfer to the United States known as
                        Privacy Shield II. Another impact resulting out of this decision involved the use of ‘standard
                        contractual clauses’ (SCCs) between the controller or processor and the controller, processor or the
                        recipient of the personal data in the third country or international organization. SCCs are a
                        commonly relied upon legal ground under the heading ‘appropriate safeguards’ where transfer of
                        personal data may only occur if appropriate safeguards are in place and that enforceable data
                        subject rights and effective legal remedies are available. Where the CJEU upheld the validity of
                        this safeguard, it established certain conditions for its use. </p>
                    <p>Adwiseri is committed to complying with the results of the Schrems II decision, and any other legal
                        mandates in the future and is monitoring developments – in particular with respect to European Data
                        Protection Board guidance publications and Supervisory Authority opinions. </p>
                    <p>As is our policy, we stand ready to address any requests made by our customers related to their
                        expanded individual rights under the GDPR. Generally speaking, these include:</p>
                    <ul class="mb-3">
                        <li> Right to be forgotten: You may terminate your Adwiseri account at any time.</li>
                        <li> Right to object: You may opt out of inclusion of your data in any data science projects.</li>
                        <li> Right to rectification: You may access and update your Adwiseri account settings at any time to
                            correct or complete your account information. You may also contact Adwiseri at any time to
                            access, correct, amend or delete information that we hold about you.</li>
                        <li> Right of access: Our Privacy Policy describes what data we collect and how we use it. If you
                            have specific questions about particular data, you can contact care@Adwiseri.com for further
                            information at any time.</li>
                        <li> Right of portability: You may request that we export your account data to a third party at any
                            time.</li>
                    </ul>
                    <p><strong>How does Adwiseri process Personal Data? </strong></p>
                    <p>Adwiseri, just like any other business, currently uses third-party Sub-processors to provide various
                        business functions like business analytics, cloud infrastructure, email notifications, payments, and
                        customer support. Prior to engaging with any third-party Sub-processor, Adwiseri performs due
                        diligence to evaluate their defensive disposition and executes an agreement requiring each
                        Sub-processor to maintain minimum acceptable security practices. We’ve listed our Suprocessors on a
                        separate page. We will keep this page up-to-date, please check back regularly to get updates on all
                        changes.</p>
                    <p><strong>Do you need to comply with the GDPR?</strong></p>
                    <p>As detailed above, the GDPR has broad extra-territorial reach and due consideration should be given
                        to its application in your organization’s business. We cannot stress enough that you should consult
                        with legal and other professional counsel regarding the full scope of your organizations’ compliance
                        obligations under the GDPR.</p>
                    <p><strong>What happens if you do not comply?</p>
                    <p>Non-compliance with the GDPR can result in enormous financial penalties. Sanctions for non-compliance
                        can be as high as 20 Million Euros or 4% of global annual turnover, whichever is higher.</p>
                    <p><strong>Where should I start?</strong></p>
                    <p>We’ve included the table below to help our customers think about GDPR and their responsibilities AND
                        how Adwiseri factors into the equation. This list is neither exclusive nor exhaustive.</p>
                    <table class="table table-bordered border">
                        <thead>
                            <tr>
                                <td>
                                    <p><strong>GDPR Requirement</strong></p>
                                </td>
                                <td>
                                    <p><strong>GDPR Reference</strong></p>
                                </td>
                                <td>
                                    <p><strong>Actor(s)</strong></p>
                                </td>
                                <td>
                                    <p><strong>Actions Taken</strong></p>
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <p>Lawful Basis</p>
                                </td>
                                <td>
                                    <p>Article 6, Article 11</p>
                                </td>
                                <td>
                                    <p>Shared</p>
                                </td>
                                <td>
                                    <p>Adwiseri: Establishes a lawful basis to process personal data. Data Subject: If the
                                        lawful basis is consent, Data Subject consents to Adwiseri&rsquo;s data collection
                                        about them.</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Processing children&rsquo;s personal data</p>
                                </td>
                                <td>
                                    <p>Article 8</p>
                                </td>
                                <td>
                                    <p>Adwiseri</p>
                                </td>
                                <td>
                                    <p>Does not distinguish between different types of personal data and does not knowingly
                                        collect children&rsquo;s personal data.</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Data protection by design</p>
                                </td>
                                <td>
                                    <p>Article 25</p>
                                </td>
                                <td>
                                    <p>Shared</p>
                                </td>
                                <td>
                                    <p>Adwiseri: Collects the minimum personal data necessary to carry out normal business
                                        operations. Customer: Manages content within Adwiseri&rsquo;s platform.</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Data Protection Impact Assessments</p>
                                </td>
                                <td>
                                    <p>Article 35</p>
                                </td>
                                <td>
                                    <p>Shared</p>
                                </td>
                                <td>
                                    <p>Adwiseri: Appoints responsible staff to perform any necessary Data Protection Impact
                                        Assessments. Customer: Determines level of content shared with business partners and
                                        may assist Adwiseri, as the processor.</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Encryption</p>
                                </td>
                                <td>
                                    <p>Article 32</p>
                                </td>
                                <td>
                                    <p>Shared</p>
                                </td>
                                <td>
                                    <p>Adwiseri and Customer (as a processor): Comply with security requirements. Personal
                                        Data is encrypted in transit and at rest using AES-256 bit encryption.&nbsp;</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>European Data Protection Board</p>
                                </td>
                                <td>
                                    <p>Article 68</p>
                                </td>
                                <td>
                                    <p>Shared</p>
                                </td>
                                <td>
                                    <p>Adwiseri and Customer (as a processor): Monitor European Data Protection Board
                                        Activity</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Personal data inventory</p>
                                </td>
                                <td>
                                    <p>Article 30</p>
                                </td>
                                <td>
                                    <p>Shared</p>
                                </td>
                                <td>
                                    <p>Adwiseri and Customer (as a processor): Comply with a record of processing activity
                                        requirements.</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Right to erasure</p>
                                </td>
                                <td>
                                    <p>Article 17</p>
                                </td>
                                <td>
                                    <p>Shared</p>
                                </td>
                                <td>
                                    <p>Adwiseri: Appoints responsible staff to respond to any exercise of this Right. Data
                                        Subject: exercises their right to erasure as Adwiseri provides.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div> <br>
                {{-- <div class="pri-text">
                    <p> Under no circumstances shall Creative Tim team be liable for any direct, indirect, special,
                        incidental or consequential damages, including, but not limited to, loss of data or profit,
                        arising out of the use, or the inability to use, the materials on this site, even if Creative
                        Tim team or an authorized representative has been advised of the possibility of such damages. If
                        your use of materials from this site results in the need for servicing, repair or correction of
                        equipment or data, you assume any costs thereof.</p>
                </div> <br>
                <div class="pri-text">
                    <p>Creative Tim will not be responsible for any outcome that may occur during the course of usage of
                        our resources.We reserve the rights to change prices and revise the resources usage policy in
                        any moment.</p>
                </div> <br>
                <div class="pri-text">
                    <p>Products <br>
                        All products and services are delivered by Creative Tim. You can access your download from your
                        dashboard.</p>
                </div>
                <div class="pri-text">
                    <p>Security <br>
                        Creative Tim does not process any order payments through the website. All payments are
                        processed securely through 2Checkout, a third party online payment provider.</p>
                </div>
                <div class="pri-text">
                    <p>Cookie Policy <br>
                        A cookie is a file containing an identifier (a string of letters and numbers) that
                        is sent by a web server to a web browser and is stored by the browser. The identifier is then
                        sent back to the server each time the browser requests a page from the server. Our website uses
                        cookies. By using our website and agreeing to this policy, you consent to our use of cookies in
                        accordance with the terms of this policy.</p>
                </div>
                <div class="pri-text">
                    <p>We use session cookies to personalise the website for each user. We use persistent cookies to
                        keep tracks of referrals coming from our affiliate network.</p>
                </div>
                <div class="pri-text">
                    <p>We use Google Analytics to analyse the use of our website. Our analytics service provider
                        generates statistical and other information about website use by means of cookies. Our analytics
                        service provider's privacy policy is available at: http://www.google.com/policies/privacy/.</p>
                </div>
                <div class="pri-text">
                    <p>Deleting cookies will have a negative impact on the usability of the site. If you block cookies,
                        you will not be able to use all the features on our website.</p>
                </div> --}}
            </div>
        </div>
    </div>
    <style>
        li {
            font-weight: 400;
            font-size: 13px;
            font-family: 'Lato';
        }
    </style>
@endsection()
