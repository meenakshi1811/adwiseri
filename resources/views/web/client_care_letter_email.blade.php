<p>Dear {{ $data['client']->name }},</p>

<p>Please find attached your {{ $data['document_title'] }} for your {{ $data['application_type'] }} matter.</p>

<p>This document includes your instructions, advice provided, agreed work scope, timelines, fees/disbursements and complaints guidance.</p>

<p>Kindly review, sign, and return it by replying to this email.</p>

<p>Regards,<br>{{ $data['adviser_name'] }}@if(($data['adviser_name'] ?? '') !== ($data['organisation_name'] ?? ''))<br>{{ $data['organisation_name'] }}@endif</p>
