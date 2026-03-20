@extends('web.layout.main')

@section('main-section')

<div class="container mt-5 mb-5 pt-5">
<div class="row">

<div class="col-lg-2"></div>

<div class="col-lg-8 loginouter-box login-box">
@if(session('success'))

<div class="alert alert-success">
{{ session('success') }}
</div>

@endif


@if(session('error'))

<div class="alert alert-danger">
{{ session('error') }}
</div>

@endif
<form method="POST" action="{{ route('visa.enquiry.store') }}">
@csrf

<input type="hidden" name="subscriber_id" value="{{ $subscriberId }}">

<h3 class="mb-4 text-center">Visa / Immigration Enquiry Form</h3>

<h5 class="mt-3">1. Personal Details</h5>

<div class="row">

<div class="col-md-6 mb-3">
<label>Full Name *</label>
<input type="text" name="full_name" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>DOB</label>
<input type="text" name="dob" class="form-control datepicker">
</div>

<div class="col-md-6 mb-3">
<label>Email *</label>
<input type="email" name="email" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>Contact No</label>
<input type="text" name="contact_no" class="form-control">
</div>

<div class="col-md-6 mb-3">
<label>Marital Status</label>
<select name="marital_status" id="marital_status" class="form-control">
<option value="">Select</option>
<option>Single</option>
<option>Engaged</option>
<option>Married</option>
<option>Divorced</option>
<option>Widowed</option>
</select>
</div>

<div class="col-md-12 mb-3">
<label>Address *</label>
<textarea name="address" class="form-control"></textarea>
</div>

</div>

<h5 class="mt-4">2. Country & Visa Preference</h5>

<div class="row">

<div class="col-md-4">
<input type="text" name="country_pref[]" class="form-control" placeholder="1st Preference">
</div>

<div class="col-md-4">
<input type="text" name="country_pref[]" class="form-control" placeholder="2nd Preference">
</div>

<div class="col-md-4">
<input type="text" name="country_pref[]" class="form-control" placeholder="3rd Preference">
</div>

<div class="col-md-6 mt-3">
<label>Visa Category</label>
<select name="visa_category" id="visa_category" class="form-control">
<option value="">Select</option>
<option value="Visit">Visit</option>
<option value="Training">Training</option>
<option value="Study">Study</option>
<option value="Work">Work</option>
<option value="Dependent">Dependent</option>
<option value="PR">PR</option>
<option value="Business">Business</option>
<option value="Investor">Investor</option>
</select>
</div>

</div>

<h5 class="mt-4">3. Abroad Residency History</h5>

<div id="residency_history">

<div class="row residency-row">

<div class="col-md-3">
<input type="text" name="res_country[]" class="form-control" placeholder="Country">
</div>

<div class="col-md-3">
<input type="text" name="res_duration[]" class="form-control" placeholder="Duration">
</div>

<div class="col-md-3">
<input type="text" name="res_visa[]" class="form-control" placeholder="Visa Category">
</div>

<div class="col-md-3">
<button type="button" class="btn btn-success addResidency">+</button>
</div>

</div>

</div>

<h5 class="mt-4">4. Travel History</h5>

<div id="travel_history">

<div class="row travel-row">

<div class="col-md-4">
<input type="text" name="travel_country[]" class="form-control" placeholder="Country">
</div>

<div class="col-md-4">
<input type="text" name="travel_duration[]" class="form-control" placeholder="Duration">
</div>

<div class="col-md-4">
<button type="button" class="btn btn-success addTravel">+</button>
</div>

</div>

</div>

<h5 class="mt-4">5. Visa Refusal History</h5>

<div id="refusal_history">

<div class="row refusal-row">

<div class="col-md-3">
<input type="text" name="refusal_country[]" class="form-control" placeholder="Country">
</div>

<div class="col-md-3">
<input type="text" name="refusal_date[]" class="form-control datepicker">
</div>

<div class="col-md-4">
<input type="text" name="refusal_reason[]" class="form-control" placeholder="Reason">
</div>

<div class="col-md-2">
<button type="button" class="btn btn-success addRefusal">+</button>
</div>

</div>

</div>

<h5 class="mt-4">6. Educational Qualifications</h5>

<div class="row">

<div class="col-md-3">
<select name="qualification" class="form-control">
<option>10th</option>
<option>12th</option>
<option>Diploma</option>
<option>Bachelor’s Degree</option>
<option>Master’s Degree</option>
<option>PhD</option>
</select>
</div>

<div class="col-md-4">
<input type="text" name="institution" class="form-control" placeholder="Institution Name">
</div>

<div class="col-md-2">
<input type="text" name="passing_year" class="form-control" placeholder="Year">
</div>

<div class="col-md-3">
<input type="text" name="grade" class="form-control" placeholder="Result / Grade">
</div>

</div>

<h5 class="mt-4">7. English Language Competency</h5>

<div class="row">

<div class="col-md-4">
<select name="english_test" class="form-control">
<option>IELTS</option>
<option>TOEFL</option>
<option>TOEIC</option>
<option>PTE</option>
<option>DuoLingo</option>
</select>
</div>

<div class="col-md-4">
<input type="text" name="overall_score" class="form-control" placeholder="Overall Score">
</div>

<div class="col-md-4">
<input type="text" name="test_date" class="form-control datepicker">
</div>

</div>

<h5 class="mt-4">8. Work Experience</h5>

<div id="work_experience">

<div class="row work-row">

<div class="col-md-3">
<input type="text" name="job_title[]" class="form-control" placeholder="Job Title">
</div>

<div class="col-md-3">
<input type="text" name="employer[]" class="form-control" placeholder="Employer Name">
</div>

<div class="col-md-2">
<input type="text" name="work_country[]" class="form-control" placeholder="Country">
</div>

<div class="col-md-2">
<input type="text" name="joining_date[]" class="form-control datepicker">
</div>

<div class="col-md-2">
<button type="button" class="btn btn-success addWork">+</button>
</div>

</div>

</div>

<div id="spouse_section" style="display:none">

<h5 class="mt-4">9. Spouse Personal Details</h5>

<div class="row">

<div class="col-md-6 mb-3">
<input type="text" name="spouse_name" class="form-control" placeholder="Spouse Name">
</div>

<div class="col-md-6 mb-3">
<input type="email" name="spouse_email" class="form-control" placeholder="Spouse Email">
</div>

<div class="col-md-6 mb-3">
<input type="text" name="spouse_dob" class="form-control datepicker">
</div>

<div class="col-md-6 mb-3">
<input type="text" name="spouse_contact" class="form-control" placeholder="Contact No">
</div>

</div>

</div>

<h5 class="mt-4">Do you have Children?</h5>

<select id="children_option" class="form-control mb-3">
<option value="no">No</option>
<option value="yes">Yes</option>
</select>

<div id="children_section" style="display:none">

<div id="children_rows">

<div class="row child-row">

<div class="col-md-3">
<input type="text" name="child_name[]" class="form-control" placeholder="Name">
</div>

<div class="col-md-2">
<input type="number" name="child_age[]" class="form-control" placeholder="Age">
</div>

<div class="col-md-2">
<select name="child_gender[]" class="form-control">
<option>M</option>
<option>F</option>
<option>PNTS</option>
</select>
</div>

<div class="col-md-3">
<input type="text" name="child_dob[]" class="form-control datepicker">
</div>

<div class="col-md-2">
<button type="button" class="btn btn-success addChild">+</button>
</div>

</div>

</div>

</div>

<!-- STUDENT VISA FUNDING -->

<div id="student_funding_section" style="display:none">

<h5 class="mt-4">What is the prime source of funding for your abroad study?</h5>

<div class="form-check">
<input class="form-check-input" type="checkbox" name="funding[]" value="Own Savings"> Own Savings
</div>

<div class="form-check">
<input class="form-check-input" type="checkbox" name="funding[]" value="Bank Finance"> Bank Finance
</div>

<div class="form-check">
<input class="form-check-input" type="checkbox" name="funding[]" value="Family"> Sponsored by Family
</div>

<div class="form-check">
<input class="form-check-input" type="checkbox" name="funding[]" value="Employer"> Sponsored by Employer
</div>

<div class="form-check">
<input class="form-check-input" type="checkbox" name="funding[]" value="Government"> Sponsored by Institution/Government
</div>

</div>

<!-- DATE PLACE SIGN -->

<div class="row mt-4">

<div class="col-md-3">
<label>Date</label>
<div class="input-group">
<input type="text" name="form_date" class="form-control datepicker">
</div>
</div>
<div class="col-md-3">
<label>Place</label>
<input type="text" name="place" class="form-control">
</div>

<div class="col-md-3">
<label>Print Name</label>
<input type="text" name="print_name" class="form-control">
</div>
<div class="col-md-6">
<label>Signature</label>

<div style="border:1px solid #ccc;border-radius:6px;padding:10px">

<canvas id="signature-pad" style="width:100%;height:150px;border:1px solid #ddd;"></canvas>

<input type="hidden" name="signature" id="signature">

<div class="mt-2">
<button type="button" class="btn btn-sm btn-secondary" id="clear-signature">Clear</button>
</div>

</div>

</div>


</div>

<!-- CAPTCHA -->

<div class="mt-4">

{!! NoCaptcha::display() !!}

</div>

<div class="mt-4">

<label>
<input type="checkbox" required> I agree to <a href="/privacy_policy" target="_blank">Privacy Policy</a>
</label>

<br>

<label>
<input type="checkbox" required> I agree to <a href="/terms_of_use" target="_blank">Terms of Service</a>
</label>

</div>

<button class="btn btn-primary mt-4 w-100">Submit Enquiry</button>

</form>

</div>

<div class="col-lg-2"></div>

</div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
{!! NoCaptcha::renderJs() !!}

<script>

document.addEventListener("DOMContentLoaded", function () {

    flatpickr(".datepicker", {
        dateFormat: "d-m-Y",
        allowInput: true
    });

});
$(document).ready(function(){

var canvas = document.getElementById('signature-pad');

if(canvas){

var signaturePad = new SignaturePad(canvas);

function resizeCanvas() {
var ratio =  Math.max(window.devicePixelRatio || 1, 1);
canvas.width = canvas.offsetWidth * ratio;
canvas.height = canvas.offsetHeight * ratio;
canvas.getContext("2d").scale(ratio, ratio);
signaturePad.clear();
}

window.addEventListener("resize", resizeCanvas);
resizeCanvas();

$('#clear-signature').click(function(){
signaturePad.clear();
$('#signature').val('');
});

$('form').on('submit', function(){

if(!signaturePad.isEmpty()){
var dataURL = signaturePad.toDataURL();
$('#signature').val(dataURL);
}

});

}

$('#marital_status').change(function(){
if($(this).val()=='Married'){ $('#spouse_section').show(); }
else{ $('#spouse_section').hide(); }
});

$('#children_option').change(function(){
if($(this).val()=='yes'){ $('#children_section').show(); }
else{ $('#children_section').hide(); }
});

$('#visa_category').change(function(){

if($(this).val()=='Study'){
$('#student_funding_section').show();
}
else{
$('#student_funding_section').hide();
}

});

function addRow(container,html){ $(container).append(html); }

$(document).on('click','.addResidency',function(){
addRow('#residency_history',
'<div class="row mt-2">'+
'<div class="col-md-3"><input type="text" name="res_country[]" class="form-control"></div>'+
'<div class="col-md-3"><input type="text" name="res_duration[]" class="form-control"></div>'+
'<div class="col-md-3"><input type="text" name="res_visa[]" class="form-control"></div>'+
'<div class="col-md-3"><button type="button" class="btn btn-danger remove">-</button></div>'+
'</div>');
});

$(document).on('click','.addTravel',function(){
addRow('#travel_history',
'<div class="row mt-2">'+
'<div class="col-md-4"><input type="text" name="travel_country[]" class="form-control"></div>'+
'<div class="col-md-4"><input type="text" name="travel_duration[]" class="form-control"></div>'+
'<div class="col-md-4"><button type="button" class="btn btn-danger remove">-</button></div>'+
'</div>');
});

$(document).on('click','.addRefusal',function(){
addRow('#refusal_history',
'<div class="row mt-2">'+
'<div class="col-md-3"><input type="text" name="refusal_country[]" class="form-control"></div>'+
'<div class="col-md-3"><input type="text" name="refusal_date[]" class="form-control datepicker"></div>'+
'<div class="col-md-4"><input type="text" name="refusal_reason[]" class="form-control"></div>'+
'<div class="col-md-2"><button type="button" class="btn btn-danger remove">-</button></div>'+
'</div>');
});

$(document).on('click','.addWork',function(){
addRow('#work_experience',
'<div class="row mt-2">'+
'<div class="col-md-3"><input type="text" name="job_title[]" class="form-control"></div>'+
'<div class="col-md-3"><input type="text" name="employer[]" class="form-control"></div>'+
'<div class="col-md-2"><input type="text" name="work_country[]" class="form-control"></div>'+
'<div class="col-md-2"><input type="text" name="joining_date[]" class="form-control datepicker"></div>'+
'<div class="col-md-2"><button type="button" class="btn btn-danger remove">-</button></div>'+
'</div>');
});

$(document).on('click','.addChild',function(){
addRow('#children_rows',
'<div class="row mt-2">'+
'<div class="col-md-3"><input type="text" name="child_name[]" class="form-control"></div>'+
'<div class="col-md-2"><input type="number" name="child_age[]" class="form-control"></div>'+
'<div class="col-md-2"><select name="child_gender[]" class="form-control"><option>M</option><option>F</option><option>PNTS</option></select></div>'+
'<div class="col-md-3"><input type="text" name="child_dob[]" class="form-control datepicker"></div>'+
'<div class="col-md-2"><button type="button" class="btn btn-danger remove">-</button></div>'+
'</div>');
});

$(document).on('click','.remove',function(){ $(this).closest('.row').remove(); });

});

</script>

@endsection
