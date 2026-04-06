<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $document_title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11.5px; color: #111; line-height: 1.45; }
        h1 { font-size: 18px; margin-bottom: 2px; }
        h2 { font-size: 13px; margin: 14px 0 6px; }
        p { margin: 0 0 8px; }
        .muted { color: #555; }
        .box { border: 1px solid #d9d9d9; padding: 8px; margin-bottom: 8px; }
        .signature { margin-top: 24px; }
        ul { margin: 0 0 8px 16px; padding: 0; }
        li { margin-bottom: 4px; }
    </style>
</head>
<body>
    <h1>{{ $document_title === 'Client Care Letter' ? 'Client Care Letter' : 'Service Agreement' }}</h1>
    <p class="muted">Our ref: {{ $reference_no }} | Issued on: {{ $issue_date }}</p>

    <h2>Introduction</h2>
    <p>Dear {{ $client->name }},</p>
    <p>Thank you for instructing {{ $organisation_name }}.</p>

    @if($letter_type === 'oisc_iaa')
        <p>We are a registered immigration advice and services provider regulated in the UK by the Immigration Advice Authority (IAA). Our IAA/OISC registration number is {{ $oisc_registration_number }}.</p>
        <p>We are authorised to provide immigration advice and services at {{ $authorisation_level }}. The regulator has the power to examine your file as part of its oversight role.</p>
    @endif

    <h2>Your Instructions</h2>
    <p>I write further to our discussion on {{ $consultation_date }}, when you instructed me to act for you in connection with your immigration matter.</p>
    <div class="box">
        <p><strong>Client identified for this matter:</strong> {{ $client->name }} (Client ID: {{ $client->id }})</p>
        <p><strong>Immigration status (if known):</strong> {{ $immigration_status }}</p>
        <p><strong>Application type:</strong> {{ $application_type }}</p>
        <p><strong>Application name:</strong> {{ $application_name }}</p>
        <p><strong>Client instructions:</strong> {{ $client_instructions }}</p>
    </div>

    <h2>Our Advice</h2>
    <div class="box">
        <p><strong>Advice given:</strong> {{ $advice_given }}</p>
        <p><strong>Work agreed to be done:</strong> {{ $work_agreed }}</p>
        <p><strong>Estimated timeline:</strong> {{ $estimated_timeline }}</p>
        <p><strong>Key dates:</strong> {{ $key_dates }}</p>
        <p><strong>Merits of the case:</strong> {{ $merits_of_case }}%</p>
        <p><strong>Merits notes:</strong> {{ $case_notes ?: 'Based on information currently available; merits may change as evidence evolves.' }}</p>
    </div>

    <h2>How We Work</h2>
    <p>I, {{ $adviser_name }}, am responsible for the conduct of your case and can be contacted by telephone on {{ $adviser_phone }} or email at {{ $adviser_email }}.</p>
    <p>My line manager is {{ $line_manager_name }} ({{ $line_manager_phone }} / {{ $line_manager_email }}).</p>
    <p>We will keep you informed of progress and key developments. We shall do our best to respond promptly, but immediate responses may not always be possible at busy times.</p>
    <p>If you need to see a member of staff, please arrange an appointment first. Office opening times are {{ $office_hours }}.</p>

    <h2>Professional Fees and Disbursements</h2>
    <div class="box">
        <p><strong>Fee details:</strong> {{ $fee_details }}</p>
        <p><strong>Agreed fixed fee:</strong> £{{ $fixed_fee }}</p>
        <p><strong>Estimated Home Office application fees:</strong> £{{ $home_office_fee }}</p>
        <p><strong>Estimated Immigration Health Surcharge:</strong> £{{ $ihs_fee }}</p>
        <p><strong>VAT note:</strong> {{ $vat_note }}</p>
        <p><strong>Additional costs client may incur:</strong> {{ $additional_costs }}</p>
        <p>Where money is held by us on your behalf for work not yet done, such money will remain your money in a separate client account until you are invoiced and payment is due.</p>
    </div>

    <h2>Documents and Responsibility</h2>
    <ul>
        <li>If you hand over original documents (for example passports or birth certificates), we will take care of them and provide copies to you as soon as reasonably practicable.</li>
        <li>We may outsource some aspects of work to appropriately authorised entities where required, but we retain full responsibility for all work done on your behalf.</li>
        <li>Your file will be retained for up to 6 years after closure, after which it may be destroyed unless you arrange collection.</li>
    </ul>

    <h2>Complaints Procedure</h2>
    <p>{{ $complaint_handling_details }}</p>
    @if($letter_type === 'oisc_iaa')
        <p>If we cannot resolve your complaint, you may contact IAA via portal: https://portal.oisc.gov.uk/s/complaints, or email: info@immigrationadviceauthority.gov.uk.</p>
        <p>IAA postal address: Immigration Advice Authority, PO Box 567, Dartford, KENT, DA1 9WX.</p>
    @endif

    <h2>Terms and Conditions</h2>
    <p>This letter forms the basis of our agreement with you. All terms and conditions applicable to your case, including complaint handling and data handling, apply to this engagement.</p>
    @if($letter_type === 'oisc_iaa')
        <p>If this agreement is a distance/off-premises contract and you are an individual consumer, you may have a statutory 14-day cancellation right under applicable law.</p>
    @endif

    <p>Our organisation contact details are: {{ $organisation_name }}, {{ $organisation_address }}, {{ $organisation_phone }}, {{ $organisation_email }}.</p>
    <p>If our contact details change, we will notify you promptly in writing.</p>

    @if(!empty($correction_note))
        <h2>Correction Note</h2>
        <p>{{ $correction_note }}</p>
    @endif

    <h2>Conclusion</h2>
    <p>Finally, thank you for instructing us. Please sign, date, and return this letter to confirm you understand and agree to its contents.</p>

    <div class="signature">
        <p>Yours sincerely,</p>
        <p>{{ $adviser_name }}</p>
        @if(strcasecmp(trim((string) $organisation_name), trim((string) $adviser_name)) !== 0)
            <p>{{ $organisation_name }}</p>
        @endif
        <br>
        <p><strong>Client Signature:</strong> ____________________________</p>
        <p><strong>Dated:</strong> ____________________________</p>
    </div>
</body>
</html>
