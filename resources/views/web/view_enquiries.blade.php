@extends('web.layout.main')

@section('main-section')

<div class="col-lg-10 column-client">
<div class="client-dashboard">

<div class="d-flex justify-content-between align-items-center mb-4">
<h3 class="text-primary m-0">Visa Enquiry Details</h3>

<a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary">
<i class="fa fa-arrow-left"></i> Back
</a>
</div>


<div class="card shadow-sm border-0">
<div class="card-body">


{{-- PERSONAL DETAILS --}}

<div class="mb-5">
<h5 class="section-title">Personal Details</h5>

<div class="row g-3">

<div class="col-md-4">
<label class="field-label">Full Name</label>
<div class="field-value">{{ $enquiry->full_name }}</div>
</div>

<div class="col-md-4">
<label class="field-label">DOB</label>
<div class="field-value">{{ $enquiry->dob ?? '-' }}</div>
</div>

<div class="col-md-4">
<label class="field-label">Email</label>
<div class="field-value">{{ $enquiry->email }}</div>
</div>

<div class="col-md-4">
<label class="field-label">Contact</label>
<div class="field-value">{{ $enquiry->contact_no ?? '-' }}</div>
</div>

<div class="col-md-4">
<label class="field-label">Marital Status</label>
<div class="field-value">{{ $enquiry->marital_status ?? '-' }}</div>
</div>

<div class="col-md-12">
<label class="field-label">Address</label>
<div class="field-value">{{ $enquiry->address ?? '-' }}</div>
</div>

</div>
</div>


{{-- COUNTRY PREFERENCE --}}

<div class="mb-5">
<h5 class="section-title">Country & Visa Preference</h5>

<div class="row g-3">

<div class="col-md-4">
<label class="field-label">1st Preference</label>
<div class="field-value">{{ $enquiry->country_pref_1 ?? '-' }}</div>
</div>

<div class="col-md-4">
<label class="field-label">2nd Preference</label>
<div class="field-value">{{ $enquiry->country_pref_2 ?? '-' }}</div>
</div>

<div class="col-md-4">
<label class="field-label">3rd Preference</label>
<div class="field-value">{{ $enquiry->country_pref_3 ?? '-' }}</div>
</div>

<div class="col-md-4">
<label class="field-label">Visa Category</label>
<div class="field-value">{{ $enquiry->visa_category ?? '-' }}</div>
</div>

</div>
</div>


{{-- RESIDENCY HISTORY --}}

<div class="mb-5">

<h5 class="section-title">Abroad Residency History</h5>

<div class="table-responsive">
<table class="table table-bordered table-striped">

<thead class="table-light">
<tr>
<th>Country</th>
<th>Duration</th>
<th>Visa Category</th>
</tr>
</thead>

<tbody>

@forelse($enquiry->residencies ?? [] as $row)

<tr>
<td>{{ $row->country }}</td>
<td>{{ $row->duration }}</td>
<td>{{ $row->visa_category }}</td>
</tr>

@empty
<tr>
<td colspan="3" class="text-center text-muted">No records</td>
</tr>
@endforelse

</tbody>

</table>
</div>

</div>


{{-- TRAVEL HISTORY --}}

<div class="mb-5">

<h5 class="section-title">Travel History</h5>

<div class="table-responsive">
<table class="table table-bordered table-striped">

<thead class="table-light">
<tr>
<th>Country</th>
<th>Duration</th>
</tr>
</thead>

<tbody>

@forelse($enquiry->travels ?? [] as $row)

<tr>
<td>{{ $row->country }}</td>
<td>{{ $row->duration }}</td>
</tr>

@empty
<tr>
<td colspan="2" class="text-center text-muted">No records</td>
</tr>
@endforelse

</tbody>

</table>
</div>

</div>


{{-- WORK EXPERIENCE --}}

<div class="mb-5">

<h5 class="section-title">Work Experience</h5>

<div class="table-responsive">
<table class="table table-bordered table-striped">

<thead class="table-light">
<tr>
<th>Job Title</th>
<th>Employer</th>
<th>Country</th>
<th>Joining Date</th>
</tr>
</thead>

<tbody>

@forelse($enquiry->workExperiences ?? [] as $row)

<tr>
<td>{{ $row->job_title }}</td>
<td>{{ $row->employer }}</td>
<td>{{ $row->work_country }}</td>
<td>{{ $row->joining_date }}</td>
</tr>

@empty
<tr>
<td colspan="4" class="text-center text-muted">No records</td>
</tr>
@endforelse

</tbody>

</table>
</div>

</div>


{{-- CHILDREN --}}

<div class="mb-5">

<h5 class="section-title">Children Details</h5>

<div class="table-responsive">

<table class="table table-bordered table-striped">

<thead class="table-light">
<tr>
<th>Name</th>
<th>Age</th>
<th>Gender</th>
<th>DOB</th>
</tr>
</thead>

<tbody>

@forelse($enquiry->children ?? [] as $child)

<tr>
<td>{{ $child->child_name }}</td>
<td>{{ $child->child_age }}</td>
<td>{{ $child->child_gender }}</td>
<td>{{ $child->child_dob }}</td>
</tr>

@empty
<tr>
<td colspan="4" class="text-center text-muted">No records</td>
</tr>
@endforelse

</tbody>

</table>

</div>

</div>


{{-- SIGNATURE --}}

<div>

<h5 class="section-title">Declaration</h5>

<div class="row">

<div class="col-md-3">
<label class="field-label">Date</label>
<div class="field-value">{{ $enquiry->form_date }}</div>
</div>

<div class="col-md-3">
<label class="field-label">Place</label>
<div class="field-value">{{ $enquiry->place }}</div>
</div>

<div class="col-md-3">
<label class="field-label">Print Name</label>
<div class="field-value">{{ $enquiry->print_name }}</div>
</div>

<div class="col-md-3">
<label class="field-label">Signature</label>

@if($enquiry->signature)
<img src="{{ $enquiry->signature }}" class="img-fluid border p-1" style="max-height:120px">
@endif

</div>

</div>

</div>


</div>
</div>

</div>
</div>

<style>

.section-title{
font-weight:600;
margin-bottom:15px;
padding-bottom:5px;
border-bottom:2px solid #e5e5e5;
}

.field-label{
font-size:13px;
color:#666;
margin-bottom:2px;
display:block;
}

.field-value{
font-size:14px;
font-weight:500;
color:#222;
}

</style>

@endsection