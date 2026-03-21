@extends('web.layout.main')

<style>
    /* .dt-buttons {
    position: absolute !important;
    right: 300px !important;
    top: 11px !important;
    float: none;
} */
    table thead tr th,
    table tr {
        text-align: center !important;
    }

    .buttons-csv,
    .buttons-excel,
    .buttons-pdf {
        font-weight: bold;
    }

    .second-table-heading {
        margin-top: 32px;
    }

    .nav-link.active {
        background-color: #15cfcf !important;
        color: white !important;
        border: 1px solid #15cfcf;
    }

    table.dataTable td.dataTables_empty,
    table.dataTable th.dataTables_empty {
        text-align: center;
        color: red;
    }
</style>
@section('main-section')
@php

use App\Models\UserRoles;
$client_roles = UserRoles::where('user_id', '=', $user->id)
->where('module', '=', 'Clients')
->first();
$application_roles = UserRoles::where('user_id', '=', $user->id)
->where('module', '=', 'Applications')
->first();
$communication_roles = UserRoles::where('user_id', '=', $user->id)
->where('module', '=', 'Communication')
->first();
$invoice_roles = UserRoles::where('user_id', '=', $user->id)
->where('module', '=', 'Invoices')
->first();
$payment_roles = UserRoles::where('user_id', '=', $user->id)
->where('module', '=', 'Payments')
->first();
$report_roles = UserRoles::where('user_id', '=', $user->id)
->where('module', '=', 'Reports')
->first();
$subscription_roles = UserRoles::where('user_id', '=', $user->id)
->where('module', '=', 'Subscription')
->first();
$setting_roles = UserRoles::where('user_id', '=', $user->id)
->where('module', '=', 'Settings')
->first();
$support_roles = UserRoles::where('user_id', '=', $user->id)
->where('module', '=', 'Support')
->first();
@endphp
<style>
    .nav-item {
        margin: 0px;
        --bs-nav-tabs-border-radius: 0px
    }
</style>
<div class="col-lg-10 column-client">
    <div class="client-dashboard">
        <div class="client-btn d-flex justify-content-between ">
            <h3 class="text-primary px-3">Reports</h3>
        </div>


        <div class="row">
            <div class="col-12">

                <ul class="nav nav-tabs border" id="myTab" role="tablist">
                    @if ($user->user_type == 'admin')
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" onclick="onClickSubscribers()" id="subscribers-tab"
                            data-bs-toggle="tab" data-bs-target="#subscribers" type="button" role="tab"
                            aria-controls="subscribers" aria-selected="true">Subscribers</button>
                    </li>
                    @endif
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $user->user_type == 'admin' ? '' : 'active' }} "
                            onclick="onClickClients()" id="client-tab" data-bs-toggle="tab" data-bs-target="#client"
                            type="button" role="tab" aria-controls="client" aria-selected="true">Clients</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" onclick="onClickApplication()" id="application-tab"
                            data-bs-toggle="tab" data-bs-target="#application" type="button" role="tab"
                            aria-controls="application" aria-selected="false">Applications</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" onclick="onClickUsers()" id="users-tab" data-bs-toggle="tab"
                            data-bs-target="#users" type="button" role="tab" aria-controls="users"
                            aria-selected="false">Users</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" onclick="onClickDocuments()" id="documents-tab" data-bs-toggle="tab"
                            data-bs-target="#documents" type="button" role="tab" aria-controls="documents"
                            aria-selected="false">Documents</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" onclick="onClickCommunications()" id="communication-tab"
                            data-bs-toggle="tab" data-bs-target="#communication" type="button" role="tab"
                            aria-controls="communication" aria-selected="false">Communications</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" onclick="onClickInvoices()" id="invocies-tab" data-bs-toggle="tab"
                            data-bs-target="#invocies" type="button" role="tab" aria-controls="invocies"
                            aria-selected="false">Invoices (AR)</button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" onclick="onClickInvoicesAP()" id="invocies-ap-tab" data-bs-toggle="tab"
                            data-bs-target="#invocies_ap" type="button" role="tab" aria-controls="invocies_ap"
                            aria-selected="false">Invoices (AP)</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" onclick="onClickPayments()" id="payments-tab" data-bs-toggle="tab"
                            data-bs-target="#payments" type="button" role="tab" aria-controls="payments"
                            aria-selected="false">Payments (AR)</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" onclick="onClickPaymentsAP()" id="paymentsap-tab" data-bs-toggle="tab"
                            data-bs-target="#paymentsap" type="button" role="tab" aria-controls="paymentsap"
                            aria-selected="false">Payments (AP)</button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" onclick="onClickReferrals()" id="refferals-tab" data-bs-toggle="tab"
                            data-bs-target="#refferals" type="button" role="tab" aria-controls="refferals"
                            aria-selected="false">Referrals</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" onclick="onClickWallets()" id="wallet-tab" data-bs-toggle="tab"
                            data-bs-target="#wallet" type="button" role="tab" aria-controls="wallet"
                            aria-selected="false">Wallet</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" onclick="onClickSupportTickets()" id="SupportTickets-tab"
                            data-bs-toggle="tab" data-bs-target="#SupportTickets" type="button" role="tab"
                            aria-controls="SupportTickets" aria-selected="false">Support Tickets</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" onclick="onClickActivityLogs()" id="ActivityLog-tab"
                            data-bs-toggle="tab" data-bs-target="#ActivityLog" type="button" role="tab"
                            aria-controls="ActivityLog" aria-selected="false">Activity Log</button>
                    </li>
                    @if ($user->user_type == 'admin')
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" onclick="onClickAffiliates()" id="Affiliates-tab"
                            data-bs-toggle="tab" data-bs-target="#Affiliates" type="button" role="tab"
                            aria-controls="Affiliates" aria-selected="false">Affiliates</button>
                    </li>
                    


                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $user->user_type == 'admin' ? '' : 'active' }}"
                            onclick="onClickDemoRequest()" id="demo-request-tab" data-bs-toggle="tab"
                            data-bs-target="#demo-request" type="button" role="tab"
                            aria-controls="demo-request" aria-selected="true">Demo Request</button>
                    </li>
                    
                    @endif
                </ul>
                <div class="tab-content pt-2 px-2"
                    style="border-left: 1px #dee2e6 solid; border-right: 1px #dee2e6 solid; border-bottom: 1px #dee2e6 solid;"
                    id="myTabContent">
                    @if ($user->user_type == 'admin')
                    <div class="tab-pane fade show active" id="subscribers" role="tabpanel"
                        aria-labelledby="subscribers-tab">


                        <div style="display: flex; justify-content: center; align-items: center; text-align: center;" class="row">
                            <div class="col-4 my-3 d-flex align-items-center">
                                <label style="width: 180px" class="mx-2">Filter By Attribute</label>
                                <select id="subscriberfilter" class="form-select" name="reportName"
                                    onchange="onchangeSubscriberReport(this.value)">
                                    <option value=" " selected>Select Attribute</option>
                                    <option value="country">By Country</option>
                                    <option value="category">By Category</option>
                                    <option value="subcategory">By Subcategory</option>
                                    <option value="plan_type">By Plan Type</option>
                                    <option value="subscriptionDuration">By Subscription Duration</option>
                                    <option value="clients">By No. of Clients</option>
                                    <option value="users">By No. of Users</option>
                                    <option value="referrals">By Referrals</option>
                                    <option value="wallet">By Wallet Amount</option>
                                    <option value="application">By No. of Applications</option>
                                    <option value="document">By Documents Stored</option>
                                </select>
                            </div>
                            <div class="col-6 my-3 d-flex align-items-center ">

                                <label style="width: 180px" for="custom_date_picker">Select Duration</label>
                                <input type="text" id="custom_date_picker" name="custom_date_picker"
                                    placeholder="Select Duration" class="form-control">

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id="report" style="display:none">
                                    <h3 id="reportTitle1"></h3>
                                    <table class="fl-table table table-hover table-responsive p-0 m-0"
                                        style="width:100%;" id="subscriberTable">
                                        <thead>

                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="table-wrapper">
                            <table class="fl-table table table-hover table-responsive p-0 m-0" style="width:100%"
                                id="subscribersTable1">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Country</th>
                                        <th>City</th>
                                        <th>Postcode</th>
                                        <th>Plan</th>
                                        <th>Status</th>
                                        {{-- <th>Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade {{ $user->user_type == 'admin' ? '' : 'show active' }}"
                        id="demo-request" role="tabpanel" aria-labelledby="demo-request-tab">
                        <div style="display: flex; justify-content: center; align-items: center; text-align: center;" class="row">
                            <div class="col-4 my-3 d-flex align-items-center">
                                <label class="w-100" for="">Filter By Attribute</label>
                                <select id="demoRequestFilter" class="form-select w-100" name=""
                                    onchange="onChangeDemoRequest(this.value,this.options[this.selectedIndex].text)">
                                    <option value="" selected>Select Attribute</option>
                                    <option value="byStatus">By Status</option>
                                    <option value="byCountry">By Country </option>
                                    <option value="byTimeline">By Timeline (Duration)</option>
                                    <option value="byTimeTaken">By Time Taken</option>
                                </select>
                            </div>
                            <div class="col-6 my-3 d-flex align-items-center ">
                                <label style="width: 180px;">Select Duration</label>
                                <input type="text" id="custom_date_picker11" name="custom_date_picker"
                                    placeholder="Select Duration" class="form-control">

                            </div>
                        </div>



                        <div class="row">
                            <div class="col-12">
                                <div id="demo-report">
                                    <h3 id="reportdemoTitle1"></h3>
                                    <table class="fl-table table table-hover table-responsive p-0 m-0"
                                        style="width:100%;" id="demoRequestTable">
                                        <thead>

                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="table-wrapper">
                            <table class="fl-table table table-hover table-responsive p-0 m-0" style="width:100%"
                                id="demoRequestTable1">
                                <thead>
                                    <tr>
                                        <th style="width:5%;">ID</th>
                                        <th style="width:20%;">Name</th>
                                        <th style="width:20%;">Email</th>
                                        <th style="width:10%;">Phone</th>
                                        <th style="width:10%;">Country</th>
                                        <th style="width:10%;">Job Title</th>
                                        <th style="width:10%;">Hear By</th>
                                        <th style="width:7%;">Served By</th>
                                        <th style="width:7%;">Request Date</th>
                                        <th style="width:7%;">ServiceDate</th>
                                        <th style="width:8%;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                    <div class="tab-pane fade {{ $user->user_type == 'admin' ? '' : 'show active' }} " id="client"
                        role="tabpanel" aria-labelledby="client-tab">

                        <div style="display: flex; justify-content: center; align-items: center; text-align: center;" class="row">
                            <div class="col-4 my-3 d-flex align-items-center">
                                <label class="mr-4 w-50 fw-bold" for="">Filter By Attribute</label>
                                <select id="clientfilter" class="form-select" name=""
                                    onchange="onchangeClientReport(this.value,this.options[this.selectedIndex].text)">
                                    <option value="" selected>Select Attribute</option>
                                    <option value="homeCountry">By Home Country</option>
                                    {{-- <option value="destination">By Visa Country(Destinations)</option> --}}
                                    <option value="ageGroup">By Age Group</option>
                                    {{-- <option value="Gender">By Gender</option> --}}
                                    <option value="appType">By Application Type</option>
                                    <option value="applications">By Total Number of Applications</option>
                                    {{-- <option value="dependents">By No. of Dependents</option> --}}
                                    <option value="payment_mode">By Payment Mode</option>
                                    <option value="paymentAmount">By Outstanding Payments Amount</option>
                                    <option value="document">By No. of Documents Stored</option>
                                    <option value="dependants">By No. of Dependants</option>                                    
                                    <option value="yearly">By Year</option>
                                    <option value="byTimeline">By Timeline (Duration)</option>
                                </select>
                            </div>
                            <div class="col-4 my-3 d-flex align-items-center ">
                                <label class="mr-4 w-50 fw-bold">Select Duration</label>
                                <input type="text" id="custom_date_picker2" name="custom_date_picker"
                                    placeholder="Select Duration" class="form-control">

                            </div>
                           
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id="reportClients" style="display:none">
                                    <h3 id="clientReportTitle1"></h3>
                                    <table class="fl-table table table-hover table-responsive p-0 m-0"
                                        style="width:100%;" id="reportClientTable">
                                        <thead>

                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                        <div class="table-wrapper">
                            <h3 class="second-table-heading">Clients</h3>
                            <table class="fl-table table table-hover table-responsive p-0 m-0" style="width:100%"
                                id="clientTable1">
                                <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Client Name (ID)</th>
                                        <!-- <th> Subscriber Name (ID)</th> -->
                                        <th>Phone No</th>
                                        <th>Email</th>
                                        <th>Country</th>
                                        <th>City/Town</th>
                                        <!-- <th>Postcode</th> -->
                                        <th>NOA</th>
                                        <th>CreatedDate</th>

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class="tab-pane fade" id="application" role="tabpanel" aria-labelledby="application-tab">
                        <div style="display: flex; justify-content: center; align-items: center; text-align: center;" class="row">
                            <div class="col-4 my-3 d-flex align-items-center">
                                <label class="mr-4 w-50 fw-bold" for="">Filter By Attribute</label>
                                <select id="applicationFilter" class="form-select" name=""
                                    onchange="onChangeApplicationReport(this.value,this.options[this.selectedIndex].text)">
                                    <option value="" selected>Select Attribute</option>
                                    <option value="visaCountry">By Client’s Visa Country</option>
                                    <option value="applicationCountry">By Client’s Home Country</option>
                                    <option value="applicationType">By Application Type</option>
                                    <option value="noOfApplicaitonsPerApplication">By No. of Applications per
                                        Application (Single/Joint)</option>
                                    <option value="paymentMode">By Payment Mode</option>
                                    <option value="paymentAmount">By Outstanding Payments Amount</option>
                                    <option value="documentStored">By No. of Documents Stored</option>
                                    <option value="noOfApplicaitonsBy">By No. of Applications By Timeline
                                    </option>
                                    <option value="yearly">By Year</option>
                                </select>
                            </div>
                            <div class="col-4 my-3 d-flex align-items-center ">
                                <label class="mr-4 w-50 fw-bold">Select Duration</label>
                                <input type="text" id="custom_date_picker3" name="custom_date_picker"
                                    placeholder="Select Duration" class="form-control">

                            </div>
                           
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id="reportApplications" style="display:none">
                                    <h3 id="applicationReportTitle"></h3>
                                    <table class="fl-table table table-hover table-responsive p-0 m-0"
                                        style="width:100%;" id="reportApplicationTable">
                                        <thead>

                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="table-wrapper">
                            <h3 class="second-table-heading">Applications</h3>
                            <table style="width:100%" class="fl-table table table-hover table-responsive p-0 m-0"
                                id="applicationTable1">
                                <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <!-- <th>Sub_Name (Sub_ID)</th> -->
                                        <th> Client Name (ID)</th>
                                        <th>Application Name (ID)</th>
                                        <th>Visa Country</th>
                                        <th>Home Country</th>
                                        <th>Status</th>
                                        <th class="squeeze-column">Start Date</th>
                                        <th class="squeeze-column">End Date</th>
                                        <th class="squeeze-column">CreatedDate</th>
                                        {{-- <th class="p-1 text-start">Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="users" role="tabpanel" aria-labelledby="users-tab">
                        <div style="display: flex; justify-content: center; align-items: center; text-align: center;" class="row">
                            <div class="col-4 my-3 d-flex align-items-center">
                                <label class="mr-4 w-50 fw-bold" for="">Filter By Attribute</label>
                                <select id="userFilter" class="form-select" name=""
                                    onchange="onchangeUserReport(this.value,this.options[this.selectedIndex].text)">
                                    <option value="" selected>Select Attribute</option>
                                    <option value="byRole">By Role</option>
                                    <option value="ageGroup">By Age Group</option>
                                    {{-- <option value="Gender">By Gender</option> --}}
                                    <option value="applicationProcessed">By No. of Applications Assigned </option>
                                    <option value="meetingNotes">By Meeting Notes</option>
                                    <option value="communication">By Mode of Communication</option>
                                    <option value="message">By No. of Messages</option>
                                    <option value="users">Users By Timeline</option>
                                    <option value="yearly">By Year</option>
                                </select>
                            </div>
                            <div class="col-4 my-3 d-flex align-items-center ">
                                <label class="mr-4 w-50 fw-bold">Select Duration</label>
                                <input type="text" id="custom_date_picker4" name="custom_date_picker"
                                    placeholder="Select Duration" class="form-control">

                            </div>
                           
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id="reportUsers" style="display:none">
                                    <h3 id="clientReportTitle"></h3>
                                    <table class="fl-table table table-hover table-responsive p-0 m-0"
                                        style="width:100%;" id="reportUserTable">
                                        <thead>

                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="table-wrapper">
                            <h3 class="second-table-heading">Users</h3>
                            <table class="fl-table table table-hover p-0 m-0" id="userTable1" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">Sr No.</th>
                                        <!-- <th>Sub_Name (Sub_ID)</th> -->
                                        <th class="text-center">User Name (ID)</th>
                                        <th class="text-center">City</th>
                                        <th class="text-center">Phone No</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">Designation</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">CreatedDate</th>
                                    </tr>
                                </thead>
                                <tbody>


                                <tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="">
                        <div style="display: flex; justify-content: center; align-items: center; text-align: center;" class="row">
                            <div class="col-4 my-3 d-flex align-items-center">
                                <label class="mr-4 w-50 fw-bold" for="">Filter By Attribute</label>
                                <select id="documentFilter" class="form-select" name=""
                                    onchange="onchangeDocumentReport(this.value,this.options[this.selectedIndex].text)">
                                    <option value="" selected>Select Attribute</option>
                                    <option value="byApplication"> By Application (Top 20)</option>
                                    <option value="byClient">By Client (Top 20)</option>
                                    {{-- <option value="Gender">By Gender</option> --}}
                                    <!-- <option value="bySubscriber">By Subscriber (Top 20) </option> -->
                                    <option value="bySize">By FileSize (Top 50)</option>
                                    <option value="byFiletype">By Filetype</option>
                                    <option value="byTimeLine">By Timeline (Duration)</option>
                                    <option value="yearly">By Year</option>
                                </select>
                            </div>
                            <div class="col-4 my-3 d-flex align-items-center ">
                                <label class="mr-4 w-50 fw-bold">Select Duration</label>
                                <input type="text" id="custom_date_picker14" name="custom_date_picker"
                                    placeholder="Select Duration" class="form-control">

                            </div>
                           
                        </div>
                        <div class="table-wrapper">
                            <div id="reportDocuments" style="display:none">
                                <h3 id="documentsReportTitle"></h3>
                                <table class="fl-table table table-hover p-0 m-0" id="documentsTable"
                                    style="width: 100%">
                                    <thead>

                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="table-wrapper">
                            <h3 class="second-table-heading">Documents</h3>
                            <table class="fl-table table table-hover p-0 m-0" id="documentsTable1"
                                style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Client Name (ID)</th>
                                        <th>Application Name (ID)</th>
                                        <th>Type</th>
                                        <th>Name</th>
                                        <th>File</th>
                                        <th>File Size</th>
                                        <th>CreatedDate</th>
                                        {{-- <th>Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="communication" role="tabpanel"
                        aria-labelledby="communication-tab">
                        <div style="display: flex; justify-content: center; align-items: center; text-align: center;" class="row">
                            <div class="col-4 my-3 d-flex align-items-center">
                                <label class="mr-4 w-50 fw-bold" for="">Filter By Attribute</label>
                                <select id="communicationFilter" class="form-select" name=""
                                    onchange="onchangeCommunicationReport(this.value,this.options[this.selectedIndex].text)">
                                    <option value="" selected>Select Attribute</option>
                                    
                                    <option value="byMeeting"> By Meeting Notes By Timeline</option>
                                    <option value="meetingNotes">By Meeting Notes Type</option>
                                    <option value="messagesSentByUser">By Messages Sent ByUsers</option>
                                    <option value="byMessages">By Messages</option>
                                    <option value="yearly">By Year</option>
                                </select>
                            </div>
                            <div class="col-4 my-3 d-flex align-items-center ">
                                <label class="mr-4 w-50 fw-bold">Select Duration</label>
                                <input type="text" id="custom_date_picker5" name="custom_date_picker"
                                    placeholder="Select Duration" class="form-control">

                            </div>
                           
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id="reportCommunications" style="display:none">
                                    <h3 id="clientReportTitle12"></h3>
                                    <table
                                        class="fl-table table table-hover table-responsive table-striped table-bordered p-0 m-0"
                                        style="width:100%;" id="reportCommunicationsTable">
                                        <thead>

                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>








                        <div class="table-wrapper">
                            <h3 class="second-table-heading">Communications</h3>
                            <table class="fl-table table table-hover p-0 m-0" id="communicationTable1"
                                style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Comm. ID</th>
                                        <th>Sent By</th>
                                        <th>Sent To</th>
                                        <th>Message</th>
                                        <!-- <th>Date</th> -->
                                        <th class="squeeze-column">CreatedDate</th>
                                        {{-- <th>Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="invocies" role="tabpanel" aria-labelledby="invocies-tab">
                        <div style="display: flex; justify-content: center; align-items: center; text-align: center;" class="row">
                            <div class="col-4 my-3 d-flex align-items-center">
                                <label class="mr-4 w-50 fw-bold" for="">Filter By Attribute</label>
                                <select id="invoiceFilter" class="form-select" name=""
                                    onchange="onchangeInvoicesReport(this.value,this.options[this.selectedIndex].text)">
                                    <option value="" selected>Select Attribute</option>
                                    <option value="byAmount">By Amount</option>
                                    <option value="byType">By Invoice Type</option>
                                    {{-- <option value="Gender">By Gender</option> --}}
                                    <option value="byClient">By Client's Home Country </option>
                                    <option value="byVisaCountry">By Visa Country Count</option>
                                    <option value="byTimeLine">By Timeline (Duration)</option>
                                    <option value="yearly">By Year</option>
                                </select>
                            </div>
                            <div class="col-4 my-3 d-flex align-items-center ">
                                <label class="mr-4 w-50 fw-bold">Select Duration</label>
                                <input type="text" id="custom_date_picker6" name="custom_date_picker"
                                    placeholder="Select Duration" class="form-control">

                            </div>
                           
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id="reportInvoices" style="display:none">
                                    <h3 id="clientReportTitle2"></h3>
                                    <table class="fl-table table table-hover table-responsive p-0 m-0"
                                        style="width:100%;" id="reportInvoiceTable">
                                        <thead>

                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="table-wrapper">
                            <h3 class="second-table-heading">Invoices (AR)</h3>
                            <table class="fl-table table table-hover p-0 m-0" id="invoicesTable1"
                                style="width: 100%">
                                <thead>

                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Client Name (ID)</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Amount</th>
                                        <th>Discount</th>
                                        <th>Tax</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Due Date</th>
                                        <th class="squeeze-column">CreatedDate</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="invocies_ap" role="tabpanel" aria-labelledby="invocies-ap-tab">
                        <div style="display: flex; justify-content: center; align-items: center; text-align: center;" class="row">
                            <div class="col-4 my-3 d-flex align-items-center">
                                <label class="mr-4 w-50 fw-bold" for="">Filter By Attribute</label>
                                <select id="invoiceAPFilter" class="form-select" name=""
                                    onchange="onchangeInvoicesAPReport(this.value,this.options[this.selectedIndex].text)">
                                    <option value="" selected>Select Attribute</option>
                                    <option value="byAmount">By Amount</option>
                                    <option value="byType">By Invoice Type</option>
                                    {{-- <option value="Gender">By Gender</option> --}}
                                    <option value="byClient">By Client's Home Country </option>
                                    <option value="byVisaCountry">By Visa Country Count</option>
                                    <option value="byTimeLine">By Timeline (Duration)</option>
                                    <option value="yearly">By Year</option>
                                </select>
                            </div>
                            <div class="col-4 my-3 d-flex align-items-center ">
                                <label class="mr-4 w-50 fw-bold">Select Duration</label>
                                <input type="text" id="custom_date_picker66" name="custom_date_picker"
                                    placeholder="Select Duration" class="form-control">

                            </div>
                           
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id="reportInvoicesAP" style="display:none">
                                    <h3 id="clientReportTitle2"></h3>
                                    <table class="fl-table table table-hover table-responsive p-0 m-0"
                                        style="width:100%;" id="reportInvoiceAPTable">
                                        <thead>

                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="table-wrapper">
                            <h3 class="second-table-heading">Invoices (AP)</h3>
                            <table class="fl-table table table-hover p-0 m-0" id="invoicesAPTable1"
                                style="width: 100%">
                                <thead>

                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Client Name (ID)</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Amount</th>
                                        <th>Discount</th>
                                        <th>Tax</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Due Date</th>
                                        <th class="squeeze-column">CreatedDate</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="payments" role="tabpanel" aria-labelledby="payments-tab">
                        <div style="display: flex; justify-content: center; align-items: center; text-align: center;" class="row">

                            <div class="col-4 my-3 d-flex align-items-center">
                                <label class="mr-4 w-50 fw-bold" for="">Filter By Attribute</label>
                                <select id="paymentFilter" class="form-select" name=""
                                    onchange="onchangePaymentsReport(this.value,this.options[this.selectedIndex].text)">
                                    <option value="" selected>Select Attribute</option>
                                    <option value="byPaymentMode">By Payment Mode</option>
                                    <option value="byPaymentAmount">By Payment Amount</option>
                                    <option value="byOutstandingAmount">By Outstanding Amount</option>
                                    <option value="byInvoiceType">By Payment Type </option>
                                    <option value="byVisaCountry">By Visa Country</option>
                                    <option value="byClientCountry">By Client's Home Country</option>
                                    <!-- <option value="byClientCountry">By Client's Home Country</option>
                                    <option value="byVisaCountry">By Visa Country</option> -->
                                    <!-- <option value="byApplicationType">By Application Type</option> -->
                                    <option value="byTimeLine">By Timeline (Duration)</option>
                                    <option value="byYear">By Year</option>
                                </select>
                            </div>
                            <div class="col-4 my-3 d-flex align-items-center ">
                                <label class="mr-4 w-50 fw-bold">Select Duration</label>
                                <input type="text" id="custom_date_picker7" name="custom_date_picker"
                                    placeholder="Select Duration" class="form-control">

                            </div>
                           
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id="reportPayments" style="display:none">
                                    <h3 id="clientReportTitle5"></h3>
                                    <table class="fl-table table table-hover table-responsive p-0 m-0"
                                        style="width:100%;" id="reportPaymentTable">
                                        <thead>

                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>









                        <div class="table-wrapper">
                            <h3 class="second-table-heading">Payments (AR)</h3>
                            <table class="fl-table table table-hover p-0 m-0" id="paymentsTable1"
                                style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Client Name (ID)</th>
                                        <th class="col-4">Service Offered</th>
                                        <!-- <th>Payment Type</th>
                                        <th>Service Provider</th>
                                        <th> Service Taken</th> -->
                                        <th>Payment Mode</th>
                                        <th>Amount To Pay</th>
                                        <th>Paid Amount</th>
                                        <th>Outstanding</th>
                                        <th>Payment Date</th>
                                        <!-- <th>InvoiceID</th> -->
                                        <th class="squeeze-column">EntryDate</th>
                                    </tr>

                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="paymentsap" role="tabpanel" aria-labelledby="paymentsap-tab">
                        <div style="display: flex; justify-content: center; align-items: center; text-align: center;" class="row">

                            <div class="col-4 my-3 d-flex align-items-center">
                                <label class="mr-4 w-50 fw-bold" for="">Filter By Attribute</label>
                                <select id="paymentAPFilter" class="form-select" name=""
                                    onchange="onchangePaymentsAPReport(this.value,this.options[this.selectedIndex].text)">
                                    <option value="" selected>Select Attribute</option>
                                    <option value="byPaymentMode">By Payment Mode</option>
                                    <option value="byPaymentAmount">By Payment Amount</option>
                                    <option value="byOutstandingAmount">By Outstanding Amount</option>
                                    <option value="byInvoiceType">By Payment Type </option>
                                    <option value="byVisaCountry">By Visa Country</option> 
                                    <option value="byClientCountry">By Client's Home Country</option>
                                    <!-- <option value="byClientCountry">By Client's Home Country</option>
                                    <option value="byVisaCountry">By Visa Country</option> -->
                                    <!-- <option value="byApplicationType">By Application Type</option> -->
                                    
                                    <option value="byYear">By Year</option>
                                    <option value="byTimeLine">By Timeline (Duration)</option>
                                </select>
                            </div>
                            <div class="col-4 my-3 d-flex align-items-center ">
                                <label class="mr-4 w-50 fw-bold">Select Duration</label>
                                <input type="text" id="custom_date_picker71" name="custom_date_picker"
                                    placeholder="Select Duration" class="form-control">

                            </div>
                           
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id="reportAPPayments" style="display:none">
                                    <h3 id="clientReportAPTitle5"></h3>
                                    <table class="fl-table table table-hover table-responsive p-0 m-0"
                                        style="width:100%;" id="reportPaymentAPTable">
                                        <thead>

                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="table-wrapper">
                            <h3 class="second-table-heading">Payments (AP)</h3>
                            <table class="fl-table table table-hover p-0 m-0" id="paymentsAPTable1"
                                style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <!-- <th>Client Name (ID)</th> -->
                                        <!-- <th>Service Offered</th>
                                        <th>Payment Type</th> -->
                                        <th class="col-4">Service Provider</th>
                                        <th class="col-4"> Service Taken</th>
                                        <th>Payment Mode</th>
                                        <th>Amount To Pay</th>
                                        <th>Paid Amount</th>
                                        <th>Outstanding</th>
                                        <th>Payment Date</th>
                                        <!-- <th>InvoiceID</th> -->
                                        <th class="squeeze-column">EntryDate</th>
                                    </tr>

                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>



                    <div class="tab-pane fade" id="refferals" role="tabpanel" aria-labelledby="refferals-tab">
                        <div style="display: flex; justify-content: center; align-items: center; text-align: center;" class="row">
                            <div class="col-4 my-3 d-flex align-items-center">
                                <label class="mr-4 w-50 fw-bold" for="">Filter By Attribute</label>
                                <select id="referralFilter" class="form-select" name=""
                                    onchange="onChangeReferralsReport(this.value,this.options[this.selectedIndex].text)">
                                    <option value="" selected>Select Attribute</option>
                                    <!-- <option value="subscribers">By No. of Subscribers Categories</option> -->
                                    <option value="subscriberType">By Subscriber's Category</option>
                                    <option value="subscribers">By Subscriber's Sub-Category</option>
                                    <option value="subscribedPlan">By Subscribed Plan </option>
                                    <option value="byTimeline">By Timeline (Duration)</option>
                                    <option value="yearly">By Year</option>
                                </select>
                            </div>
                            <div class="col-4 my-3 d-flex align-items-center ">
                                <label class="mr-4 w-50 fw-bold">Select Duration</label>
                                <input type="text" id="custom_date_picker8" name="custom_date_picker"
                                    placeholder="Select Duration" class="form-control">

                            </div>
                           
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div id="reportReferrals" style="display:none">
                                    <h3 id="referralReportTitle"></h3>
                                    <table class="fl-table table table-hover table-responsive p-0 m-0"
                                        style="width:100%;" id="reportReferralTable">
                                        <thead>

                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="table-wrapper">
                            <h3 class="second-table-heading">Referrals</h3>
                            <table class="fl-table table table-hover p-0 m-0" id="refferalsTable1"
                                style="width: 100%">
                                <thead>
                                    <tr>
                                        <th class="p-1 text-center">Sr.No.</th>
                                        <th class="p-1 text-center">Referred By(Sub_ID)</th>
                                        <th class="p-1 text-center">Referred To(Sub_ID)</th>
                                        <th class="p-1 text-center">Referral Code</th>
                                        <th class="p-1 text-center">Sub_Plan</th>
                                        <th class="p-1 text-center"> Amount_Paid (USD)</th>
                                        <th class="p-1 text-center">Commission (USD)</th>
                                        <th class="p-1 text-center"> DOS </th>

                                        <!-- <th class="p-1 text-start">Date</th> -->
                                        <th class="squeeze-column">ReferredDate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <div class="tab-pane fade" id="wallet" role="tabpanel" aria-labelledby="wallet-tab">
                        <div style="display: flex; justify-content: center; align-items: center; text-align: center;" class="row">
                            <div class="col-4 my-3 d-flex align-items-center">
                                <label class="mr-4 w-50 fw-bold" for="">Filter By Attribute</label>
                                <select id="walletFilter" class="form-select" name=""
                                    onchange="onchangeWalletReport(this.value,this.options[this.selectedIndex].text)">
                                    <option value="" selected>Select Attribute</option>
                                    <!-- <option value="byWallets">By Wallet Amount </option> -->
                                    <option value="byTransactions"> By Transaction Type</option>
                                    <option value="byDates"> By Timeline (Duration)</option>
                                    <option value="byWalletYear"> By Year</option>
                                </select>
                            </div>
                            <div class="col-4 my-3 d-flex align-items-center ">
                                <label class="mr-4 w-50 fw-bold">Select Duration</label>
                                <input type="text" id="custom_date_picker9" name="custom_date_picker"
                                    placeholder="Select Duration" class="form-control">

                            </div>
                           
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id="reportWallet" style="display:none">
                                    <h3 id="clientReportTitle61"></h3>
                                    <table class="fl-table table table-hover table-responsive p-0 m-0"
                                        style="width:100%;" id="reportWalletTable">
                                        <thead>

                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="table-wrapper">
                            <h3 class="second-table-heading">Wallet</h3>
                            <table class="fl-table table table-hover p-0 m-0" id="walletsTable1" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th class="p-1 text-center">Sr.No.</th>

                                        <th class="p-1  text-center"> Wallet_TransID</th>
                                        <th class="p-1  text-center col-4"> Subscriber(SUB_ID)</th>
                                        <th class="p-1 narrow-column col-1">Transaction Amount (Cr/Dr) </th>
                                        <th class="p-1  text-center col-4"> Description</th>
                                        {{-- <th class="p-1 text-center text-center">Transaction Amount (USD) </th> --}}
                                        <th class="p-1  text-center"> Previous Balance (USD) </th>
                                        <th class="p-1  text-center"> New Balance (USD)</th>
                                        <th class="text-center col-1">TransactionDate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        {{-- <h4>Wallet Transactions</h4>
                            <div class="table-wrapper">
                                <table class="fl-table table table-hover p-0 m-0" id="transactionsTable1"
                                    style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th class="p-1 text-center">Sr.No.</th>
                                            <th class="p-1 text-start">Subscriber Name</th>
                                            <th class="p-1 text-start">Transaction Type (Credit/Debit)</th>
                                            <th class="p-1 text-start">Transaction Amount (USD)</th>
                                            <th class="p-1 text-start">Transaction Date</th>
                                            <th class="p-1 text-start">Pevious Balance</th>
                                            <th class="p-1 text-start">New Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div> --}}
                    </div>
                    <!-- @if ($user->user_type == 'admin') -->
                    <div class="tab-pane fade" id="SupportTickets" role="tabpanel"
                        aria-labelledby="SupportTickets-tab">



                        <div class="row">
                            <div class="col-6 my-3 d-flex align-items-center">
                                <label style="width: 180px" for="">Filter By Attribute</label>
                                <select id="" class="form-select" name=""
                                    onchange="onchangeSupportReport(this.value,this.options[this.selectedIndex].text)">
                                    <option selected>Select Attribute</option>
                                    <option value="byTicketType">By Ticket Type</option>
                                    <option value="byTime">By Time</option>
                                    <option value="byTimeTaken">By Time Taken</option>
                                    <option value="bySupportStaff">By Support Staff</option>
                                </select>
                            </div>
                            <div class="col-6 my-3 d-flex align-items-center ">
                                <label style="width: 180px">Select Duration</label>
                                <input type="text" id="custom_date_picker10" name="custom_date_picker"
                                    placeholder="Select Duration" class="form-control">

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id="reportSupport" style="display:none">
                                    <h3 id="clientReportTitle7"></h3>
                                    <table class="fl-table table table-hover table-responsive p-0 m-0"
                                        style="width:100%;" id="reportSupportTable">
                                        <thead>

                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>



                        <div class="table-wrapper">
                            <table class="fl-table table table-hover p-0 m-0" id="SupportTicketsTable1"
                                style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Sr.No.</th>
                                        <th>Ticket ID</th>
                                        <th>Subscriber ID</th>
                                        <th>Client ID</th>
                                        <th>Issue</th>
                                        <th>Department</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        {{-- <th>Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="ActivityLog" role="tabpanel"
                        aria-labelledby="ActivityLog-tab">
                        <div class="row">
                            <div class="col-6 my-3 d-flex align-items-center">
                                <label style="width: 180px" for="">Filter By Attribute</label>
                                <select id="activityFilter" class="form-select" name=""
                                    onchange="onchangeActivityReport(this.value,this.options[this.selectedIndex].text)">
                                    <option value="" selected>Select Attribute</option>
                                    <option value="byActivityType">By Activity Type</option>
                                    <option value="byTime">By Total Number No. of Activities By Time</option>
                                    {{-- <option value="Gender">By Gender</option> --}}
                                    <option value="bySubscribers">By Top 10 Subscribers </option>
                                </select>
                            </div>
                            <div class="col-6 my-3 d-flex align-items-center ">
                                <label style="width: 180px">Select Duration</label>
                                <input type="text" id="custom_date_picker13" name="custom_date_picker"
                                    placeholder="Select Duration" class="form-control">

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id="reportActivity" style="display:none">
                                    <h3 id="clientReportTitle8"></h3>
                                    <table class="fl-table table table-hover table-responsive p-0 m-0"
                                        style="width:100%;" id="reportActivityTable">
                                        <thead>

                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="table-wrapper">
                            <table class="fl-table table table-hover p-0 m-0" id="ActivityLogTable1"
                                style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Activity</th>
                                        <th>User</th>
                                        <th>Detail</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <div class="tab-pane fade" id="Affiliates" role="tabpanel" aria-labelledby="Affiliates-tab">
                        <div class="row">
                            <div class="col-6 my-3 d-flex align-items-center">
                                <label style="width: 180px" for="">Filter By Attribute</label>
                                <select id="affiliateFilter" class="form-select bold-select" name=""
                                    onchange="onChangeAffiliatesReport(this.value,this.options[this.selectedIndex].text)">
                                    <option selected>Select Attribute</option>
                                    <option value="subscribersReferred">By No. of Subscribers Referred</option>
                                    <option value="earnedComission">By Amount of Commissions Earnt</option>
                                    <option value="country">By Country </option>
                                    <option value="subscriberType">By Subscriber Type </option>
                                    <option value="subscribedPlan">By Subscribed Plan </option>
                                    <option value="currentWalletCredits">By Current Wallet Credits</option>
                                </select>
                            </div>
                            <div class="col-6 my-3 d-flex align-items-center ">
                                <label style="width: 180px">Select Duration</label>
                                <input type="text" id="custom_date_picker12 bold-selec" name="custom_date_picker"
                                    placeholder="Select Duration" class="form-control">

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div id="reportAffiliates" style="display:none">
                                    <h3 id="affiliateReportTitle"></h3>
                                    <table class="fl-table table table-hover table-responsive p-0 m-0"
                                        style="width:100%;" id="reportAffiliateTable">
                                        <thead>

                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="table-wrapper">
                            <table class="fl-table table table-hover p-0 m-0" id="AffiliatesTable1"
                                style="width: 100%">
                                <thead>
                                    <tr>
                                        <th class="p-1 text-center">Sr.No.</th>
                                        <th class="p-1 text-start">Affiliate Name(id)</th>
                                        <th class="p-1 text-start">Referral Code</th>
                                        <th class="p-1 text-start">Type</th>
                                        <th class="p-1 text-start">Country</th>
                                        <th class="p-1 text-start">City/Town</th>
                                        <th class="p-1 text-start">CE</th>
                                        <th class="p-1 text-start">Activation Date</th>
                                        <th class="p-1 text-start">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- @endif -->
                </div>
            </div>
        </div>


    </div>
</div>
</div>

</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


<script>
    //  document.querySelectorAll(".bold-select").forEach(select => {
    //         select.addEventListener("change", function() {
    //             this.style.fontWeight = "bold"; // Make only the selected dropdown bold
    //         });
    //     });
    //     document.querySelectorAll(".bold-select").forEach(input => {
    //     input.addEventListener("change", function() {

    //         if (this.value.trim() !== "") {
    //             this.style.fontWeight = "bold"; // Make text bold when a date is selected
    //         } else {
    //             this.style.fontWeight = "normal"; // Reset if input is cleared
    //         }
    //     });
    // });
    document.querySelectorAll(".bold-select").forEach(input => {
        input.addEventListener("input", function() {
            if (this.value.trim() !== "") {
                this.style.fontWeight = "bold"; // Make text bold when a date is selected
            } else {
                this.style.fontWeight = "normal"; // Reset if input is cleared
            }
        });

        input.addEventListener("change", function() {
            if (this.value.trim() !== "") {
                this.style.fontWeight = "bold";
            } else {
                this.style.fontWeight = "normal";
            }
        });
    });
    $(document).ready(function() {
        // Use event delegation for dynamically loaded content
        $(document).on('mouseenter', '#communicationTable1 .message-tooltip', function() {
            var fullText = $(this).data('full-text');
            $(this).append('<div class="tooltip-content">' + fullText + '</div>');
        }).on('mouseleave', '#communicationTable1 .message-tooltip', function() {
            $(this).find('.tooltip-content').remove();
        });

        $(document).on('mouseenter', '#communicationTable1 .receiver-tooltip', function() {
            var fullText = $(this).data('full-text');
            $(this).append('<div class="tooltip-content">' + fullText + '</div>');
        }).on('mouseleave', '#communicationTable1 .receiver-tooltip', function() {
            $(this).find('.tooltip-content').remove();
        });
        $(document).on('mouseenter', '#SupportTicketsTable1 .message-tooltip', function() {
            var fullText = $(this).data('full-text');
            $(this).append('<div class="tooltip-content">' + fullText + '</div>');
        }).on('mouseleave', '#SupportTicketsTable1 .message-tooltip', function() {
            $(this).find('.tooltip-content').remove();
        });
        $(document).on('mouseenter', '#demoRequestTable1 .message-tooltip', function() {
            var fullText = $(this).data('full-text');
            $(this).append('<div class="tooltip-content">' + fullText + '</div>');
        }).on('mouseleave', '#demoRequestTable1 .message-tooltip', function() {
            $(this).find('.tooltip-content').remove();
        });
        $(document).on('mouseenter', '#paymentsAPTable1 .message-tooltip', function() {
            var fullText = $(this).data('full-text');
            $(this).append('<div class="tooltip-content">' + fullText + '</div>');
        }).on('mouseleave', '#paymentsAPTable1 .message-tooltip', function() {
            $(this).find('.tooltip-content').remove();
        });
    });

    var dataTable1 = false;
    var clientTable1 = false;
    var Documents1 = false;
    var applicationTable1 = false;
    var userTable1 = false;
    var communicationTable1 = false;
    var invoiceTable1 = false;
    var invoiceAPTable1 = false;
    var paymentsTable1 = false;
    var paymentsAPTable1 = false;
    var refferalsTable1 = false;
    var walletsTable1 = false;
    var transactionsTable1 = false;
    var Subscribers1 = false;
    var SupportTickets1 = false;
    var ActivityLog1 = false;
    var Affiliates1 = false;
    var demoRequest1 = false;


    var startdate = null;
    var enddate = null;




    function deativeTabs() {
        dataTable1 = false;
        Documents1 = false;
        applicationTable1 = false;
        userTable1 = false;
        communicationTable1 = false;
        invoiceTable1 = false;
        invoiceAPTable1 = false;
        paymentsTable1 = false;
        paymentsAPTable1 = false;
        refferalsTable1 = false;
        walletsTable1 = false;
        transactionsTable1 = false;
        Subscribers1 = false;
        SupportTickets1 = false;
        ActivityLog1 = false;
        Affiliates1 = false;
        demoRequest1 = false;
        clientTable1 = false;
    }












    $(document).ready(function() {




        var result = getStartAndEndDate('Client');

        // This arrangement can be altered based on how we want the date's format to appear.
        let currentDate = `${result.startDate} - ${result.endDate}`;

        deativeTabs();
        dataTable1 = true;
        if ($.fn.DataTable.isDataTable('#clientTable1')) {
            $('#clientTable1').DataTable().clear().destroy();
            $('#clientTable1').empty(); // Remove existing columns and rows
        }
        var dataTable = $('#clientTable1').DataTable({
            processing: true,
            serverSide: true,

            "lengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "pageLength": 10,
            destroy: true,
            dom: '<"d-flex justify-content-between align-items-center"lBf>rtip', // Custom layout
            "buttons": [{
                    extend: 'csv',
                    title: 'Report : Clients  (' + currentDate + ')', // Custom title for CSV
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'excel',
                    title: 'Report : Clients  (' + currentDate + ')', // Custom t
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'pdf',
                    title: 'Report : Clients (' + currentDate + ')', // Custom title
                    orientation: 'landscape', // Makes table fit better
                    pageSize: 'A4',
                    customize: function(doc) {
                        let table = doc.content[1].table;
                        let columnCount = table.body[0].length;

                        // Dynamically set column widths to distribute space evenly
                        // This adjusts each column to take up equal space
                        table.widths = [
                            '3%', // Column 1 (3% width)
                            '*', // Column 2 (remaining space, evenly distributed)
                            '8%', // Column 3 (2% width)
                            '*', // Column 4 (remaining space, evenly distributed)
                            '8%', // Column 5 (2% width)
                            '10%', // Column 6 (remaining space, evenly distributed)
                            '3%', // Column 7 (2% width)
                            '6%' // Column 8 (3% width)
                        ];
                        // Adjust styles
                        doc.defaultStyle.fontSize = 8; // Font size for table data
                        doc.styles.tableHeader.fontSize = 8; // Larger header font
                        doc.styles.title.fontSize = 18; // Title font
                        doc.pageMargins = [5, 5, 5, 5]; // Reduce margins
                        doc.styles.title.bold = true;
                        // Force full-page width usage
                        doc.content[1].layout = {
                            hLineWidth: function(i, node) {
                                return 0.5; // Line thickness
                            },
                            vLineWidth: function(i, node) {
                                return 0.5;
                            },
                            paddingLeft: function(i, node) {
                                return 4; // Adjust left padding
                            },
                            paddingRight: function(i, node) {
                                return 4;
                            }
                        };

                        // Center-align all table content
                        doc.content[1].table.body.forEach(function(row, rowIndex) {
                            row.forEach(function(cell, cellIndex) {
                                if (typeof cell === 'object') {
                                    cell.alignment = 'center'; // Center-align text in each cell
                                }
                            });
                        });

                        // Center-align the title
                        doc.content[0].alignment = 'center';
                    },
                    exportOptions: {
                        columns: ':visible', // Ensure all visible columns are exported
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                }
            ],
            ajax: {
                url: "{{ route('manage_clients_report') }}",
                data: function(d) {
                    // Add additional data here
                    d.startDate = result.startDate;
                    d.endDate = result.endDate;

                },
                dataSrc: function(json) {
                    if (json.data.length === 0) {
                        // Swal.fire({
                        //     icon: 'warning',
                        //     title: 'No Data Available',
                        //     text: 'No Data found for Report : Clients (' + currentDate + ')',
                        //     confirmButtonText: 'OK'
                        // });
                    }
                    return json.data;
                },

            },
            language: {
                emptyTable: 'No Data found for Report : Clients (' + currentDate + ')',
            },
            initComplete: function(settings, json) {
                var rowCount = dataTable.data().count(); // Get total number of rows
                var buttons = dataTable.buttons();

                if (rowCount === 0) {
                    buttons.disable(); // Disable all buttons if no records
                } else {
                    buttons.enable(); // Enable if records exist
                }
            },
            columns: [{
                    data: 'DT_RowIndex', // Use DT_RowIndex to display the index column
                    name: 'DT_RowIndex', // Also set the name to 'DT_RowIndex' for proper handling
                    searchable: false, // You can disable searching on this column if needed
                    orderable: false,
                    // You can also disable sorting if you don't want the index to be sortable
                },
                {
                    data: 'name',
                    name: 'name'
                },
                // {
                //     data: 'subscriber',
                //     name: 'subscriber'
                // },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'country',
                    name: 'country',

                },
                {
                    data: 'city',
                    name: 'city'
                },

                {
                    data: 'noa',
                    name: 'noa',

                },
                {
                    data: 'created_at',
                    name: 'created_at',

                }
                //{ data: 'action', name: 'action' },
            ],

        });





        // $('#custom_date_picker').daterangepicker();


    });

    function getStartAndEndDate(type) {

        if (type == 'Subscriber') {
            var selectedDate = $('#custom_date_picker').val();
        } else if (type == 'Client') {
            var selectedDate = $('#custom_date_picker2').val();
        } else if (type == 'Application') {
            var selectedDate = $('#custom_date_picker3').val();
        } else if (type == 'User') {
            var selectedDate = $('#custom_date_picker4').val();
        } else if (type == 'Document') {
            var selectedDate = $('#custom_date_picker14').val();
        } else if (type == 'Communication') {
            var selectedDate = $('#custom_date_picker5').val();
        } else if (type == 'Invoice') {
            var selectedDate = $('#custom_date_picker6').val();
        } else if (type == 'InvoiceAP') {
            var selectedDate = $('#custom_date_picker66').val();
        } else if (type == 'PaymentAR') {
            var selectedDate = $('#custom_date_picker7').val();
        } else if (type == 'PaymentAP') {
            var selectedDate = $('#custom_date_picker71').val();
        } else if (type == 'Referral') {
            var selectedDate = $('#custom_date_picker8').val();
        } else if (type == 'Wallet') {
            var selectedDate = $('#custom_date_picker9').val();
        } else if (type == 'Affiliat') {
            var selectedDate = $('#custom_date_picker12').val();
        } else if (type == 'Support') {
            var selectedDate = $('#custom_date_picker10').val();
        } else if (type == 'Demo') {
            var selectedDate = $('#custom_date_picker11').val();
        } else if (type == 'Activity') {
            var selectedDate = $('#custom_date_picker13').val();
        }


        // Get the selected date value from the date picker


        // Ensure the date value is not empty or invalid
        if (!selectedDate || !selectedDate.includes(" - ")) {
            console.error("Invalid date format. Ensure the date is in 'DD-MM-YYYY - DD-MM-YYYY' format.");
            return null;
        }

        // Split the selected date into start and end date
        var dateParts = selectedDate.split(" - ");
        var startDate = dateParts[0].trim(); // Extract and trim the start date
        var endDate = dateParts[1].trim(); // Extract and trim the end date

        // Return the result as an object
        return {
            startDate,
            endDate
        };
    }

    function deleteclient(id) {
        var localtime = new Date();
        var conf = confirm('Delete Client');
        if (conf == true) {
            window.location.href = "delete_client/" + id + "/" + localtime.toString() + "";
        }
    }

    function colorizer() {
        const characters = 'ABCDEF0123456789';
        let result = '#';
        const charactersLength = characters.length;
        for (let i = 0; i < 6; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    }
</script>
<script>
    function deleteuser(id) {
        var conf = confirm('Delete User');
        if (conf == true) {
            window.location.href = "delete_user/" + id + "";
        }
    }



    function onClickClients() {


        var result = getStartAndEndDate('Client');

        // This arrangement can be altered based on how we want the date's format to appear.
        let currentDate = `${result.startDate} - ${result.endDate}`;

        deativeTabs();

        dataTable1 = true;
        var dataTable = $('#clientTable1').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            "lengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "pageLength": 10,
            dom: '<"d-flex justify-content-between align-items-center w-bold"lBf>rtip', // Custom layout
            "buttons": [{
                    extend: 'csv',
                    title: 'Report : Clients  (' + currentDate + ')', // Custom title for CSV
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'excel',
                    title: 'Report : Clients (' + currentDate + ')', // Custom t
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'pdf',
                    title: 'Report : Clients (' + currentDate + ')', // Custom title
                    orientation: 'landscape', // Makes table fit better
                    pageSize: 'A4',
                    customize: function(doc) {
                        let table = doc.content[1].table;
                        let columnCount = table.body[0].length;

                        // Dynamically set column widths to distribute space evenly
                        // This adjusts each column to take up equal space
                        table.widths = [
                            '3%', // Column 1 (3% width)
                            '*', // Column 2 (remaining space, evenly distributed)
                            '8%', // Column 3 (2% width)
                            '*', // Column 4 (remaining space, evenly distributed)
                            '8%', // Column 5 (2% width)
                            '10%', // Column 6 (remaining space, evenly distributed)
                            '3%', // Column 7 (2% width)
                            '6%' // Column 8 (3% width)
                        ];
                        // Adjust styles
                        doc.defaultStyle.fontSize = 8; // Font size for table data
                        doc.styles.tableHeader.fontSize = 8; // Larger header font
                        doc.styles.title.fontSize = 18; // Title font
                        doc.pageMargins = [5, 5, 5, 5]; // Reduce margins
                        doc.styles.title.bold = true;

                        // Force full-page width usage
                        doc.content[1].layout = {
                            hLineWidth: function(i, node) {
                                return 0.5; // Line thickness
                            },
                            vLineWidth: function(i, node) {
                                return 0.5;
                            },
                            paddingLeft: function(i, node) {
                                return 4; // Adjust left padding
                            },
                            paddingRight: function(i, node) {
                                return 4;
                            }
                        };

                        // Center-align all table content
                        doc.content[1].table.body.forEach(function(row, rowIndex) {
                            row.forEach(function(cell, cellIndex) {
                                if (typeof cell === 'object') {
                                    cell.alignment = 'center'; // Center-align text in each cell
                                }
                            });
                        });

                        // Center-align the title
                        doc.content[0].alignment = 'center';
                    },
                    exportOptions: {
                        columns: ':visible', // Ensure all visible columns are exported
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                }
            ],
            ajax: {
                url: "{{ route('manage_clients_report') }}",
                data: function(d) {
                    // Add additional data here
                    d.startDate = result.startDate;
                    d.endDate = result.endDate;

                },
                dataSrc: function(json) {
                    if (json.data.length === 0) {
                        // Swal.fire({
                        //     icon: 'warning',
                        //     title: 'No Data Available',
                        //     text: 'No Data found for Report : Clients (' + currentDate + ')',
                        //     confirmButtonText: 'OK'
                        // });
                    }
                    return json.data;
                }
            },
            language: {
                emptyTable: 'No Data found for Report : Clients (' + currentDate + ')',
            },
            initComplete: function(settings, json) {
                var rowCount = dataTable.data().count(); // Get total number of rows
                var buttons = dataTable.buttons();

                if (rowCount === 0) {
                    buttons.disable(); // Disable all buttons if no records
                } else {
                    buttons.enable(); // Enable if records exist
                }
            },
            columns: [{
                    data: 'DT_RowIndex', // Use DT_RowIndex to display the index column
                    name: 'DT_RowIndex', // Also set the name to 'DT_RowIndex' for proper handling
                    searchable: false, // You can disable searching on this column if needed
                    orderable: false, // You can also disable sorting if you don't want the index to be sortable
                },
                {
                    data: 'name',
                    name: 'name'
                },
                // {
                //     data: 'subscriber',
                //     name: 'subscriber'
                // },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'country',
                    name: 'country'
                },
                {
                    data: 'city',
                    name: 'city'
                },
                // {
                //     data: 'pincode',
                //     name: 'pincode'
                // },
                {
                    data: 'noa',
                    name: 'noa'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
            ],

        });

    }

    function onchangeClientReport(type, text) {

        // const selectedText = type.options[type.selectedIndex].text;
        // alert(selectedText);
        let selectedText = text;

        if (type === '') {
            // If empty, hide the table and the report title
            $('#clientReportTitle1').hide(''); // Clear the report title
            $('#reportClientTable').hide(); // Hide the table
            $('#reportClients').hide();
            return; // Exit the function
        }
        $('#clientReportTitle1').show(''); // Clear the report title
        $('#reportClientTable').show(); // Hide the table
        $('#reportClients').show();
        console.log(type);
        $('#reportClients').show();
        // Define a mapping of report types to titles

        if ($.fn.DataTable.isDataTable('#reportClientTable')) {
            $('#reportClientTable').DataTable().clear().destroy();
            $('#reportClientTable').empty(); // Remove existing columns and rows
        }
        var result = getStartAndEndDate('Client');
        // This arrangement can be altered based on how we want the date's format to appear.
        let currentDate = `${result.startDate} - ${result.endDate}`;

        let reportTitle = '';
        let ajaxType = type;
        let columns = [];
        // Define common DataTable settings
        let dataTableSettings = {
            processing: true,
            serverSide: true,
            searching: true, // Disable the search box
            // Disable the "Showing x to y of z entries" message
            "lengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "pageLength": 10,
            destroy: true,
            dom: '<"d-flex justify-content-between align-items-center"lBf>rtip', // Custom layout
            "buttons": [{
                    extend: 'csv',
                    title: 'Report :  Clients ' + text + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''), // Custom title for CSV
                    exportOptions: {
                        columns: ':visible',
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'excel',
                    title: 'Report :  Clients ' + text + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''), // Custom title for Excel
                    exportOptions: {
                        columns: ':visible',
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'pdf',
                    title: 'Report :  Clients ' + text + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''),
                    orientation: 'landscape', // Makes table fit better
                    pageSize: 'A4',
                    customize: function(doc) {
                        let table = doc.content[1].table;
                        let columnCount = table.body[0].length;

                        // Dynamically set column widths to distribute space evenly
                        // This adjusts each column to take up equal space

                        // Adjust styles
                        // table.widths = Array(columnCount).fill('*');
                        table.widths = ['8%', ...Array(columnCount - 1).fill('*')];
                        doc.defaultStyle.fontSize = 8; // Font size for table data
                        doc.styles.tableHeader.fontSize = 8; // Larger header font
                        doc.styles.title.fontSize = 18; // Title font
                        doc.pageMargins = [5, 5, 5, 5]; // Reduce margins
                        doc.styles.title.bold = true;
                        // Force full-page width usage
                        doc.content[1].layout = {
                            hLineWidth: function(i, node) {
                                return 0.5; // Line thickness
                            },
                            vLineWidth: function(i, node) {
                                return 0.5;
                            },
                            paddingLeft: function(i, node) {
                                return 4; // Adjust left padding
                            },
                            paddingRight: function(i, node) {
                                return 4;
                            }
                        };

                        // Center-align all table content
                        doc.content[1].table.body.forEach(function(row, rowIndex) {
                            row.forEach(function(cell, cellIndex) {
                                if (typeof cell === 'object') {
                                    cell.alignment = 'center'; // Center-align text in each cell
                                }
                            });
                        });

                        // Center-align the title
                        doc.content[0].alignment = 'center';
                    },
                    exportOptions: {
                        columns: ':visible', // Ensure all visible columns are exported
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                }
            ],
            ajax: {
                url: "{{ route('clientsReport') }}",
                data: function(d) {
                    d.type = type;
                    d.startDate = result.startDate,
                        d.endDate = result.endDate
                },
                dataSrc: function(json) {
                    if (json.data.length === 0) {
                        // Swal.fire({
                        //     icon: 'warning',
                        //     title: 'No Data Available',
                        //     text: 'No Data found for Report : Clients ('  + text + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''),
                        //     confirmButtonText: 'OK'
                        // });
                    }

                    return json.data;
                }

            },
            language: {
                emptyTable: 'No Data found for Report : Clients (' + text + '  (' + currentDate + ')',
            },
            initComplete: function(settings, json) {
                var api = this.api();
                var rowCount = api.rows().count(); // Get total number of rows
                var buttons = api.buttons();

                // Disable buttons if no records
                if (rowCount === 0) {
                    buttons.disable(); // Disable all buttons if no records
                } else {
                    buttons.enable(); // Enable buttons if records exist
                }
            },
            dataSrc: function(json) {
                console.log("AJAX Response:", json); // ✅ Debug the response
                if (!json.data) {
                    console.error("DataTables Error: Missing 'data' key in response");
                    return [];
                }
                return json.data;
            },
            "order": [], //

            columns: columns,
        };
        // Configure DataTable based on report type
        if (type == 'homeCountry') {
            reportTitle = 'Clients By Home Country';
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false

                }, {
                    title: "Country Name",
                    data: 'country_name',
                    name: 'country_name'
                },
                {
                    title: "No. of Clients",
                    data: 'No_of_Subscribers',
                    name: 'No_of_Subscribers'
                }
            ];
        } else if (type == 'ageGroup') {
            reportTitle = 'Clients By Age Group';
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Age Groups",
                    data: 'age_group',
                    name: 'age_group'
                },
                {
                    title: "No of Clients",
                    data: 'count',
                    name: 'count'
                }
            ];
        } else if (type == 'appType') {
            reportTitle = 'Clients By Application Type';
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Application Type",
                    data: 'application_name',
                    name: 'application_name'
                },
                {
                    title: "No of Clients",
                    data: 'number_of_clients',
                    name: 'number_of_clients',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }
            ];
        } else if (type == 'applications') {
            reportTitle = 'Clients By No. of Applications';
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Client Name (ID)",
                    data: 'client_name',
                    name: 'client_name'
                },
                {
                    title: "No of Applications",
                    data: 'no_of_applications',
                    name: 'no_of_applications',

                }
            ];
        } else if (type == 'payment_mode') {
            reportTitle = 'Clients By Payment Mode';
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Payment Mode",
                    data: 'payment_mode',
                    name: 'payment_mode'
                },
                {
                    title: "No of Clients",
                    data: 'number_of_payment',
                    name: 'number_of_payment'
                }
            ];
        } else if (type == 'paymentAmount') {
            reportTitle = ' Clients By Outstanding Payments Amount';
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Client Name (ID)",
                    data: 'client_name',
                    name: 'client_name'
                },
                {
                    title: "Name of Application/Service",
                    data: 'application_name',
                    name: 'application_name',

                },
                {
                    title: "Outstanding Payment",
                    data: "amount_to_pay",
                    name: "amount_to_pay",

                },
                {
                    title: "Payment Due Date",
                    data: "due_date",
                    name: "due_date",

                }
            ];
        } else if (type == 'document') {
            reportTitle = 'Clients By No. of Documents Stored';
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Client Name (ID)",
                    data: 'client_name',
                    name: 'client_name'
                },
                {
                    title: "No of Documents",
                    data: 'no_of_docs',
                    name: 'no_of_docs',
                    orderable: false,
                    searchable: false
                }
            ];
        } else if (type == 'dependants') {
            reportTitle = 'Clients By No. of Dependants';
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Client Name (ID)",
                    data: 'name',
                    name: 'name'
                },
                {
                    title: "No of Dependants",
                    data: 'dependants_count',
                    name: 'dependants_count'
                }
            ];
        } else if (type == 'byTimeline') {
            reportTitle = 'Clients By Timeline (Duration)';
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Timeline (Duration)",
                    data: 'year',
                    name: 'year',

                },
                {
                    title: "No of Users",
                    data: 'count',
                    name: 'count',

                }
            ];
        } else if (type == 'yearly') {
            reportTitle = "Clients By Year";
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Year",
                    data: 'year',
                    name: 'year'
                },
                {
                    title: "Count",
                    data: 'year_count',
                    name: 'year_count'
                }
            ];
        }

        $('#custom_date_picker2').prop('disabled', type == 'byTimeline' || type == 'yearly');


        // Initialize the DataTable with the new settings
        $('#clientReportTitle1').html(reportTitle);
        $('#reportClientTable').DataTable(dataTableSettings);
    }

    function onClickApplication() {


        var result = getStartAndEndDate('Application');
        // This arrangement can be altered based on how we want the date's format to appear.
        let currentDate = `${result.startDate} - ${result.endDate}`;

        deativeTabs();
        applicationTable1 = true;

        var applicationTable = $('#applicationTable1').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            "lengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "pageLength": 10,
            dom: '<"d-flex justify-content-between align-items-center w-bold"lBf>rtip', // Custom layout
            buttons: [{
                    extend: 'csv',
                    title: 'Report : Applications  (' + currentDate + ')',
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'excel',
                    title: 'Report : Applications (' + currentDate + ')',
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'pdf',
                    title: 'Report : Applications (' + currentDate + ')',
                    orientation: 'landscape', // Makes table fit better
                    pageSize: 'A4',
                    customize: function(doc) {
                        let table = doc.content[1].table;
                        let columnCount = table.body[0].length;

                        // Dynamically set column widths to distribute space evenly
                        // table.widths = Array(columnCount).fill('*'); // This adjusts each column to take up equal space
                        table.widths = [
                            '3%', // Column 1 (3% width)
                            '*',
                            '*', // Column 2 (remaining space, evenly distributed)
                            '8%', // Column 3 (2% width)
                            '8%', // Column 5 (2% width)
                            '8%', // Column 6 (remaining space, evenly distributed)
                            '6%',
                            '6%', // Column 7 (2% width)
                            '6%' // Column 8 (3% width)
                        ];
                        // Adjust styles
                        doc.defaultStyle.fontSize = 8; // Font size for table data
                        doc.styles.tableHeader.fontSize = 8; // Larger header font
                        doc.styles.title.fontSize = 18; // Title font
                        doc.pageMargins = [5, 5, 5, 5]; // Reduce margins
                        doc.styles.title.bold = true;

                        // Force full-page width usage
                        doc.content[1].layout = {
                            hLineWidth: function(i, node) {
                                return 0.5; // Line thickness
                            },
                            vLineWidth: function(i, node) {
                                return 0.5;
                            },
                            paddingLeft: function(i, node) {
                                return 4; // Adjust left padding
                            },
                            paddingRight: function(i, node) {
                                return 4;
                            }
                        };

                        // Center-align all table content
                        doc.content[1].table.body.forEach(function(row, rowIndex) {
                            row.forEach(function(cell, cellIndex) {
                                if (typeof cell === 'object') {
                                    cell.alignment = 'center'; // Center-align text in each cell
                                }
                            });
                        });

                        // Center-align the title
                        doc.content[0].alignment = 'center';
                    },
                    exportOptions: {
                        columns: ':visible', // Ensure all visible columns are exported
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                }
            ],
            ajax: {
                url: "{{ route('manage_reports_applications') }}",
                data: function(d) {
                    // Add additional data here
                    d.startDate = result.startDate;
                    d.endDate = result.endDate;

                },
                dataSrc: function(json) {
                    if (json.data.length === 0) {
                        // Swal.fire({
                        //     icon: 'warning',
                        //     title: 'No Data Available',
                        //     text: 'No Data found for Report : Applications  (' + currentDate + ')',
                        //     confirmButtonText: 'OK'
                        // });
                    }
                    return json.data;
                }
            },
            language: {
                emptyTable: 'No Data found for Report : Applications  (' + currentDate + ')',
            },
            initComplete: function(settings, json) {
                var api = this.api();
                var rowCount = api.rows().count(); // Get total number of rows
                var buttons = api.buttons();

                // Disable buttons if no records
                if (rowCount === 0) {
                    buttons.disable(); // Disable all buttons if no records
                } else {
                    buttons.enable(); // Enable buttons if records exist
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                // {
                //     data: 'subscriber_id',
                //     name: 'subscriber_id'
                // },
                {
                    data: 'client_id',
                    name: 'client_id'
                },
                {
                    data: 'application_id',
                    name: 'application_id'
                },
                {
                    data: 'visa_country',
                    name: 'visa_country'
                },
                {
                    data: 'application_country',
                    name: 'application_country'
                },
                {
                    data: 'application_status',
                    name: 'application_status'
                },
                {
                    data: 'start_date',
                    name: 'start_date'
                },
                {
                    data: 'end_date',
                    name: 'end_date'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
            ],

        });

    }

    function onChangeApplicationReport(type, text) {
        let selectedText = text;

        if (type === '') {
            // If empty, hide the table and the report title
            $('#reportApplications').hide(''); // Clear the report title
            $('#reportApplicationTable').hide(); // Hide the table
            $('#applicationReportTitle').hide();
            return; // Exit the function
        }
        $('#reportApplicationTable').show(); // Hide the table
        $('#applicationReportTitle').show();
        $('#reportApplications').show();
        var result = getStartAndEndDate('Application');

        let currentDate = `${result.startDate} - ${result.endDate}`;


        let reportTitle = '';
        let ajaxType = type;
        let columns = [];
        let orders = [];

        if (type == 'visaCountry') {
            reportTitle = 'Applications By Client’s Visa Country';
            columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Visa Countries",
                    data: 'country',
                    name: 'country'
                },
                {
                    title: "No of Applications",
                    data: 'application_count',
                    name: 'application_count'
                }
            ];
        } else if (type == 'applicationCountry') {
            reportTitle = 'Applications By Client’s Home Country';
            columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Country Name",
                    data: 'country',
                    name: 'country'
                },
                {
                    title: "No of Applications",
                    data: 'application_count',
                    name: 'application_count'
                }
            ];
        } else if (type == 'applicationType') {
            reportTitle = 'Applications By Application Type';
            columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Application Type",
                    data: 'application_name',
                    name: 'application_name'
                },
                {
                    title: "No of Applications",
                    data: 'application_count',
                    name: 'application_count'
                }
            ];
            orders = [];
        } else if (type == 'noOfApplicaitonsPerApplication') {
            reportTitle = 'Applications By No. of Applicants per Application (Single/Joint)';
            columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Application Name (ID)",
                    data: 'applicationName',
                    name: 'applicationName'
                },
                {
                    title: "No of Single",
                    data: 'single',
                    name: 'single'
                },
                {
                    title: "No of Joint",
                    data: 'joint',
                    name: 'joint'
                }
            ];
        } else if (type == 'paymentMode') {
            reportTitle = 'Applications By Payment Mode';
            columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Payment Mode",
                    data: 'payment_mode',
                    name: 'payment_mode'
                },
                {
                    title: "No of Applications",
                    data: 'application_count',
                    name: 'application_count'
                }
            ];
        } else if (type == 'paymentAmount') {
            reportTitle = 'Applications By Outstanding Payments Amount';
            columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Client Name (ID)",
                    data: 'client_name',
                    name: 'client_name'
                }, {
                    title: "Application Name (ID)",
                    data: 'application_name',
                    name: 'application_name'
                },
                {
                    title: "Outstanding Amount",
                    data: 'amount_to_pay',
                    name: 'amount_to_pay'
                }, {
                    title: "Payment Due Date",
                    data: "due_date",
                    name: "due_date",

                }
            ];
        } else if (type == 'documentStored') {
            reportTitle = 'Applications By No. of Documents Stored';
            columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Application Name (ID)",
                    data: 'name',
                    name: 'name'
                },
                {
                    title: "No of Documents",
                    data: 'no_of_docs',
                    name: 'no_of_docs'
                }
            ];
        } else if (type == 'noOfApplicaitonsBy') {
            var type = window.selectedLabel;
            console.log(type);
            reportTitle = 'Applications By No. of Applications By Timeline';
            columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Timeline (Duration)",
                    data: 'year',
                    name: 'year',

                },
                {
                    title: "No of Applications",
                    data: 'count',
                    name: 'count',

                }
            ];
        } else if (type == 'yearly') {
            reportTitle = "Applications By Year";
            columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Year",
                    data: 'year',
                    name: 'year'
                },
                {
                    title: "Count",
                    data: 'year_count',
                    name: 'year_count'
                }
            ];
        }
        
        $('#custom_date_picker3').prop('disabled', type == 'noOfApplicaitonsBy' || type == 'yearly');
        $('#applicationReportTitle').html(reportTitle);

        if ($.fn.DataTable.isDataTable('#reportApplicationTable')) {
            $('#reportApplicationTable').DataTable().clear().destroy();
            $('#reportApplicationTable').empty(); // Remove existing columns and rows
        }
        $('#reportApplicationTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true, // Disable the search box
            // Disable the "Showing x to y of z entries" message
            "lengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "pageLength": 10,
            destroy: true,
            dom: '<"d-flex justify-content-between align-items-center w-bold"lBf>rtip', // Custom layout
            buttons: [{
                    extend: 'csv',
                    title: 'Report : Applications  ' + selectedText + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''), // Custom title for CSV
                    exportOptions: {
                        modifier: {
                            page: 'all'
                        }
                    } // Export all data, not just the current page
                },
                {
                    extend: 'excel',
                    title: 'Report : Applications  ' + selectedText + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''), // Custom title for Excel
                    exportOptions: {
                        modifier: {
                            page: 'all'
                        }
                    } // Export all data, not just the current page
                },
                {
                    extend: 'pdf',
                    title: 'Report : Applications  ' + selectedText + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''), // Custom title for PDF
                    orientation: 'landscape', // Makes table fit better
                    pageSize: 'A4',
                    customize: function(doc) {
                        let table = doc.content[1].table;
                        let columnCount = table.body[0].length;

                        // Dynamically set column widths to distribute space evenly
                        // table.widths = Array(columnCount).fill('*');
                        table.widths = ['8%', ...Array(columnCount - 1).fill('*')];
                        // Adjust styles
                        doc.defaultStyle.fontSize = 10; // Font size for table data
                        doc.styles.tableHeader.fontSize = 12; // Larger header font
                        doc.styles.title.fontSize = 18; // Title font
                        doc.pageMargins = [10, 10, 10, 10]; // Reduce margins
                        doc.styles.title.bold = true;
                        // Force full-page width usage
                        doc.content[1].layout = {
                            hLineWidth: function(i, node) {
                                return 0.5; // Line thickness
                            },
                            vLineWidth: function(i, node) {
                                return 0.5;
                            },
                            paddingLeft: function(i, node) {
                                return 4; // Adjust left padding
                            },
                            paddingRight: function(i, node) {
                                return 4;
                            }
                        };

                        // Center-align all table content
                        doc.content[1].table.body.forEach(function(row, rowIndex) {
                            row.forEach(function(cell, cellIndex) {
                                if (typeof cell === 'object') {
                                    cell.alignment = 'center'; // Center-align text in each cell
                                }
                            });
                        });

                        // Center-align the title
                        doc.content[0].alignment = 'center';
                    },
                    exportOptions: {
                        columns: ':visible',
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                }
            ],
            ajax: {
                url: "{{ route('applicationsReport') }}",
                data: function(d) {
                    d.type = ajaxType;
                    d.startDate = result.startDate
                    d.endDate = result.endDate
                },
                dataSrc: function(json) {
                    if (json.data.length === 0) {
                        // Swal.fire({
                        //     icon: 'warning',
                        //     title: 'No Data Available',
                        //     text: 'No Data found for Report : Applications  ' + selectedText + ' (' + currentDate + ')',
                        //     confirmButtonText: 'OK'
                        // });
                    }
                    return json.data;
                }
            },
            language: {
                emptyTable: 'No Data found for Report : Applications  ' + selectedText + ' (' + currentDate + ')',
            },
            initComplete: function(settings, json) {
                var api = this.api();
                var rowCount = api.rows().count(); // Get total number of rows
                var buttons = api.buttons();

                // Disable buttons if no records
                if (rowCount === 0) {
                    buttons.disable(); // Disable all buttons if no records
                } else {
                    buttons.enable(); // Enable buttons if records exist
                }
            },
            columns: columns,
            order: orders,

        });
    }

    function onClickUsers() {
        var result = getStartAndEndDate('User');
        // This arrangement can be altered based on how we want the date's format to appear.
        let currentDate = `${result.startDate} - ${result.endDate}`;
        deativeTabs();
        userTable1 = true;

        var userTable = $('#userTable1').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            "lengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "pageLength": 10,
            dom: '<"d-flex justify-content-between align-items-center w-bold"lBf>rtip', // Custom layout
            "buttons": [{
                    extend: 'csv',
                    title: 'Report : Users (' + currentDate + ')',

                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'excel',
                    title: 'Report : Users (' + currentDate + ')', // Custom t
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'pdf',
                    title: 'Report : Users (' + currentDate + ')', // Custom t
                    orientation: 'landscape', // Makes table fit better
                    pageSize: 'A4',
                    customize: function(doc) {
                        let table = doc.content[1].table;
                        let columnCount = table.body[0].length;

                        // Dynamically set column widths to distribute space evenly
                        table.widths = [
                            '3%', // Column 1 (3% width)
                            '*',
                            '12%', // Column 3 (2% width)
                            '8%', // Column 5 (2% width)
                            '*', // Column 6 (remaining space, evenly distributed)
                            '10%',
                            '6%', // Column 7 (2% width)
                            '10%' // Column 8 (3% width)
                        ];
                        // Adjust styles
                        doc.defaultStyle.fontSize = 10; // Font size for table data
                        doc.styles.tableHeader.fontSize = 12; // Larger header font
                        doc.styles.title.fontSize = 18; // Title font
                        doc.pageMargins = [10, 10, 10, 10]; // Reduce margins
                        doc.styles.title.bold = true;
                        // Force full-page width usage
                        doc.content[1].layout = {
                            hLineWidth: function(i, node) {
                                return 0.5; // Line thickness
                            },
                            vLineWidth: function(i, node) {
                                return 0.5;
                            },
                            paddingLeft: function(i, node) {
                                return 4; // Adjust left padding
                            },
                            paddingRight: function(i, node) {
                                return 4;
                            }
                        };

                        // Center-align all table content
                        doc.content[1].table.body.forEach(function(row, rowIndex) {
                            row.forEach(function(cell, cellIndex) {
                                if (typeof cell === 'object') {
                                    cell.alignment = 'center'; // Center-align text in each cell
                                }
                            });
                        });

                        // Center-align the title
                        doc.content[0].alignment = 'center';
                    },
                    exportOptions: {
                        columns: ':visible',
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                }
            ],
            ajax: {
                url: "{{ route('manage_user_reports') }}",
                data: function(d) { // Add additional data here
                    d.startDate = result.startDate;
                    d.endDate = result.endDate;

                },
                dataSrc: function(json) {
                    if (json.data.length === 0) {
                        // Swal.fire({
                        //     icon: 'warning',
                        //     title: 'No Data Available',
                        //     text: 'No Data found for Report : Users  (' + currentDate + ')',
                        //     confirmButtonText: 'OK'
                        // });
                    }
                    return json.data;
                }
            },
            language: {
                emptyTable: 'No Data found for Report : Users  (' + currentDate + ')',
            },
            initComplete: function(settings, json) {
                var api = this.api();
                var rowCount = api.rows().count(); // Get total number of rows
                var buttons = api.buttons();

                // Disable buttons if no records
                if (rowCount === 0) {
                    buttons.disable(); // Disable all buttons if no records
                } else {
                    buttons.enable(); // Enable buttons if records exist
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                // {
                //     data: 'subscriber',
                //     name: 'subscriber'
                // },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'city',
                    name: 'city'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'designation',
                    name: 'designation'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
            ],

        });

    }

    function onchangeUserReport(type, text) {
        let selectedText = text;

        if (type === '') {
            // If empty, hide the table and the report title
            $('#reportUsers').hide(); // Hide the table
            $('#clientReportTitle').hide();
            return; // Exit the function
        }
        $('#clientReportTitle').show();
        var result = getStartAndEndDate('User');

        // Ensure DataTable is properly destroyed and HTML table is cleared
        if ($.fn.DataTable.isDataTable('#reportUserTable')) {
            $('#reportUserTable').DataTable().clear().destroy();
            $('#reportUserTable').empty(); // Remove existing columns and rows
        }
        let orders = [];
        let currentDate = `${result.startDate} - ${result.endDate}`;
        $('#reportUsers').show();

        // Define common DataTable settings
        let dataTableSettings = {
            processing: true,
            serverSide: true,
            searching: true, // Disable the search box
            // Disable the "Showing x to y of z entries" message
            "lengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "pageLength": 10,
            destroy: true,
            dom: '<"d-flex justify-content-between align-items-center"lBf>rtip', // Custom layout
            buttons: [{
                    extend: 'csv',
                    title: 'Report : Users   ' + selectedText + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''), // Custom title for CSV
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'excel',
                    title: 'Report : Users  ' + selectedText + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''), // Custom title for Excel
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'pdf',
                    title: 'Report : Users  ' + selectedText + ' (' + currentDate + ')', // Custom title for Excel
                    orientation: 'landscape', // Makes table fit better
                    pageSize: 'A4',
                    customize: function(doc) {
                        let table = doc.content[1].table;
                        let columnCount = table.body[0].length;

                        // Dynamically set column widths to distribute space evenly
                        table.widths = ['8%', ...Array(columnCount - 1).fill('*')];

                        // Adjust styles
                        doc.defaultStyle.fontSize = 10; // Font size for table data
                        doc.styles.tableHeader.fontSize = 8; // Larger header font
                        doc.styles.title.fontSize = 18; // Title font
                        doc.pageMargins = [10, 10, 10, 10]; // Reduce margins
                        doc.styles.title.bold = true;
                        // Force full-page width usage
                        doc.content[1].layout = {
                            hLineWidth: function(i, node) {
                                return 0.5; // Line thickness
                            },
                            vLineWidth: function(i, node) {
                                return 0.5;
                            },
                            paddingLeft: function(i, node) {
                                return 4; // Adjust left padding
                            },
                            paddingRight: function(i, node) {
                                return 4;
                            }
                        };

                        // Center-align all table content
                        doc.content[1].table.body.forEach(function(row, rowIndex) {
                            row.forEach(function(cell, cellIndex) {
                                if (typeof cell === 'object') {
                                    cell.alignment = 'center'; // Center-align text in each cell
                                }
                            });
                        });

                        // Center-align the title
                        doc.content[0].alignment = 'center';
                    },
                    exportOptions: {
                        columns: ':visible',
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                }
            ],
            ajax: {
                url: "{{ route('usersReport') }}",
                data: function(d) {
                    d.type = type;
                    d.startDate = result.startDate,
                        d.endDate = result.endDate
                },
                dataSrc: function(json) {
                    if (json.data.length === 0) {
                        // Swal.fire({
                        //     icon: 'warning',
                        //     title: 'No Data Available',
                        //     text: 'No Data found for Report : Users  '+selectedText+(!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''),
                        //     confirmButtonText: 'OK'
                        // });
                    }
                    return json.data;
                }
            },
            language: {
                emptyTable: 'No Data found for Report :  Users  ' + selectedText + '  (' + currentDate + ')',
            },
            initComplete: function(settings, json) {
                var api = this.api();
                var rowCount = api.rows().count(); // Get total number of rows
                var buttons = api.buttons();

                // Disable buttons if no records
                if (rowCount === 0) {
                    buttons.disable(); // Disable all buttons if no records
                } else {
                    buttons.enable(); // Enable buttons if records exist
                }
            },
            order: orders, //
            // order: [
            //     [1, 'desc']
            // ]
        };

        // Configure DataTable based on report type
        if (type == 'byRole') {
            $('#clientReportTitle').html('Users By Role');
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Roles",
                    data: 'designation',
                    name: 'designation'
                },
                {
                    title: "No. of Users",
                    data: 'users',
                    name: 'users'
                }
            ];
        } else if (type == "ageGroup") {
            $('#clientReportTitle').html('Users By Age Group');
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Age Groups",
                    data: 'age_group',
                    name: 'age_group'
                },
                {
                    title: "No. of Users",
                    data: 'count',
                    name: 'count'
                }
            ];
            dataTableSettings.orders = [
                1, 'asc'
            ]
        } else if (type == "applicationProcessed") {
            $('#clientReportTitle').html('Users By No. of Application Assigned');
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "User Name (ID)",
                    data: 'user_name',
                    name: 'user_name'
                },
                {
                    title: "No. of Applications",
                    data: 'count',
                    name: 'count'
                }
            ];
        } else if (type == "meetingNotes") {
            $('#clientReportTitle').html('Users By Meeting Notes');
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "User Name (ID)",
                    data: 'username',
                    name: 'username'
                }, {
                    title: "Communication Mode",
                    data: 'communication_summary',
                    name: 'communication_summary'
                },
                {
                    title: "No. of Meeting Notes",
                    data: 'total_discussions',
                    name: 'total_discussions'
                }
            ];
        } else if (type == "communication") {
            $('#clientReportTitle').html('Users By Mode of Communication');
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Mode of Communication",
                    data: 'communication_type',
                    name: 'communication_type',
                    orderable: false,
                    searchable: false

                }, {

                    title: "No of Users",
                    data: 'total_users',
                    name: 'total_users'
                }

            ];
        } else if (type == "message") {
            $('#clientReportTitle').html('Users By No. of Messages');
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "User Name (ID)",
                    data: 'user_id',
                    name: 'user_id'
                },
                {
                    title: "No. of Messages Sent",
                    data: 'total_messages_sent',
                    name: 'total_messages_sent',
                    orderable: false,
                    searchable: false
                },
                {
                    title: "No. of Messages Received",
                    data: 'total_messages_received',
                    name: 'total_messages_received',
                    orderable: false,
                    searchable: false
                }
            ];
        } else if (type == "users") {
            $('#clientReportTitle').html('Users By Timeline');
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Timeline (Duration)",
                    data: 'type',
                    name: 'type',

                },
                {
                    title: "No of Users",
                    data: 'count',
                    name: 'count',

                }
            ];
        }  else if (type == 'yearly') {
            $('#clientReportTitle').html('Users By Year');
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Year",
                    data: 'year',
                    name: 'year'
                },
                {
                    title: "Count",
                    data: 'year_count',
                    name: 'year_count'
                }
            ];
        }

        $('#custom_date_picker4').prop('disabled', type == 'byTimeline' || type == 'yearly');

        // Initialize the DataTable with the new settings
        $('#reportUserTable').DataTable(dataTableSettings);
    }

    function onClickDocuments() {

        // This arrangement can be altered based on how we want the date's format to appear.
        var result = getStartAndEndDate('Document');
        let currentDate = `${result.startDate} - ${result.endDate}`;

        deativeTabs();
        Documents1 = true;

        var refferalsTable = $('#documentsTable1').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            "lengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "pageLength": 10,
            dom: '<"d-flex justify-content-between align-items-center"lBf>rtip', // Custom layout
            "buttons": [{
                    extend: 'csv',
                    title: 'Report : Documents  (' + currentDate + ')', // Custom title for C
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'excel',
                    title: 'Report : Documents  (' + currentDate + ')', // Custom title for Exc
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'pdf',
                    title: 'Report : Documents  (' + currentDate + ')',
                    orientation: 'landscape', // Makes table fit better
                    pageSize: 'A4',
                    customize: function(doc) {
                        let table = doc.content[1].table;
                        let columnCount = table.body[0].length;

                        // Dynamically set column widths to distribute space evenly
                        table.widths = [
                            '5%', // Column 1 (3% width)
                            '*',
                            '*', // Column 3 (2% width)
                            '10%', // Column 5 (2% width)
                            '10%', // Column 6 (remaining space, evenly distributed)
                            '15%',
                            '8%', // Column 7 (2% width)
                            '9%' // Column 8 (3% width)
                        ];

                        doc.defaultStyle.fontSize = 10; // Font size for table data
                        doc.styles.tableHeader.fontSize = 12; // Larger header font
                        doc.styles.title.fontSize = 18; // Title font
                        doc.pageMargins = [10, 10, 10, 10]; // Reduce margins
                        doc.styles.title.bold = true;
                        // Force full-page width usage

                        // Force full-page width usage
                        doc.content[1].layout = {
                            hLineWidth: function(i, node) {
                                return 0.5; // Line thickness
                            },
                            vLineWidth: function(i, node) {
                                return 0.5;
                            },
                            paddingLeft: function(i, node) {
                                return 4; // Adjust left padding
                            },
                            paddingRight: function(i, node) {
                                return 4;
                            }
                        };

                        // Center-align all table content
                        doc.content[1].table.body.forEach(function(row, rowIndex) {
                            row.forEach(function(cell, cellIndex) {
                                if (typeof cell === 'object') {
                                    cell.alignment = 'center'; // Center-align text in each cell
                                }
                            });
                        });

                        // Center-align the title
                        doc.content[0].alignment = 'center';
                    },
                    exportOptions: {
                        columns: ':visible',
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                }
            ],
            ajax: {
                url: "{{ route('client_documents_reports') }}",
                data: function(d) {
                    // Add additional data here
                    d.startDate = result.startDate;
                    d.endDate = result.endDate;

                },
                dataSrc: function(json) {
                    if (json.data.length === 0) {
                        // Swal.fire({
                        //     icon: 'warning',
                        //     title: 'No Data Available',
                        //     text: 'No Data found for Report : Documents  (' + currentDate + ')',
                        //     confirmButtonText: 'OK'
                        // });
                    }
                    return json.data;
                }
            },
            language: {
                emptyTable: 'No Data found for Report : Documents   (' + currentDate + ')',
            },
            initComplete: function(settings, json) {
                var api = this.api();
                var rowCount = api.rows().count(); // Get total number of rows
                var buttons = api.buttons();

                // Disable buttons if no records
                if (rowCount === 0) {
                    buttons.disable(); // Disable all buttons if no records
                } else {
                    buttons.enable(); // Enable buttons if records exist
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'client_id',
                    name: 'client_id'
                },
                {
                    data: 'application_id',
                    name: 'application_id'
                },
                {
                    data: 'doc_type',
                    name: 'doc_type'
                },
                {
                    data: 'doc_name',
                    name: 'doc_name'
                },
                {
                    data: 'doc_file',
                    name: 'doc_file'
                },
                {
                    data: 'doc_size',
                    name: 'doc_size'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                //{ data: 'action', name: 'action' },

            ],

        });
    }

    function onchangeDocumentReport(type, text) {
        let selectedText = text;

        if (type === '') {
            // If empty, hide the table and the report title
            $('#reportDocuments').hide(); // Hide the table
            $('#documentsReportTitle').hide();
            return; // Exit the function
        }
        $('#documentsReportTitle').show();
        var result = getStartAndEndDate('Document');
        $('#reportDocuments').show();
        let currentDate = `${result.startDate} - ${result.endDate}`;
        $('#documentsTable').show();

        let reportTitle = '';
        let ajaxType = type;
        let columns = [];
        let orders = [];

        if (type == 'byApplication') {
            reportTitle = 'Documents By Application (Top 20)';
            columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                },
                {
                    title: "Client Name (ID)",
                    data: 'client',
                    name: 'client'
                },
                {
                    title: "Application Name  (ID)",
                    data: 'application',
                    name: 'application'
                },
                {
                    title: "No. of Documents",
                    data: 'no_of_docs',
                    name: 'no_of_docs'
                }
            ];
        } else if (type == 'byClient') {
            reportTitle = 'Documents By Client (Top 20)';
            columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                },
                {
                    title: "Client Name (ID)",
                    data: 'client',
                    name: 'client'
                },
                {
                    title: "No. of Documents",
                    data: 'no_of_docs',
                    name: 'no_of_docs'
                }
            ];
        } else if (type == 'bySubscriber') {
            reportTitle = 'Documents By Subscriber Stored Document';
            columns = [{
                    title: "SUB(ID)",
                    data: 'subscriber',
                    name: 'subscriber'
                },
                {
                    title: "No. of Documents",
                    data: 'no_of_docs',
                    name: 'no_of_docs'
                }
            ];
        } else if (type == 'bySize') {
            reportTitle = 'Documents By Filesize (Top 50)';
            columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Client Name (ID)",
                    data: 'client_name',
                    name: 'client_name'
                }, {
                    title: "Application Name (ID)",
                    data: 'application_name',
                    name: 'application_name'
                }, {
                    title: "Document Name (ID)",
                    data: 'docs_name',
                    name: 'docs_name'
                },
                {
                    title: "Filesize",
                    data: 'formatted_size',
                    name: 'formatted_size'
                }
            ];
            orders = [
                1, 'asc'
            ]
        } else if (type == 'byFiletype') {
            reportTitle = 'Documents By Filetype';
            columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                },
                {
                    title: "File Type",
                    data: 'file_type',
                    name: 'file_type'
                },
                {
                    title: "No. of Documents",
                    data: 'total_count',
                    name: 'total_count'
                }
            ];
        } else if (type == 'byTimeLine') {
            reportTitle = 'Documents By Timeline (Duration)';
            columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Timeline (Duration)",
                    data: 'year',
                    name: 'year',

                },
                {
                    title: "No of Documents",
                    data: 'count',
                    name: 'count',

                }
            ];
        }  else if (type == 'yearly') {
            reportTitle = 'Documents By Year';
            Columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Year",
                    data: 'year',
                    name: 'year'
                },
                {
                    title: "Count",
                    data: 'year_count',
                    name: 'year_count'
                }
            ];
        }
        $('#custom_date_picker14').prop('disabled', type == 'byTimeLine' || type == 'yearly');

        $('#documentsReportTitle').html(reportTitle);

        if ($.fn.DataTable.isDataTable('#documentsTable')) {
            $('#documentsTable').DataTable().clear().destroy();
            $('#documentsTable').empty(); // Remove existing columns and rows
        }
        $('#documentsTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true, // Disable the search box
            // Disable the "Showing x to y of z entries" message
            "lengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "pageLength": 10,
            destroy: true,
            dom: '<"d-flex justify-content-between align-items-center"lBf>rtip', // Custom layout
            buttons: [{
                    extend: 'csv',
                    title: 'Report : Documents  ' + selectedText + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''), // Custom title for Excel
                    exportOptions: {
                        modifier: {
                            page: 'all'
                        }
                    } // Export all data, not just the current page
                },
                {
                    extend: 'excel',
                    title: 'Report : Documents  ' + selectedText + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''), // Custom title for Excel
                    exportOptions: {
                        modifier: {
                            page: 'all'
                        }
                    } // Export all data, not just the current page
                },
                {
                    extend: 'pdf',
                    title: 'Report : Documents  ' + selectedText + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''), // Custom title for Excel
                    orientation: 'landscape', // Makes table fit better
                    pageSize: 'A4',
                    customize: function(doc) {
                        let table = doc.content[1].table;
                        let columnCount = table.body[0].length;

                        // Dynamically set column widths to distribute space evenly
                        table.widths = ['8%', ...Array(columnCount - 1).fill('*')];

                        // Adjust styles
                        doc.defaultStyle.fontSize = 10; // Font size for table data
                        doc.styles.tableHeader.fontSize = 12; // Larger header font
                        doc.styles.title.fontSize = 18; // Title font
                        doc.pageMargins = [10, 10, 10, 10]; // Reduce margins
                        doc.styles.title.bold = true;

                        // Force full-page width usage
                        doc.content[1].layout = {
                            hLineWidth: function(i, node) {
                                return 0.5; // Line thickness
                            },
                            vLineWidth: function(i, node) {
                                return 0.5;
                            },
                            paddingLeft: function(i, node) {
                                return 4; // Adjust left padding
                            },
                            paddingRight: function(i, node) {
                                return 4;
                            }
                        };

                        // Center-align all table content
                        doc.content[1].table.body.forEach(function(row, rowIndex) {
                            row.forEach(function(cell, cellIndex) {
                                if (typeof cell === 'object') {
                                    cell.alignment = 'center'; // Center-align text in each cell
                                }
                            });
                        });

                        // Center-align the title
                        doc.content[0].alignment = 'center';
                    },
                    exportOptions: {
                        columns: ':visible',
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                }
            ],
            ajax: {
                url: "{{ route('documentReport') }}",
                data: function(d) {
                    d.type = ajaxType;
                    d.startDate = result.startDate
                    d.endDate = result.endDate
                },
                dataSrc: function(json) {
                    if (json.data.length === 0) {
                        // Swal.fire({
                        //     icon: 'warning',
                        //     title: 'No Data Available',
                        //     text: 'No Data found for Report : Documents  ' + selectedText + ' (' + currentDate + ')',
                        //     confirmButtonText: 'OK'
                        // });
                    }
                    return json.data;
                }
            },
            language: {
                emptyTable: 'No Data found for Report : Documents  ' + selectedText + ' (' + currentDate + ')',
            },
            initComplete: function(settings, json) {
                var api = this.api();
                var rowCount = api.rows().count(); // Get total number of rows
                var buttons = api.buttons();

                // Disable buttons if no records
                if (rowCount === 0) {
                    buttons.disable(); // Disable all buttons if no records
                } else {
                    buttons.enable(); // Enable buttons if records exist
                }
            },
            columns: columns,
            order: orders
        });
    }

    function onClickCommunications() {

        var result = getStartAndEndDate('Communication');
        // This arrangement can be altered based on how we want the date's format to appear.
        let currentDate = `${result.startDate} - ${result.endDate}`;

        deativeTabs();
        communicationTable1 = true;

        var communicationTable = $('#communicationTable1').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            "lengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "pageLength": 10,
            dom: '<"d-flex justify-content-between align-items-center"lBf>rtip', // Custom layout
            "buttons": [{
                    extend: 'csv',
                    title: 'Report :  Communications (' + currentDate + ')', // Custom title
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'excel',
                    title: 'Report :  Communications (' + currentDate + ')', // Custom title fo
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'pdf',
                    title: 'Report :  Communications (' + currentDate + ')',
                    orientation: 'landscape', // Makes table fit better
                    pageSize: 'A4',
                    customize: function(doc) {
                        let table = doc.content[1].table;
                        let columnCount = table.body[0].length;

                        // Dynamically set column widths to distribute space evenly
                        table.widths = [
                            '5%', // Column 1 (3% width)
                            '10%',
                            '*', // Column 3 (2% width)
                            '*', // Column 5 (2% width)
                            '*', // Column 7 (2% width)
                            '9%' // Column 8 (3% width)
                        ];

                        doc.defaultStyle.fontSize = 10; // Font size for table data
                        doc.styles.tableHeader.fontSize = 12; // Larger header font
                        doc.styles.title.fontSize = 18; // Title font
                        doc.pageMargins = [10, 10, 10, 10]; // Reduce margins
                        doc.styles.title.bold = true;

                        // Force full-page width usage
                        doc.content[1].layout = {
                            hLineWidth: function(i, node) {
                                return 0.5; // Line thickness
                            },
                            vLineWidth: function(i, node) {
                                return 0.5;
                            },
                            paddingLeft: function(i, node) {
                                return 4; // Adjust left padding
                            },
                            paddingRight: function(i, node) {
                                return 4;
                            }
                        };

                        // Center-align all table content
                        doc.content[1].table.body.forEach(function(row, rowIndex) {
                            row.forEach(function(cell, cellIndex) {
                                if (typeof cell === 'object') {
                                    cell.alignment = 'center'; // Center-align text in each cell
                                }
                            });
                        });

                        // Center-align the title
                        doc.content[0].alignment = 'center';
                    },
                    exportOptions: {
                        columns: ':visible',
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                }
            ],
            ajax: {
                url: "{{ route('manage_report_communications') }}",
                data: function(d) {
                    // Add additional data here
                    d.startDate = result.startDate;
                    d.endDate = result.endDate;

                },
                dataSrc: function(json) {
                    if (json.data.length === 0) {
                        // Swal.fire({
                        //     icon: 'warning',
                        //     title: 'No Data Available',
                        //     text: 'No Data found for Report : Communications  (' + currentDate + ')',
                        //     confirmButtonText: 'OK'
                        // });
                    }
                    return json.data;
                }
            },
            language: {
                emptyTable: 'No Data found for Report : Communications   (' + currentDate + ')',
            },
            initComplete: function(settings, json) {
                var api = this.api();
                var rowCount = api.rows().count(); // Get total number of rows
                var buttons = api.buttons();

                // Disable buttons if no records
                if (rowCount === 0) {
                    buttons.disable(); // Disable all buttons if no records
                } else {
                    buttons.enable(); // Enable buttons if records exist
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'communication_id',
                    name: 'communication_id'
                },
                {
                    data: 'sender_name',
                    name: 'sender_name'
                },
                {
                    data: 'receiver_name',
                    name: 'receiver_name'
                },
                {
                    data: 'message',
                    name: 'message'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                //{ data: 'action', name: 'action' },
            ],


        });

    }

    function onchangeCommunicationReport(type, text) {
        let selectedText = text;

        if (type === '') {
            // If empty, hide the table and the report title
            $('#reportCommunications').hide(); // Hide the table

            return; // Exit the function
        }
        $('#reportCommunications').show();
        // Ensure DataTable is properly destroyed and HTML table is cleared
        if ($.fn.DataTable.isDataTable('#reportCommunicationsTable')) {
            $('#reportCommunicationsTable').DataTable().clear().destroy();
            $('#reportCommunicationsTable').empty(); // Remove existing columns and rows
            $('#reportCommunicationsTable').empty();
        }
        var result = getStartAndEndDate('Communication');
        let currentDate = `${result.startDate} - ${result.endDate}`;
        $('#reportCommunications').show();
        let dataTableSettings = {
            processing: true,
            serverSide: true,
            searching: true, // Disable the search box
            // Disable the "Showing x to y of z entries" message
            "lengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "pageLength": 10,
            destroy: true,
            dom: '<"d-flex justify-content-between align-items-center"lBf>rtip', // Custom layout
            buttons: [{
                    extend: 'csv',
                    title: 'Report : Communications ' + selectedText + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''), // Custom title for CSV
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'excel',
                    title: 'Report : Communications ' + selectedText + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''), // Custom title for Excel
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'pdf',
                    title: 'Report : Communications ' + selectedText + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''), // Custom title for Excel
                    orientation: 'landscape', // Makes table fit better
                    pageSize: 'A4',
                    customize: function(doc) {
                        let table = doc.content[1].table;
                        let columnCount = table.body[0].length;

                        // Dynamically set column widths to distribute space evenly
                        table.widths = ['8%', ...Array(columnCount - 1).fill('*')];

                        // Adjust styles
                        doc.defaultStyle.fontSize = 10; // Font size for table data
                        doc.styles.tableHeader.fontSize = 12; // Larger header font
                        doc.styles.title.fontSize = 18; // Title font
                        doc.pageMargins = [10, 10, 10, 10]; // Reduce margins
                        doc.styles.title.bold = true;

                        // Force full-page width usage
                        doc.content[1].layout = {
                            hLineWidth: function(i, node) {
                                return 0.5; // Line thickness
                            },
                            vLineWidth: function(i, node) {
                                return 0.5;
                            },
                            paddingLeft: function(i, node) {
                                return 4; // Adjust left padding
                            },
                            paddingRight: function(i, node) {
                                return 4;
                            }
                        };

                        // Center-align all table content
                        doc.content[1].table.body.forEach(function(row, rowIndex) {
                            row.forEach(function(cell, cellIndex) {
                                if (typeof cell === 'object') {
                                    cell.alignment = 'center'; // Center-align text in each cell
                                }
                            });
                        });

                        // Center-align the title
                        doc.content[0].alignment = 'center';
                    },
                    exportOptions: {
                        columns: ':visible',
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                }
            ],
            ajax: {
                url: "{{ route('communicationReport') }}",
                data: function(d) {
                    d.type = type;
                    d.startDate = result.startDate;
                    d.endDate = result.endDate
                },
                dataSrc: function(json) {
                    if (json.data.length === 0) {
                        // Swal.fire({
                        //     icon: 'warning',
                        //     title: 'No Data Available',
                        //     text: 'No Data found for Report : Communications  ' + selectedText + ' (' + currentDate + ')',
                        //     confirmButtonText: 'OK'
                        // });
                    }
                    return json.data;
                }
            },
            language: {
                emptyTable: 'No Data found for Report : Communications  ' + selectedText + ' (' + currentDate + ')',
            },
            initComplete: function(settings, json) {
                var api = this.api();
                var rowCount = api.rows().count(); // Get total number of rows
                var buttons = api.buttons();

                // Disable buttons if no records
                if (rowCount === 0) {
                    buttons.disable(); // Disable all buttons if no records
                } else {
                    buttons.enable(); // Enable buttons if records exist
                }
            },

            order: [
                [1, 'desc']
            ]
        };
        // Configure DataTable based on report type
        if (type == 'byMessages') {
            $('#clientReportTitle12').html('Communications By Timeline (Duration)');
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Timeline (Duration)",
                    data: 'period',
                    name: 'period'
                },
                {
                    title: "No. of Messages",
                    data: 'total_activities',
                    name: 'total_activities'
                }
            ];
        } else if (type == 'byMeeting') {
            $('#clientReportTitle12').html('Communications By Meeting Notes By Timeline');
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Timeline (Duration)",
                    data: 'period',
                    name: 'period'
                },
                {
                    title: "No. of Meeting Notes",
                    data: 'total_activities',
                    name: 'total_activities'
                }
            ];
        } else if (type == 'meetingNotes') {
            $('#clientReportTitle12').html('Communications By Meeting Notes Type');
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "User Name (ID)",
                    data: 'username',
                    name: 'username'
                }, {
                    title: "Communication Mode",
                    data: 'communication_summary',
                    name: 'communication_summary'
                },
                {
                    title: "No. of Meeting Notes",
                    data: 'total_discussions',
                    name: 'total_discussions'
                }
            ];
        } else if (type == 'messagesSentByUser') {
            $('#clientReportTitle12').html('Communications By Messages Sent By Users');
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Sent By",
                    data: 'user_id',
                    name: 'user_id'
                },
                {
                    title: "No. of Messages",
                    data: 'number_of_communication',
                    name: 'number_of_communication'
                }
            ];
        } else if (type == 'byTimeLine') {
            reportTitle = 'Communication By No. Timeline';
            columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Duration",
                    data: 'type',
                    name: 'type',

                },
                {
                    title: "No of Users",
                    data: 'count',
                    name: 'count',

                }
            ];
        }  else if (type == 'yearly') {
            $('#clientReportTitle12').html('Communication By Year');
            dataTableSettings.columns = [{
                title: 'Sr.No',
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                width: '50px',
                orderable: false,
                searchable: false
                }, {
                    title: "Year",
                    data: 'year',
                    name: 'year'
                },
                {
                    title: "Count",
                    data: 'year_count',
                    name: 'year_count'
                }
            ];
        }

        $('#custom_date_picker5').prop('disabled', type == 'byTimeLine' || type == 'yearly');
        // Initialize the DataTable with the new settings
        $('#reportCommunicationsTable').DataTable(dataTableSettings);
    }

    function onClickInvoices() {

        // This arrangement can be altered based on how we want the date's format to appear.
        var result = getStartAndEndDate('Invoice');
        let currentDate = `${result.startDate} - ${result.endDate}`;

        deativeTabs();
        invoiceTable1 = true;

        var invoiceTable = $('#invoicesTable1').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            "lengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "pageLength": 10,
            dom: '<"d-flex justify-content-between align-items-center"lBf>rtip', // Custom layout
            "buttons": [{
                    extend: 'csv',
                    title: 'Report : Invoices  (' + currentDate + ')', // Custom title for CS
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'excel',
                    title: 'Report : Invoices  (' + currentDate + ')', // Custom title for Exce
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'pdf',
                    title: 'Report : Invoices  (' + currentDate + ')', // Custom title for Exce
                    orientation: 'landscape', // Makes table fit better
                    pageSize: 'A4',
                    customize: function(doc) {
                        let table = doc.content[1].table;
                        let columnCount = table.body[0].length;

                        table.widths = [
                            '5%',
                            '*',
                            '10%',
                            '*', // Column 1 (3% width)
                            '7%',
                            '7%', // Column 3 (2% width)
                            '7%', // Column 5 (2% width)
                            '7%', // Column 7 (2% width)
                            '8%',
                            '9%', // Column 7 (2% width)
                            '10%' // Column 8 (3% width)
                        ];

                        doc.defaultStyle.fontSize = 10; // Font size for table data
                        doc.styles.tableHeader.fontSize = 12; // Larger header font
                        doc.styles.title.fontSize = 18; // Title font
                        doc.pageMargins = [10, 10, 10, 10]; // Reduce margins
                        doc.styles.title.bold = true;

                        // Force full-page width usage
                        doc.content[1].layout = {
                            hLineWidth: function(i, node) {
                                return 0.5; // Line thickness
                            },
                            vLineWidth: function(i, node) {
                                return 0.5;
                            },
                            paddingLeft: function(i, node) {
                                return 4; // Adjust left padding
                            },
                            paddingRight: function(i, node) {
                                return 4;
                            }
                        };

                        // Center-align all table content
                        doc.content[1].table.body.forEach(function(row, rowIndex) {
                            row.forEach(function(cell, cellIndex) {
                                if (typeof cell === 'object') {
                                    cell.alignment = 'center'; // Center-align text in each cell
                                }
                            });
                        });

                        // Center-align the title
                        doc.content[0].alignment = 'center';
                    },
                    exportOptions: {
                        columns: ':visible',
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                }
            ],
            ajax: {
                url: "{{ route('manage_reports_invoices') }}",
                data: function(d) {
                    // Add additional data here
                    d.startDate = result.startDate;
                    d.endDate = result.endDate;

                },
                dataSrc: function(json) {
                    if (json.data.length === 0) {
                        // Swal.fire({
                        //     icon: 'warning',
                        //     title: 'No Data Available',
                        //     text: 'No Data found for Report :Invoices  (' + currentDate + ')',
                        //     confirmButtonText: 'OK'
                        // });
                    }
                    return json.data;
                }
            },
            language: {
                emptyTable: 'No Data found for Report : Invoices  (' + currentDate + ')',
            },
            initComplete: function(settings, json) {
                var api = this.api();
                var rowCount = api.rows().count(); // Get total number of rows
                var buttons = api.buttons();

                // Disable buttons if no records
                if (rowCount === 0) {
                    buttons.disable(); // Disable all buttons if no records
                } else {
                    buttons.enable(); // Enable buttons if records exist
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'to_name',
                    name: 'to_name'
                },
                {
                    data: 'to_phone',
                    name: 'to_phone'
                },
                {
                    data: 'to_email',
                    name: 'to_email'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'discount',
                    name: 'discount'
                },
                {
                    data: 'tax',
                    name: 'tax'
                },
                {
                    data: 'total',
                    name: 'total'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'due_date',
                    name: 'due_date'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            order: [1, 'asc']

        });
    }

    function onClickInvoicesAP() {

    // This arrangement can be altered based on how we want the date's format to appear.
    var result = getStartAndEndDate('Invoice');
    let currentDate = `${result.startDate} - ${result.endDate}`;

    deativeTabs();
    invoiceAPTable1 = true;

    var invoiceTable = $('#invoicesAPTable1').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        "lengthMenu": [
            [10, 20, 50, -1],
            [10, 20, 50, "All"]
        ],
        "pageLength": 10,
        dom: '<"d-flex justify-content-between align-items-center"lBf>rtip', // Custom layout
        "buttons": [{
                extend: 'csv',
                title: 'Report : Invoices  (' + currentDate + ')', // Custom title for CS
                exportOptions: {
                    modifier: {
                        page: 'all' // Export all data, not just the current page
                    }
                }
            },
            {
                extend: 'excel',
                title: 'Report : Invoices  (' + currentDate + ')', // Custom title for Exce
                exportOptions: {
                    modifier: {
                        page: 'all' // Export all data, not just the current page
                    }
                }
            },
            {
                extend: 'pdf',
                title: 'Report : Invoices  (' + currentDate + ')', // Custom title for Exce
                orientation: 'landscape', // Makes table fit better
                pageSize: 'A4',
                customize: function(doc) {
                    let table = doc.content[1].table;
                    let columnCount = table.body[0].length;

                    table.widths = [
                        '5%',
                        '*',
                        '10%',
                        '*', // Column 1 (3% width)
                        '7%',
                        '7%', // Column 3 (2% width)
                        '7%', // Column 5 (2% width)
                        '7%', // Column 7 (2% width)
                        '8%',
                        '9%', // Column 7 (2% width)
                        '10%' // Column 8 (3% width)
                    ];

                    doc.defaultStyle.fontSize = 10; // Font size for table data
                    doc.styles.tableHeader.fontSize = 12; // Larger header font
                    doc.styles.title.fontSize = 18; // Title font
                    doc.pageMargins = [10, 10, 10, 10]; // Reduce margins
                    doc.styles.title.bold = true;

                    // Force full-page width usage
                    doc.content[1].layout = {
                        hLineWidth: function(i, node) {
                            return 0.5; // Line thickness
                        },
                        vLineWidth: function(i, node) {
                            return 0.5;
                        },
                        paddingLeft: function(i, node) {
                            return 4; // Adjust left padding
                        },
                        paddingRight: function(i, node) {
                            return 4;
                        }
                    };

                    // Center-align all table content
                    doc.content[1].table.body.forEach(function(row, rowIndex) {
                        row.forEach(function(cell, cellIndex) {
                            if (typeof cell === 'object') {
                                cell.alignment = 'center'; // Center-align text in each cell
                            }
                        });
                    });

                    // Center-align the title
                    doc.content[0].alignment = 'center';
                },
                exportOptions: {
                    columns: ':visible',
                    modifier: {
                        page: 'all' // Export all data, not just the current page
                    }
                }
            }
        ],
        ajax: {
            url: "{{ route('manage_reports_invoices_ap') }}",
            data: function(d) {
                // Add additional data here
                d.startDate = result.startDate;
                d.endDate = result.endDate;

            },
            dataSrc: function(json) {
                if (json.data.length === 0) {
                    // Swal.fire({
                    //     icon: 'warning',
                    //     title: 'No Data Available',
                    //     text: 'No Data found for Report :Invoices  (' + currentDate + ')',
                    //     confirmButtonText: 'OK'
                    // });
                }
                return json.data;
            }
        },
        language: {
            emptyTable: 'No Data found for Report : Invoices  (' + currentDate + ')',
        },
        initComplete: function(settings, json) {
            var api = this.api();
            var rowCount = api.rows().count(); // Get total number of rows
            var buttons = api.buttons();

            // Disable buttons if no records
            if (rowCount === 0) {
                buttons.disable(); // Disable all buttons if no records
            } else {
                buttons.enable(); // Enable buttons if records exist
            }
        },
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {
                data: 'to_name',
                name: 'to_name'
            },
            {
                data: 'to_phone',
                name: 'to_phone'
            },
            {
                data: 'to_email',
                name: 'to_email'
            },
            {
                data: 'amount',
                name: 'amount'
            },
            {
                data: 'discount',
                name: 'discount'
            },
            {
                data: 'tax',
                name: 'tax'
            },
            {
                data: 'total',
                name: 'total'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'due_date',
                name: 'due_date'
            },
            {
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ],
        order: [1, 'asc']

    });
    }

    function onchangeInvoicesReport(type, text) {
        let selectedText = text;
        let columns = [];

        if (type === '') {
            // If empty, hide the table and the report title
            $('#reportInvoices').hide(); // Hide the table

            return; // Exit the function
        }
        $('#reportInvoices').show();
        // Ensure DataTable is properly destroyed and HTML table is cleared
        if ($.fn.DataTable.isDataTable('#reportInvoiceTable')) {
            $('#reportInvoiceTable').DataTable().clear().destroy();
            $('#reportInvoiceTable').empty(); // Remove existing columns and rows
        }
        var result = getStartAndEndDate('Invoice');
        let currentDate = `${result.startDate} - ${result.endDate}`;
        $('#reportInvoices').show();
        let dataTableSettings = {
            processing: true,
            serverSide: true,
            searching: true, // Disable the search box
            // Disable the "Showing x to y of z entries" message
            "lengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "pageLength": 10,
            destroy: true,
            dom: '<"d-flex justify-content-between align-items-center"lBf>rtip', // Custom layout
            buttons: [{
                    extend: 'csv',
                    title: 'Report : Invoices  ' + selectedText + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''), // Custom title for CSV
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'excel',
                    title: 'Report : Invoices  ' + selectedText + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''), // Custom title for Excel
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'pdf',
                    title: 'Report : Invoices  ' + selectedText + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''), // Custom title for Excel
                    orientation: 'landscape', // Makes table fit better
                    pageSize: 'A4',
                    customize: function(doc) {
                        let table = doc.content[1].table;
                        let columnCount = table.body[0].length;

                        // Dynamically set column widths to distribute space evenly
                        // Dynamically set column widths to distribute space evenly
                        table.widths = ['8%', ...Array(columnCount - 1).fill('*')];

                        // Adjust styles
                        doc.defaultStyle.fontSize = 10; // Font size for table data
                        doc.styles.tableHeader.fontSize = 12; // Larger header font
                        doc.styles.title.fontSize = 18; // Title font
                        doc.pageMargins = [10, 10, 10, 10]; // Reduce margins
                        doc.styles.title.bold = true;
                        // Force full-page width usage
                        doc.content[1].layout = {
                            hLineWidth: function(i, node) {
                                return 0.5; // Line thickness
                            },
                            vLineWidth: function(i, node) {
                                return 0.5;
                            },
                            paddingLeft: function(i, node) {
                                return 4; // Adjust left padding
                            },
                            paddingRight: function(i, node) {
                                return 4;
                            }
                        };

                        // Center-align all table content
                        doc.content[1].table.body.forEach(function(row, rowIndex) {
                            row.forEach(function(cell, cellIndex) {
                                if (typeof cell === 'object') {
                                    cell.alignment = 'center'; // Center-align text in each cell
                                }
                            });
                        });

                        // Center-align the title
                        doc.content[0].alignment = 'center';
                    },
                    exportOptions: {
                        columns: ':visible',
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                }
            ],
            ajax: {
                url: "{{ route('invoicesReport') }}",
                data: function(d) {
                    d.type = type;
                    d.startDate = result.startDate;
                    d.endDate = result.endDate
                },
                dataSrc: function(json) {
                    if (json.data.length === 0) {
                        // Swal.fire({
                        //     icon: 'warning',
                        //     title: 'No Data Available',
                        //     text: 'No Data found for Report : Invoices  ' + selectedText + ' (' + currentDate + ')',
                        //     confirmButtonText: 'OK'
                        // });
                    }
                    return json.data;
                }
            },
            language: {
                emptyTable: 'No Data found for Report : Invoices  ' + selectedText + ' (' + currentDate + ')',
            },
            initComplete: function(settings, json) {
                var api = this.api();
                var rowCount = api.rows().count(); // Get total number of rows
                var buttons = api.buttons();

                // Disable buttons if no records
                if (rowCount === 0) {
                    buttons.disable(); // Disable all buttons if no records
                } else {
                    buttons.enable(); // Enable buttons if records exist
                }
            },



        };
        // Configure DataTable based on report type
        if (type == 'byAmount') {
            $('#clientReportTitle2').html('Invoices By Amount');
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Amount Range",
                    data: 'amount_range',
                    name: 'amount_range'
                },
                {
                    title: "No. of Invoices",
                    data: 'number_of_invoices',
                    name: 'number_of_invoices'
                }
            ];
            dataTableSettings.order = [
                [1, 'asc']
            ]
        } else if (type == 'byType') {
            $('#clientReportTitle2').html('Invoices By Type');
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Invoice Type",
                    data: 'status',
                    name: 'status'
                },
                {
                    title: "No. of Invoices",
                    data: 'number_of_invoices',
                    name: 'number_of_invoices'
                }
            ];
        } else if (type == 'byClient') {
            $('#clientReportTitle2').html(`Invoices By Client's Home Country`);
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Client Country",
                    data: 'country',
                    name: 'country'
                },
                {
                    title: "No. of Invoices",
                    data: 'number_of_invoices',
                    name: 'number_of_invoices'
                }
            ];
        } else if (type == 'byVisaCountry') {
            $('#clientReportTitle2').html("Invoices By Visa Country's Count");
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Visa Country",
                    data: 'to_country',
                    name: 'to_country'
                },
                {
                    title: "No. of Invoices",
                    data: 'number_of_invoices',
                    name: 'number_of_invoices'
                }
            ];
        } else if (type == 'byTimeLine') {
            $('#clientReportTitle2').html(`Invoices By Timeline (Duration)`);
            // reportTitle = 'Invoices By Timeline';
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Timeline (Duration)",
                    data: 'year',
                    name: 'year',

                },
                {
                    title: "No of Invoices",
                    data: 'count',
                    name: 'count',

                }
            ];
            dataTableSettings.order = [
                // []
            ]
        }  else if (type == 'yearly') {
            reportTitle = 'Communication By Year';
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Year",
                    data: 'year',
                    name: 'year'
                },
                {
                    title: "Count",
                    data: 'year_count',
                    name: 'year_count'
                }
            ];
        }

        $('#custom_date_picker6').prop('disabled', type == 'byTimeLine' || type == 'yearly');
        // Initialize the DataTable with the new settings
        $('#reportInvoiceTable').DataTable(dataTableSettings);
    }

    function onchangeInvoicesAPReport(type, text) {
        let selectedText = text;
        let columns = [];

        if (type === '') {
            // If empty, hide the table and the report title
            $('#reportInvoicesAP').hide(); // Hide the table

            return; // Exit the function
        }
        $('#reportInvoicesAP').show();
        // Ensure DataTable is properly destroyed and HTML table is cleared
        if ($.fn.DataTable.isDataTable('#reportInvoiceAPTable')) {
            $('#reportInvoiceAPTable').DataTable().clear().destroy();
            $('#reportInvoiceAPTable').empty(); // Remove existing columns and rows
        }
        var result = getStartAndEndDate('Invoice');
        let currentDate = `${result.startDate} - ${result.endDate}`;
        $('#reportInvoicesAP').show();
        let dataTableSettings = {
            processing: true,
            serverSide: true,
            searching: true, // Disable the search box
            // Disable the "Showing x to y of z entries" message
            "lengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "pageLength": 10,
            destroy: true,
            dom: '<"d-flex justify-content-between align-items-center"lBf>rtip', // Custom layout
            buttons: [{
                    extend: 'csv',
                    title: 'Report : Invoices  ' + selectedText + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''), // Custom title for CSV
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'excel',
                    title: 'Report : Invoices  ' + selectedText + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''), // Custom title for Excel
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'pdf',
                    title: 'Report : Invoices  ' + selectedText + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''), // Custom title for Excel
                    orientation: 'landscape', // Makes table fit better
                    pageSize: 'A4',
                    customize: function(doc) {
                        let table = doc.content[1].table;
                        let columnCount = table.body[0].length;

                        // Dynamically set column widths to distribute space evenly
                        // Dynamically set column widths to distribute space evenly
                        table.widths = ['8%', ...Array(columnCount - 1).fill('*')];

                        // Adjust styles
                        doc.defaultStyle.fontSize = 10; // Font size for table data
                        doc.styles.tableHeader.fontSize = 12; // Larger header font
                        doc.styles.title.fontSize = 18; // Title font
                        doc.pageMargins = [10, 10, 10, 10]; // Reduce margins
                        doc.styles.title.bold = true;
                        // Force full-page width usage
                        doc.content[1].layout = {
                            hLineWidth: function(i, node) {
                                return 0.5; // Line thickness
                            },
                            vLineWidth: function(i, node) {
                                return 0.5;
                            },
                            paddingLeft: function(i, node) {
                                return 4; // Adjust left padding
                            },
                            paddingRight: function(i, node) {
                                return 4;
                            }
                        };

                        // Center-align all table content
                        doc.content[1].table.body.forEach(function(row, rowIndex) {
                            row.forEach(function(cell, cellIndex) {
                                if (typeof cell === 'object') {
                                    cell.alignment = 'center'; // Center-align text in each cell
                                }
                            });
                        });

                        // Center-align the title
                        doc.content[0].alignment = 'center';
                    },
                    exportOptions: {
                        columns: ':visible',
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                }
            ],
            ajax: {
                url: "{{ route('invoicesReport_ap') }}",
                data: function(d) {
                    d.type = type;
                    d.startDate = result.startDate;
                    d.endDate = result.endDate
                },
                dataSrc: function(json) {
                    if (json.data.length === 0) {
                        // Swal.fire({
                        //     icon: 'warning',
                        //     title: 'No Data Available',
                        //     text: 'No Data found for Report : Invoices  ' + selectedText + ' (' + currentDate + ')',
                        //     confirmButtonText: 'OK'
                        // });
                    }
                    return json.data;
                }
            },
            language: {
                emptyTable: 'No Data found for Report : Invoices  ' + selectedText + ' (' + currentDate + ')',
            },
            initComplete: function(settings, json) {
                var api = this.api();
                var rowCount = api.rows().count(); // Get total number of rows
                var buttons = api.buttons();

                // Disable buttons if no records
                if (rowCount === 0) {
                    buttons.disable(); // Disable all buttons if no records
                } else {
                    buttons.enable(); // Enable buttons if records exist
                }
            },



        };
        // Configure DataTable based on report type
        if (type == 'byAmount') {
            $('#clientReportTitle2').html('Invoices By Amount');
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Amount Range",
                    data: 'amount_range',
                    name: 'amount_range'
                },
                {
                    title: "No. of Invoices",
                    data: 'number_of_invoices',
                    name: 'number_of_invoices'
                }
            ];
            dataTableSettings.order = [
                [1, 'asc']
            ]
        } else if (type == 'byType') {
            $('#clientReportTitle2').html('Invoices By Type');
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Invoice Type",
                    data: 'status',
                    name: 'status'
                },
                {
                    title: "No. of Invoices",
                    data: 'number_of_invoices',
                    name: 'number_of_invoices'
                }
            ];
        } else if (type == 'byClient') {
            $('#clientReportTitle2').html(`Invoices By Client's Home Country`);
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Client Country",
                    data: 'country',
                    name: 'country'
                },
                {
                    title: "No. of Invoices",
                    data: 'number_of_invoices',
                    name: 'number_of_invoices'
                }
            ];
        } else if (type == 'byVisaCountry') {
            $('#clientReportTitle2').html("Invoices By Visa Country's Count");
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Visa Country",
                    data: 'to_country',
                    name: 'to_country'
                },
                {
                    title: "No. of Invoices",
                    data: 'number_of_invoices',
                    name: 'number_of_invoices'
                }
            ];
        } else if (type == 'byTimeLine') {
            $('#clientReportTitle2').html(`Invoices By Timeline (Duration)`);
            // reportTitle = 'Invoices By Timeline';
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Timeline (Duration)",
                    data: 'year',
                    name: 'year',

                },
                {
                    title: "No of Invoices",
                    data: 'count',
                    name: 'count',

                }
            ];
            dataTableSettings.order = [
                // []
            ]
        }  else if (type == 'yearly') {
            reportTitle = 'Communication By Year';
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Year",
                    data: 'year',
                    name: 'year'
                },
                {
                    title: "Count",
                    data: 'year_count',
                    name: 'year_count'
                }
            ];
        }

        $('#custom_date_picker66').prop('disabled', type == 'byTimeLine' || type == 'yearly');
        // Initialize the DataTable with the new settings
        $('#reportInvoiceAPTable').DataTable(dataTableSettings);
    }

    function onClickPayments() {


        var result = getStartAndEndDate('PaymentAR');
        // This arrangement can be altered based on how we want the date's format to appear.
        let currentDate = `${result.startDate} - ${result.endDate}`;

        deativeTabs();
        paymentsTable1 = true;

        var paymentsTable = $('#paymentsTable1').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            "lengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "pageLength": 10,
            order: [
                [0, 'desc']
            ],
            dom: '<"d-flex justify-content-between align-items-center"lBf>rtip', // Custom layout
            "buttons": [{
                    extend: 'csv',
                    title: 'Report : Payments (AR)  (' + currentDate + ')', // Custom title for CS
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'excel',
                    title: 'Report : Payments (AR)  (' + currentDate + ')', // Custom title for Exce
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'pdf',
                    title: 'Report : Payments (AR)  (' + currentDate + ')', // Custom title for Exce
                    orientation: 'landscape', // Makes table fit better
                    pageSize: 'A4',
                    customize: function(doc) {
                        let table = doc.content[1].table;
                        let columnCount = table.body[0].length;

                        // Dynamically set column widths to distribute space evenly
                        table.widths = [
                            '5%',
                            '*',
                            '*',
                            '5%', // Column 1 (3% width)
                            '10%',
                            '10%', // Column 3 (2% width)
                            '7%', // Column 5 (2% width)
                            '7%', // Column 7 (2% width)
                            // '7%',
                            // '7%', // Column 7 (2% width)
                            // '7%',
                            // '8%',
                            '8%' // Column 8 (3% width)
                        ];

                        doc.defaultStyle.fontSize = 10; // Font size for table data
                        doc.styles.tableHeader.fontSize = 12; // Larger header font
                        doc.styles.title.fontSize = 18; // Title font
                        doc.pageMargins = [10, 10, 10, 10]; // Reduce margins
                        doc.styles.title.bold = true;

                        // Force full-page width usage
                        doc.content[1].layout = {
                            hLineWidth: function(i, node) {
                                return 0.5; // Line thickness
                            },
                            vLineWidth: function(i, node) {
                                return 0.5;
                            },
                            paddingLeft: function(i, node) {
                                return 4; // Adjust left padding
                            },
                            paddingRight: function(i, node) {
                                return 4;
                            }
                        };

                        // Center-align all table content
                        doc.content[1].table.body.forEach(function(row, rowIndex) {
                            row.forEach(function(cell, cellIndex) {
                                if (typeof cell === 'object') {
                                    cell.alignment = 'center'; // Center-align text in each cell
                                }
                            });
                        });

                        // Center-align the title
                        doc.content[0].alignment = 'center';
                    },
                    exportOptions: {
                        columns: ':visible',
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                }
            ],
            ajax: {
                url: "{{ route('manage_report_payments') }}",
                data: function(d) {
                    // Add additional data here
                    d.startDate = result.startDate;
                    d.endDate = result.endDate;
                    d.type = 'ar'

                },
                dataSrc: function(json) {
                    if (json.data.length === 0) {
                        // Swal.fire({
                        //     icon: 'warning',
                        //     title: 'No Data Available',
                        //     text: 'No Data found for Report : Payments  (' + currentDate + ')',
                        //     confirmButtonText: 'OK'
                        // });
                    }
                    return json.data;
                }
            },
            language: {
                emptyTable: 'No Data found for Report : Payments (AR) (' + currentDate + ')',
            },
            initComplete: function(settings, json) {
                var api = this.api();
                var rowCount = api.rows().count(); // Get total number of rows
                var buttons = api.buttons();

                // Disable buttons if no records
                if (rowCount === 0) {
                    buttons.disable(); // Disable all buttons if no records
                } else {
                    buttons.enable(); // Enable buttons if records exist
                }
            },
            columns: [
                // { data: 'DT_RowIndex', name: 'DT_RowIndex', title: '#', orderable: false, searchable: false },
                {
                    data: 'DT_RowIndex', // Index column
                    name: 'DT_RowIndex',
                    searchable: false,
                    orderable: false,

                },
                {
                    data: 'client',
                    name: 'client'
                },
                {
                    data: 'service_description',
                    name: 'service_description'
                },
                // {
                //     data: 'type',
                //     name: 'type'
                // },
                // {
                //     data: 'service_provider',
                //     name: 'service_provider'
                // },
                // {
                //     data: 'service_taken',
                //     name: 'service_taken'
                // },
                {
                    data: 'payment_mode',
                    name: 'payment_mode'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'paid_amount',
                    name: 'paid_amount'
                },
                {
                    data: 'amount_to_pay',
                    name: 'amount_to_pay'
                },
                {
                    data: 'payment_date',
                    name: 'payment_date'
                },
                // {
                //     data: 'invoice_no',
                //     name: 'invoice_no'
                // },
                {
                    data: 'created_at',
                    name: 'created_at'
                }

                //{ data: 'action', name: 'action' },

            ],


        });
    }

    function onchangePaymentsReport(type, text) {
        let selectedText = text;

        if (type === '') {
            // If empty, hide the table and the report title
            $('#reportPayments').hide(); // Hide the table

            return; // Exit the function
        }
        // Ensure DataTable is properly destroyed and HTML table is cleared
        if ($.fn.DataTable.isDataTable('#reportPaymentTable')) {
            $('#reportPaymentTable').DataTable().clear().destroy();
            $('#reportPaymentTable').empty(); // Remove existing columns and rows
        }
        var result = getStartAndEndDate('PaymentAR');
        // This arrangement can be altered based on how we want the date's format to appear.
        let currentDate = `${result.startDate} - ${result.endDate}`;
        let columns = [];
        $('#reportPayments').show();
        let dataTableSettings = {
            processing: true,
            serverSide: true,
            searching: true, // Disable the search box
            // Disable the "Showing x to y of z entries" message
            "lengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "pageLength": 10,
            destroy: true,
            dom: '<"d-flex justify-content-between align-items-center"lBf>rtip', // Custom layout
            buttons: [{
                    extend: 'csv',
                    title: 'Report : Payments (AR) ' + selectedText + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''), // Custom title for CSV
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'excel',
                    title: 'Report : Payments (AR) ' + selectedText + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''), // Custom title for Excel
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'pdf',
                    title: 'Report : Payments (AR) ' + selectedText + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''), // Custom title for Excel
                    orientation: 'landscape', // Makes table fit better
                    pageSize: 'A4',
                    customize: function(doc) {
                        let table = doc.content[1].table;
                        let columnCount = table.body[0].length;

                        // Dynamically set column widths to distribute space evenly
                        table.widths = ['8%', ...Array(columnCount - 1).fill('*')];

                        // Adjust styles
                        doc.defaultStyle.fontSize = 10; // Font size for table data
                        doc.styles.tableHeader.fontSize = 12; // Larger header font
                        doc.styles.title.fontSize = 18; // Title font
                        doc.pageMargins = [10, 10, 10, 10]; // Reduce margins
                        doc.styles.title.bold = true;

                        // Force full-page width usage
                        doc.content[1].layout = {
                            hLineWidth: function(i, node) {
                                return 0.5; // Line thickness
                            },
                            vLineWidth: function(i, node) {
                                return 0.5;
                            },
                            paddingLeft: function(i, node) {
                                return 4; // Adjust left padding
                            },
                            paddingRight: function(i, node) {
                                return 4;
                            }
                        };

                        // Center-align all table content
                        doc.content[1].table.body.forEach(function(row, rowIndex) {
                            row.forEach(function(cell, cellIndex) {
                                if (typeof cell === 'object') {
                                    cell.alignment = 'center'; // Center-align text in each cell
                                }
                            });
                        });

                        // Center-align the title
                        doc.content[0].alignment = 'center';
                    },
                    exportOptions: {
                        columns: ':visible',
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                }
            ],
            ajax: {
                url: "{{ route('paymentReport') }}",
                data: function(d) {
                    d.type = type;
                    d.startDate = result.startDate;
                    d.endDate = result.endDate,
                        d.payment_type = 'ar'
                },
                dataSrc: function(json) {
                    if (json.data.length === 0) {
                        // Swal.fire({
                        //     icon: 'warning',
                        //     title: 'No Data Available',
                        //     text: 'No Data found for Report : Payments  ' + selectedText + ' (' + currentDate + ')',
                        //     confirmButtonText: 'OK'
                        // });
                    }
                    return json.data;
                }
            },
            language: {
                emptyTable: 'No Data found for Report : Payments (AR)  ' + selectedText + ' (' + currentDate + ')',
            },
            initComplete: function(settings, json) {
                var api = this.api();
                var rowCount = api.rows().count(); // Get total number of rows
                var buttons = api.buttons();

                // Disable buttons if no records
                if (rowCount === 0) {
                    buttons.disable(); // Disable all buttons if no records
                } else {
                    buttons.enable(); // Enable buttons if records exist
                }
            },
            columns: columns,
            order: [
                [1, 'desc']
            ]
        };
        // Configure DataTable based on report type
        if (type == 'byPaymentMode') {
            $('#clientReportTitle5').html('Payments (AR) By Mode');
            dataTableSettings.columns = [{
                    title: "Sr.No",
                    data: 'DT_RowIndex', // Index column
                    name: 'DT_RowIndex',
                    searchable: false,
                    orderable: false,

                }, {
                    title: "Payment Mode",
                    data: 'payment_mode',
                    name: 'payment_mode'
                },
                {
                    title: "No. of Payments",
                    data: 'number_of_payment',
                    name: 'number_of_payment'
                }
            ];
        } else if (type == 'byPaymentAmount') {
            $('#clientReportTitle5').html('Payments (AR) By Payment Amount');
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                },
                {
                    title: "Amount (Range)",
                    data: 'amount_range',
                    name: 'amount_range',

                },
                {
                    title: "No. of Payments",
                    data: "number_of_invoices",
                    name: "number_of_invoices",

                }

            ];
            dataTableSettings.order = [
                [1, 'asc']
            ]
        } else if (type == 'byOutstandingAmount') {
            $('#clientReportTitle5').html('Payments (AR) By Outstanding Amount');
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Client Name (ID)",
                    data: 'client_name',
                    name: 'client_name'
                },
                {
                    title: "Name of Application/Service",
                    data: 'application_name',
                    name: 'application_name',

                },
                {
                    title: "Amount To Pay",
                    data: "amount",
                    name: "amount",

                },
                {
                    title: "Paid Amount",
                    data: "paid_amount",
                    name: "paid_amount",

                },
                {
                    title: "Outstanding Amount",
                    data: "amount_to_pay",
                    name: "amount_to_pay",

                },

                {
                    title: "Payment Due Date",
                    data: "due_date",
                    name: "due_date",

                }
            ];
            dataTableSettings.order = [
                [1, 'asc']
            ]
        } else if (type == 'byInvoiceType') {
            $('#clientReportTitle5').html('Payments (AR) By Payment Type');
            dataTableSettings.columns = [{
                    title: "Sr.No",
                    data: 'DT_RowIndex', // Index column
                    name: 'DT_RowIndex',
                    searchable: false,
                    orderable: false,

                }, {
                    title: "Payment Type",
                    data: 'type',
                    name: 'type'
                },
                {
                    title: "No. of Payments",
                    data: 'number_of_invoices',
                    name: 'number_of_invoices'
                }
            ];
        } else if (type == 'byClientCountry') {
            $('#clientReportTitle5').html("Payments (AR) By Client's Home Country");
            dataTableSettings.columns = [{
                    title: "Sr.No",
                    data: 'DT_RowIndex', // Index column
                    name: 'DT_RowIndex',
                    searchable: false,
                    orderable: false,

                }, {
                    title: "Home Country",
                    data: 'country',
                    name: 'country'
                },
                {
                    title: "No. of Payments",
                    data: 'number_of_payment',
                    name: 'number_of_payment'
                }
            ];
        } else if (type == 'byVisaCountry') {
            $('#clientReportTitle5').html("Payments (AR) By Visa Country");
            dataTableSettings.columns = [{
                    title: "Sr.No",
                    data: 'DT_RowIndex', // Index column
                    name: 'DT_RowIndex',
                    searchable: false,
                    orderable: false,

                }, {
                    title: "Visa Country",
                    data: 'to_country',
                    name: 'to_country'
                },
                {
                    title: "No. of Payments",
                    data: 'number_of_payment',
                    name: 'number_of_payment'
                }
            ];
        } else if (type == 'byApplicationType') {
            $('#clientReportTitle5').html('Payments (AR) By Application Type');
            dataTableSettings.columns = [{
                    title: "Sr.No",
                    data: 'DT_RowIndex', // Index column
                    name: 'DT_RowIndex',
                    searchable: false,
                    orderable: false,

                }, {
                    title: "Application Type",
                    data: 'application_name',
                    name: 'application_name'
                },
                {
                    title: "No. of Payments",
                    data: 'number_of_payment',
                    name: 'number_of_payment'
                }
            ];
        } else if (type == 'byTimeLine') {
            $('#clientReportTitle5').html('Payments (AR) By Timeline');
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Timeline (Duration)",
                    data: 'year',
                    name: 'year',

                },
                {
                    title: "No of Payments",
                    data: 'count',
                    name: 'count',

                }
            ];
            dataTableSettings.order = [
                // []
            ]
        } else if (type == 'byYear') {
            $('#clientReportTitle5').html('Payments (AR) By Year');
            dataTableSettings.columns = [{
                    title: "Sr.No",
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Year",
                    data: 'year',
                    name: 'year'
                },
                {
                    title: "No of Payments",
                    data: 'count',
                    name: 'count'
                }
            ];
            dataTableSettings.order = [
                // []
            ]
        }


        $('#custom_date_picker7').prop('disabled', type == 'byTimeLine' || type == 'byYear');
        // Initialize the DataTable with the new settings
        $('#reportPaymentTable').DataTable(dataTableSettings);
    }

    function onClickPaymentsAP() {

        var result = getStartAndEndDate('PaymentAP');
        // This arrangement can be altered based on how we want the date's format to appear.
        let currentDate = `${result.startDate} - ${result.endDate}`;

        deativeTabs();
        paymentsAPTable1 = true;

        var paymentAPTable1 = $('#paymentsAPTable1').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            "lengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "pageLength": 10,
            order: [
                [0, 'desc']
            ],
            dom: '<"d-flex justify-content-between align-items-center"lBf>rtip', // Custom layout
            "buttons": [{
                    extend: 'csv',
                    title: 'Report : Payments (AP)   (' + currentDate + ')', // Custom title for CS
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'excel',
                    title: 'Report : Payments (AP)   (' + currentDate + ')', // Custom title for Exce
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'pdf',
                    title: 'Report : Payments (AP)   (' + currentDate + ')', // Custom title for Exce
                    orientation: 'landscape', // Makes table fit better
                    pageSize: 'A4',
                    customize: function(doc) {
                        let table = doc.content[1].table;
                        let columnCount = table.body[0].length;

                        // Dynamically set column widths to distribute space evenly
                        table.widths = [
                            '5%',
                            '*',
                            '*',
                            '5%', // Column 1 (3% width)
                            '10%',
                            '10%', // Column 3 (2% width)
                            '7%', // Column 5 (2% width)
                            '7%', // Column 7 (2% width)
                            // '7%',
                            // '7%', // Column 7 (2% width)
                            // '7%',
                            // '8%',
                            '8%' // Column 8 (3% width)
                        ];

                        doc.defaultStyle.fontSize = 10; // Font size for table data
                        doc.styles.tableHeader.fontSize = 12; // Larger header font
                        doc.styles.title.fontSize = 18; // Title font
                        doc.pageMargins = [10, 10, 10, 10]; // Reduce margins
                        doc.styles.title.bold = true;

                        // Force full-page width usage
                        doc.content[1].layout = {
                            hLineWidth: function(i, node) {
                                return 0.5; // Line thickness
                            },
                            vLineWidth: function(i, node) {
                                return 0.5;
                            },
                            paddingLeft: function(i, node) {
                                return 4; // Adjust left padding
                            },
                            paddingRight: function(i, node) {
                                return 4;
                            }
                        };

                        // Center-align all table content
                        doc.content[1].table.body.forEach(function(row, rowIndex) {
                            row.forEach(function(cell, cellIndex) {
                                if (typeof cell === 'object') {
                                    cell.alignment = 'center'; // Center-align text in each cell
                                }
                            });
                        });

                        // Center-align the title
                        doc.content[0].alignment = 'center';
                    },
                    exportOptions: {
                        columns: ':visible',
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                }
            ],
            ajax: {
                url: "{{ route('manage_report_payments') }}",
                data: function(d) {
                    // Add additional data here
                    d.startDate = result.startDate;
                    d.endDate = result.endDate;
                    d.type = 'ap'

                },
                dataSrc: function(json) {
                    if (json.data.length === 0) {
                        // Swal.fire({
                        //     icon: 'warning',
                        //     title: 'No Data Available',
                        //     text: 'No Data found for Report : Payments  (' + currentDate + ')',
                        //     confirmButtonText: 'OK'
                        // });
                    }
                    return json.data;
                }
            },
            language: {
                emptyTable: 'No Data found for Report : Payments (AP)   (' + currentDate + ')',
            },
            initComplete: function(settings, json) {
                var api = this.api();
                var rowCount = api.rows().count(); // Get total number of rows
                var buttons = api.buttons();

                // Disable buttons if no records
                if (rowCount === 0) {
                    buttons.disable(); // Disable all buttons if no records
                } else {
                    buttons.enable(); // Enable buttons if records exist
                }
            },
            columns: [
                // { data: 'DT_RowIndex', name: 'DT_RowIndex', title: '#', orderable: false, searchable: false },
                {
                    data: 'DT_RowIndex', // Index column
                    name: 'DT_RowIndex',
                    searchable: false,
                    orderable: false,

                },
                // {
                //     data: 'client',
                //     name: 'client'
                // },
                {
                    data: 'service_provider',
                    name: 'service_provider'
                },
                // {
                //     data: 'type',
                //     name: 'type'
                // },
                // {
                //     data: 'service_provider',
                //     name: 'service_provider'
                // },
                {
                    data: 'service_taken',
                    name: 'service_taken'
                },
                {
                    data: 'payment_mode',
                    name: 'payment_mode'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'paid_amount',
                    name: 'paid_amount'
                },
                {
                    data: 'amount_to_pay',
                    name: 'amount_to_pay'
                },
                {
                    data: 'payment_date',
                    name: 'payment_date'
                },
                // {
                //     data: 'invoice_no',
                //     name: 'invoice_no'
                // },
                {
                    data: 'created_at',
                    name: 'created_at'
                }

                //{ data: 'action', name: 'action' },

            ],


        });
    }

    function onchangePaymentsAPReport(type, text) {
        let selectedText = text;

        if (type === '') {
            // If empty, hide the table and the report title
            $('#reportAPPayments').hide(); // Hide the table

            return; // Exit the function
        }
        // Ensure DataTable is properly destroyed and HTML table is cleared
        if ($.fn.DataTable.isDataTable('#reportPaymentAPTable')) {
            $('#reportPaymentAPTable').DataTable().clear().destroy();
            $('#reportPaymentAPTable').empty(); // Remove existing columns and rows
        }
        var result = getStartAndEndDate('PaymentAP');
        // This arrangement can be altered based on how we want the date's format to appear.
        let currentDate = `${result.startDate} - ${result.endDate}`;
        let columns = [];
        $('#reportAPPayments').show();
        let dataTableSettings = {
            processing: true,
            serverSide: true,
            searching: true, // Disable the search box
            // Disable the "Showing x to y of z entries" message
            "lengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "pageLength": 10,
            destroy: true,
            dom: '<"d-flex justify-content-between align-items-center"lBf>rtip', // Custom layout
            buttons: [{
                    extend: 'csv',
                    title: 'Report : Payments (AP) ' + selectedText + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''), // Custom title for CSV
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'excel',
                    title: 'Report : Payments (AP) ' + selectedText + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''), // Custom title for Excel
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'pdf',
                    title: 'Report : Payments (AP) ' + selectedText + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''), // Custom title for Excel
                    orientation: 'landscape', // Makes table fit better
                    pageSize: 'A4',
                    customize: function(doc) {
                        let table = doc.content[1].table;
                        let columnCount = table.body[0].length;

                        // Dynamically set column widths to distribute space evenly
                        table.widths = ['8%', ...Array(columnCount - 1).fill('*')];

                        // Adjust styles
                        doc.defaultStyle.fontSize = 10; // Font size for table data
                        doc.styles.tableHeader.fontSize = 12; // Larger header font
                        doc.styles.title.fontSize = 18; // Title font
                        doc.pageMargins = [10, 10, 10, 10]; // Reduce margins
                        doc.styles.title.bold = true;

                        // Force full-page width usage
                        doc.content[1].layout = {
                            hLineWidth: function(i, node) {
                                return 0.5; // Line thickness
                            },
                            vLineWidth: function(i, node) {
                                return 0.5;
                            },
                            paddingLeft: function(i, node) {
                                return 4; // Adjust left padding
                            },
                            paddingRight: function(i, node) {
                                return 4;
                            }
                        };

                        // Center-align all table content
                        doc.content[1].table.body.forEach(function(row, rowIndex) {
                            row.forEach(function(cell, cellIndex) {
                                if (typeof cell === 'object') {
                                    cell.alignment = 'center'; // Center-align text in each cell
                                }
                            });
                        });

                        // Center-align the title
                        doc.content[0].alignment = 'center';
                    },
                    exportOptions: {
                        columns: ':visible',
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                }
            ],
            ajax: {
                url: "{{ route('paymentReport') }}",
                data: function(d) {
                    d.type = type;
                    d.payment_type = 'ap'
                    d.startDate = result.startDate;
                    d.endDate = result.endDate
                },
                dataSrc: function(json) {
                    if (json.data.length === 0) {
                        // Swal.fire({
                        //     icon: 'warning',
                        //     title: 'No Data Available',
                        //     text: 'No Data found for Report : Payments  ' + selectedText + ' (' + currentDate + ')',
                        //     confirmButtonText: 'OK'
                        // });
                    }
                    return json.data;
                }
            },
            language: {
                emptyTable: 'No Data found for Report : Payments (AP)  ' + selectedText + ' (' + currentDate + ')',
            },
            initComplete: function(settings, json) {
                var api = this.api();
                var rowCount = api.rows().count(); // Get total number of rows
                var buttons = api.buttons();

                // Disable buttons if no records
                if (rowCount === 0) {
                    buttons.disable(); // Disable all buttons if no records
                } else {
                    buttons.enable(); // Enable buttons if records exist
                }
            },
            columns: columns,
            order: [
                [1, 'desc']
            ]
        };
        // Configure DataTable based on report type
        if (type == 'byPaymentMode') {
            $('#clientReportAPTitle5').html('Payments (AP) By Mode');
            dataTableSettings.columns = [{
                    title: "Sr.No",
                    data: 'DT_RowIndex', // Index column
                    name: 'DT_RowIndex',
                    searchable: false,
                    orderable: false,

                }, {
                    title: "Payment Mode",
                    data: 'payment_mode',
                    name: 'payment_mode'
                },
                {
                    title: "No. of Payments",
                    data: 'number_of_payment',
                    name: 'number_of_payment'
                }
            ];
        } else if (type == 'byPaymentAmount') {
            $('#clientReportAPTitle5').html('Payments (AP) By Payment Amount');
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                },
                {
                    title: "Amount (Range)",
                    data: 'amount_range',
                    name: 'amount_range',

                },
                {
                    title: "No. of Payments",
                    data: "number_of_invoices",
                    name: "number_of_invoices",

                }

            ];
            dataTableSettings.order = [
                [1, 'asc']
            ]
        } else if (type == 'byOutstandingAmount') {
            $('#clientReportAPTitle5').html('Payments (AP) By Outstanding Amount');
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Client Name (ID)",
                    data: 'client_name',
                    name: 'client_name'
                },
                {
                    title: "Name of Application/Service",
                    data: 'application_name',
                    name: 'application_name',

                },
                {
                    title: "Amount To Pay",
                    data: "amount",
                    name: "amount",

                },
                {
                    title: "Paid Amount",
                    data: "paid_amount",
                    name: "paid_amount",

                },
                {
                    title: "Outstanding Amount",
                    data: "amount_to_pay",
                    name: "amount_to_pay",

                },

                {
                    title: "Payment Due Date",
                    data: "due_date",
                    name: "due_date",

                }
            ];
            dataTableSettings.order = [
                [1, 'asc']
            ]
        } else if (type == 'byInvoiceType') {
            $('#clientReportAPTitle5').html('Payments (AP) By Payment Type');
            dataTableSettings.columns = [{
                    title: "Sr.No",
                    data: 'DT_RowIndex', // Index column
                    name: 'DT_RowIndex',
                    searchable: false,
                    orderable: false,

                }, {
                    title: "Payment Type",
                    data: 'type',
                    name: 'type'
                },
                {
                    title: "No. of Payments",
                    data: 'number_of_invoices',
                    name: 'number_of_invoices'
                }
            ];
        } else if (type == 'byClientCountry') {
            $('#clientReportAPTitle5').html("Payments (AP) By Client's Home Country");
            dataTableSettings.columns = [{
                    title: "Sr.No",
                    data: 'DT_RowIndex', // Index column
                    name: 'DT_RowIndex',
                    searchable: false,
                    orderable: false,

                }, {
                    title: "Home Country",
                    data: 'country',
                    name: 'country'
                },
                {
                    title: "No. of Payments",
                    data: 'number_of_payment',
                    name: 'number_of_payment'
                }
            ];
        } else if (type == 'byVisaCountry') {
            $('#clientReportAPTitle5').html("Payments (AP) By Visa Country");
            dataTableSettings.columns = [{
                    title: "Sr.No",
                    data: 'DT_RowIndex', // Index column
                    name: 'DT_RowIndex',
                    searchable: false,
                    orderable: false,

                }, {
                    title: "Visa Country",
                    data: 'to_country',
                    name: 'to_country'
                },
                {
                    title: "No. of Payments",
                    data: 'number_of_payment',
                    name: 'number_of_payment'
                }
            ];
        } else if (type == 'byApplicationType') {
            $('#clientReportAPTitle5').html('Payments (AP) By Application Type');
            dataTableSettings.columns = [{
                    title: "Sr.No",
                    data: 'DT_RowIndex', // Index column
                    name: 'DT_RowIndex',
                    searchable: false,
                    orderable: false,

                }, {
                    title: "Application Type",
                    data: 'application_name',
                    name: 'application_name'
                },
                {
                    title: "No. of Payments",
                    data: 'number_of_payment',
                    name: 'number_of_payment'
                }
            ];
        } else if (type == 'byTimeLine') {
            $('#clientReportAPTitle5').html('Payments (AP) By Timeline');
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Timeline (Duration)",
                    data: 'type',
                    name: 'type',

                },
                {
                    title: "No of Payments",
                    data: 'count',
                    name: 'count',

                }
            ];
            dataTableSettings.order = [
                // []
            ]
        } else if (type == 'byYear') {
            $('#clientReportAPTitle5').html('Payments (AP) By Year');
            dataTableSettings.columns = [{
                    title: "Sr.No",
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Year",
                    data: 'year',
                    name: 'year'
                },
                {
                    title: "No. of Payments",
                    data: 'count',
                    name: 'count'
                }
            ];
            dataTableSettings.order = [
                // []
            ]
        }

        $('#custom_date_picker71').prop('disabled', type == 'byTimeLine' || type == 'byYear');
        // Initialize the DataTable with the new settings
        $('#reportPaymentAPTable').DataTable(dataTableSettings);
    }

    function onClickReferrals() {
        var result = getStartAndEndDate('Referral');
        let currentDate = `${result.startDate} - ${result.endDate}`
        deativeTabs();
        refferalsTable1 = true;
        var refferalsTable = $('#refferalsTable1').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            "lengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "pageLength": 10,
            dom: '<"d-flex justify-content-between align-items-center"lBf>rtip', // Custom layout
            "buttons": [{
                    extend: 'csv',
                    title: 'Report : Referrals  (' + currentDate + ')', // Custom title for C
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'excel',
                    title: 'Report : Referrals  (' + currentDate + ')', // Custom title for Exc
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'pdf',
                    title: 'Report : Referrals  (' + currentDate + ')', // Custom title for Exc
                    orientation: 'landscape', // Makes table fit better
                    pageSize: 'A4',
                    customize: function(doc) {
                        let table = doc.content[1].table;
                        let columnCount = table.body[0].length;

                        // Dynamically set column widths to distribute space evenly
                        table.widths = [
                            '5%',
                            '*',
                            '*',
                            '8%', // Column 1 (3% width)
                            '8%',
                            '7%', // Column 3 (2% width)
                            '7%', // Column 5 (2% width)
                            '7%', // Column 7 (2% width)
                            '7%',
                            // Column 8 (3% width)
                        ];

                        doc.defaultStyle.fontSize = 10; // Font size for table data
                        doc.styles.tableHeader.fontSize = 12; // Larger header font
                        doc.styles.title.fontSize = 18; // Title font
                        doc.pageMargins = [10, 10, 10, 10]; // Reduce margins
                        doc.styles.title.bold = true;

                        // Force full-page width usage
                        doc.content[1].layout = {
                            hLineWidth: function(i, node) {
                                return 0.5; // Line thickness
                            },
                            vLineWidth: function(i, node) {
                                return 0.5;
                            },
                            paddingLeft: function(i, node) {
                                return 4; // Adjust left padding
                            },
                            paddingRight: function(i, node) {
                                return 4;
                            }
                        };

                        // Center-align all table content
                        doc.content[1].table.body.forEach(function(row, rowIndex) {
                            row.forEach(function(cell, cellIndex) {
                                if (typeof cell === 'object') {
                                    cell.alignment = 'center'; // Center-align text in each cell
                                }
                            });
                        });

                        // Center-align the title
                        doc.content[0].alignment = 'center';
                    },
                    exportOptions: {
                        columns: ':visible',
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                }
            ],
            ajax: {
                url: "{{ route('manage_report_referrals') }}",
                data: function(d) {
                    // Add additional data here
                    d.startDate = result.startDate;
                    d.endDate = result.endDate;

                },
                dataSrc: function(json) {
                    if (json.data.length === 0) {
                        // Swal.fire({
                        //     icon: 'warning',
                        //     title: 'No Data Available',
                        //     text: 'No Data found for Report : Referrals  (' + currentDate + ')',
                        //     confirmButtonText: 'OK'
                        // });
                    }
                    return json.data;
                }
            },
            language: {
                emptyTable: 'No Data found for Report : Referrals  (' + currentDate + ')',
            },
            initComplete: function(settings, json) {
                var api = this.api();
                var rowCount = api.rows().count(); // Get total number of rows
                var buttons = api.buttons();

                // Disable buttons if no records
                if (rowCount === 0) {
                    buttons.disable(); // Disable all buttons if no records
                } else {
                    buttons.enable(); // Enable buttons if records exist
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'referral_by',
                    name: 'referral_by'
                },
                {
                    data: 'referral_to',
                    name: 'referral_to'
                },
                {
                    data: 'referral_code',
                    name: 'referral_code'
                },
                {
                    data: 'membership',
                    name: 'membership'
                },
                {
                    data: 'total_amount',
                    name: 'total_amount',

                },
                {
                    data: 'amount_added',
                    name: 'amount_added',

                },
                {
                    data: 'dos',
                    name: 'dos'
                },

                {
                    data: 'created_at',
                    name: 'created_at'
                },

            ],

        });
    }
    // -------------------------------------------------------------------------------
    // ---------------------- Referral Report ------------------------------------------
    // -------------------------------------------------------------------------------



    function onChangeReferralsReport(type, text) {
        let selectedText = text;

        if (type === '') {
            // If empty, hide the table and the report title
            $('#reportReferrals').hide(); // Hide the table

            return; // Exit the function
        }
        var result = getStartAndEndDate('Referral');
        let currentDate = `${result.startDate} - ${result.endDate}`
        $('#reportReferrals').show();

        let reportTitle = '';
        let ajaxType = type;
        let columns = [];
        let order = [];

        switch (type) {
            case 'subscribers':
                reportTitle = "Referrals By Subscriber's  Sub-Category";
                columns = [{
                        title: "Sr.No",
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    }, {
                        title: "Subscriber's Sub-Category",
                        data: 'sub_category',
                        name: 'sub_category'
                    },
                    {
                        title: "No. of Referrals",
                        data: 'user_count',
                        name: 'user_count'
                    }
                ];
                break;
            case 'subscriberType':
                reportTitle = "Referrals By Subscriber's Category";
                columns = [{
                        title: "Sr.No",
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    }, {
                        title: "Subscriber's Category",
                        data: 'category',
                        name: 'category'
                    },
                    {
                        title: "No. of Referrals",
                        data: 'user_count',
                        name: 'user_count'
                    }
                ];
                break;
            case 'subscribedPlan':
                reportTitle = 'Referrals By Subscribed Plan';
                columns = [{
                        title: "Sr.No",
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    }, {
                        title: "Subscription Type (Plan)",
                        data: 'membership',
                        name: 'membership'
                    },
                    {
                        title: "No. of Referrals",
                        data: 'user_count',
                        name: 'user_count'
                    }
                ];
                break;
            case 'yearly':
                reportTitle = 'Referrals By Year';
                columns = [{
                        title: "Sr.No",
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    }, {
                        title: "Year",
                        data: 'year',
                        name: 'year'
                    },
                    {
                        title: "No. of Referrals",
                        data: 'count',
                        name: 'count'
                    }
                ];
                break;
            case 'byTimeline':
                reportTitle = 'Referrals By Timeline ';
                columns = [{
                        title: "Sr.No",
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    }, {
                        title: "Timeline (Duration)",
                        data: 'year',
                        name: 'year'
                    },
                    {
                        title: "No. of Referrals",
                        data: 'count',
                        name: 'count'
                    }
                ];
                break;


            default:
                return;
        }
        $('#custom_date_picker8').prop('disabled', type == 'byTimeline' || type == 'yearly');
        $('#referralReportTitle').html(reportTitle);

        $('#reportReferralTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true, // Disable the search box
            // Disable the "Showing x to y of z entries" message
            "lengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "pageLength": 10,
            destroy: true,
            dom: '<"d-flex justify-content-between align-items-center"lBf>rtip', // Custom layout
            buttons: [{
                    extend: 'csv',
                    title: 'Report : Referrals ' + selectedText + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''),
                    exportOptions: {
                        modifier: {
                            page: 'all'
                        }
                    } // Export all data, not just the current page
                },
                {
                    extend: 'excel',
                    title: 'Report : Referrals ' + selectedText + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''),
                    exportOptions: {
                        modifier: {
                            page: 'all'
                        }
                    } // Export all data, not just the current page
                },
                {
                    extend: 'pdf',
                    title: 'Report : Referrals ' + selectedText + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''), // Custom title for Excel
                    orientation: 'landscape', // Makes table fit better
                    pageSize: 'A4',
                    customize: function(doc) {
                        let table = doc.content[1].table;
                        let columnCount = table.body[0].length;

                        // Dynamically set column widths to distribute space evenly
                        table.widths = ['8%', ...Array(columnCount - 1).fill('*')];

                        // Adjust styles
                        doc.defaultStyle.fontSize = 10; // Font size for table data
                        doc.styles.tableHeader.fontSize = 12; // Larger header font
                        doc.styles.title.fontSize = 18; // Title font
                        doc.pageMargins = [10, 10, 10, 10]; // Reduce margins
                        doc.styles.title.bold = true;

                        // Force full-page width usage
                        doc.content[1].layout = {
                            hLineWidth: function(i, node) {
                                return 0.5; // Line thickness
                            },
                            vLineWidth: function(i, node) {
                                return 0.5;
                            },
                            paddingLeft: function(i, node) {
                                return 4; // Adjust left padding
                            },
                            paddingRight: function(i, node) {
                                return 4;
                            }
                        };

                        // Center-align all table content
                        doc.content[1].table.body.forEach(function(row, rowIndex) {
                            row.forEach(function(cell, cellIndex) {
                                if (typeof cell === 'object') {
                                    cell.alignment = 'center'; // Center-align text in each cell
                                }
                            });
                        });

                        // Center-align the title
                        doc.content[0].alignment = 'center';
                    },
                    exportOptions: {
                        columns: ':visible',
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                }
            ],
            ajax: {
                url: "{{ route('referralsReport') }}",
                data: function(d) {
                    d.type = ajaxType;
                    d.startDate = result.startDate;
                    d.endDate = result.endDate
                },
                dataSrc: function(json) {
                    if (json.data.length === 0) {
                        // Swal.fire({
                        //     icon: 'warning',
                        //     title: 'No Data Available',
                        //     text: 'No Data found for Report : Referrals  ' + selectedText + ' (' + currentDate + ')',
                        //     confirmButtonText: 'OK'
                        // });
                    }
                    return json.data;
                }
            },
            language: {
                emptyTable: 'No Data found for Report : Referrals  ' + selectedText + ' (' + currentDate + ')',
            },
            initComplete: function(settings, json) {
                var api = this.api();
                var rowCount = api.rows().count(); // Get total number of rows
                var buttons = api.buttons();

                // Disable buttons if no records
                if (rowCount === 0) {
                    buttons.disable(); // Disable all buttons if no records
                } else {
                    buttons.enable(); // Enable buttons if records exist
                }
            },
            columns: columns,
            order: order
        });
    }


    function onClickWallets() {

        var result = getStartAndEndDate('Wallet');
        let currentDate = `${result.startDate} - ${result.endDate}`

        deativeTabs();
        walletsTable1 = true;
        transactionsTable1 = true;

        var walletsTable = $('#walletsTable1').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            "lengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "pageLength": 10,
            dom: '<"d-flex justify-content-between align-items-center"lBf>rtip', // Custom layout
            "buttons": [{
                    extend: 'csv',
                    title: 'Report : Wallet (' + currentDate + ')', // Custom title for CSV
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'excel',
                    title: 'Report : Wallet (' + currentDate + ')', // Custom t
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'pdf',
                    title: 'Report : Wallet  (' + currentDate + ')', // Custom title for Exc
                    orientation: 'landscape', // Makes table fit better
                    pageSize: 'A4',
                    customize: function(doc) {
                        let table = doc.content[1].table;
                        let columnCount = table.body[0].length;

                        // Dynamically set column widths to distribute space evenly
                        table.widths = [
                            '5%',
                            '6%',
                            '*',
                            '*',
                            '7%', // Column 3 (2% width)
                            '7%', // Column 5 (2% width)
                            '7%', // Column 7 (2% width)
                            '7%',
                            // Column 8 (3% width)
                        ];

                        doc.defaultStyle.fontSize = 10; // Font size for table data
                        doc.styles.tableHeader.fontSize = 12; // Larger header font
                        doc.styles.title.fontSize = 18; // Title font
                        doc.pageMargins = [10, 10, 10, 10]; // Reduce margins
                        doc.styles.title.bold = true;

                        // Force full-page width usage
                        doc.content[1].layout = {
                            hLineWidth: function(i, node) {
                                return 0.5; // Line thickness
                            },
                            vLineWidth: function(i, node) {
                                return 0.5;
                            },
                            paddingLeft: function(i, node) {
                                return 4; // Adjust left padding
                            },
                            paddingRight: function(i, node) {
                                return 4;
                            }
                        };

                        // Center-align all table content
                        doc.content[1].table.body.forEach(function(row, rowIndex) {
                            row.forEach(function(cell, cellIndex) {
                                if (typeof cell === 'object') {
                                    cell.alignment = 'center'; // Center-align text in each cell
                                }
                            });
                        });

                        // Center-align the title
                        doc.content[0].alignment = 'center';
                    },
                    exportOptions: {
                        columns: ':visible',
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                }
            ],
            ajax: {
                url: "{{ route('manage_report_wallet') }}",
                data: function(d) {
                    // Add additional data here
                    d.tableName = 'wallet';

                    // Add additional data here
                    d.startDate = result.startDate;
                    d.endDate = result.endDate;



                },
                dataSrc: function(json) {
                    if (json.data.length === 0) {
                        // Swal.fire({
                        //     icon: 'warning',
                        //     title: 'No Data Available',
                        //     text: 'No Data found for Report : Wallets  (' + currentDate + ')',
                        //     confirmButtonText: 'OK'
                        // });
                    }
                    return json.data;
                }
            },
            language: {
                emptyTable: 'No Data found for Report : Wallet  (' + currentDate + ')',
            },
            initComplete: function(settings, json) {
                var api = this.api();
                var rowCount = api.rows().count(); // Get total number of rows
                var buttons = api.buttons();

                // Disable buttons if no records
                if (rowCount === 0) {
                    buttons.disable(); // Disable all buttons if no records
                } else {
                    buttons.enable(); // Enable buttons if records exist
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'walletId',
                    name: 'walletId'
                },
                {
                    data: 'user_name',
                    name: 'user_name'
                },
                {
                    data: 'TransactionType',
                    name: 'TransactionType'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                // { data: 'amount_added', name: 'amount_added' },
                {
                    data: 'previous_balance',
                    name: 'previous_balance'
                },
                {
                    data: 'wallet_balance',
                    name: 'wallet_balance'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },

            ],

        });


    }



    function onchangeWalletReport(type, text) {

        let selectedText = text;
        console.log(selectedText);
        if (type === '') {
            // If empty, hide the table and the report title
            $('#reportWallet').hide(); // Hide the table

            return; // Exit the function
        }
        var result = getStartAndEndDate('Wallet');
        let currentDate = `${result.startDate} - ${result.endDate}`

        // Ensure DataTable is properly destroyed and HTML table is cleared
        if ($.fn.DataTable.isDataTable('#reportWalletTable')) {
            $('#reportWalletTable').DataTable().clear().destroy();
            $('#reportWalletTable').empty(); // Remove existing columns and rows
        }
        let order = [];
        $('#reportWallet').show();
        let dataTableSettings = {
            processing: true,
            serverSide: true,
            searching: true, // Disable the search box
            // Disable the "Showing x to y of z entries" message
            "lengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "pageLength": 10,
            destroy: true,
            dom: '<"d-flex justify-content-between align-items-center"lBf>rtip', // Custom layout
            "buttons": [{
                    extend: 'csv',
                    title: 'Report : Wallet ' + selectedText + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''), // Custom title for CSV
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'excel',
                    title: 'Report : Wallet ' + selectedText + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''), // Custom title for Excel
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'pdf',
                    title: 'Report : Wallet ' + selectedText + (!text.includes('By Timeline (Duration)') && !text.includes('By Year') ? ' (' + currentDate + ')' : ''), // Custom title for Excel
                    orientation: 'landscape', // Makes table fit better
                    pageSize: 'A4',
                    customize: function(doc) {
                        let table = doc.content[1].table;
                        let columnCount = table.body[0].length;

                        // Dynamically set column widths to distribute space evenly
                        table.widths = ['8%', ...Array(columnCount - 1).fill('*')];

                        // Adjust styles
                        doc.defaultStyle.fontSize = 10; // Font size for table data
                        doc.styles.tableHeader.fontSize = 12; // Larger header font
                        doc.styles.title.fontSize = 18; // Title font
                        doc.pageMargins = [10, 10, 10, 10]; // Reduce margins
                        doc.styles.title.bold = true;

                        // Force full-page width usage
                        doc.content[1].layout = {
                            hLineWidth: function(i, node) {
                                return 0.5; // Line thickness
                            },
                            vLineWidth: function(i, node) {
                                return 0.5;
                            },
                            paddingLeft: function(i, node) {
                                return 4; // Adjust left padding
                            },
                            paddingRight: function(i, node) {
                                return 4;
                            }
                        };

                        // Center-align all table content
                        doc.content[1].table.body.forEach(function(row, rowIndex) {
                            row.forEach(function(cell, cellIndex) {
                                if (typeof cell === 'object') {
                                    cell.alignment = 'center'; // Center-align text in each cell
                                }
                            });
                        });

                        // Center-align the title
                        doc.content[0].alignment = 'center';
                    },
                    exportOptions: {
                        columns: ':visible',
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                }
            ],
            ajax: {
                url: "{{ route('walletReport') }}",
                data: function(d) {
                    d.type = type;
                    d.startDate = result.startDate;
                    d.endDate = result.endDate
                },
                dataSrc: function(json) {
                    if (json.data.length === 0) {
                        // Swal.fire({
                        //     icon: 'warning',
                        //     title: 'No Data Available',
                        //     text: 'No Data found for Report : Wallets  (' + currentDate + ')',
                        //     confirmButtonText: 'OK'
                        // });
                    }
                    return json.data;
                }
            },
            language: {
                emptyTable: 'No Data found for Report :  Wallet ' + selectedText + ' (' + currentDate + ')'
            },
            initComplete: function(settings, json) {
                var api = this.api();
                var rowCount = api.rows().count(); // Get total number of rows
                var buttons = api.buttons();

                // Disable buttons if no records
                if (rowCount === 0) {
                    buttons.disable(); // Disable all buttons if no records
                } else {
                    buttons.enable(); // Enable buttons if records exist
                }
            },
            order: order
        };
        // Configure DataTable based on report type
        if (type == 'byWallets') {
            $('#clientReportTitle61').html('Wallet By Amount');
            dataTableSettings.columns = [{
                    title: "Sr.No",
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Amount(USD)",
                    data: 'payment_amount_range',
                    name: 'payment_amount_range'
                },
                {
                    title: "No. of Wallets",
                    data: 'number_of_wallet',
                    name: 'number_of_wallet'
                }
            ];
            dataTableSettings.order = [
                [1, 'asc']
            ]
        } else if (type == 'byTransactions') {
            $('#clientReportTitle61').html('Wallet By Transaction Type');
            dataTableSettings.columns = [{
                    title: "Sr.No",
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Transaction Type",
                    data: 'type',
                    name: 'type'
                },
                {
                    title: "No. of Transactions",
                    data: 'number_of_communication',
                    name: 'number_of_communication'
                }
            ];
        } else if (type == 'byDates') {

            $('#clientReportTitle61').html('Wallet  Transactions By Timeline (Duration)');
            dataTableSettings.columns = [{
                    title: 'Sr.No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '50px',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Timeline (Duration)",
                    data: 'type',
                    name: 'type',

                },
                {
                    title: "No of Transactions",
                    data: 'count',
                    name: 'count',

                }
            ];
            dataTableSettings.order = [
                [1, 'desc']
            ]
        } else if (type == 'byWalletYear') {
            $('#clientReportTitle61').html('Wallet Transactions By Year');
            dataTableSettings.columns = [{
                    title: "Sr.No",
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                }, {
                    title: "Year",
                    data: 'year',
                    name: 'year'
                },
                {
                    title: "No. of Transactions",
                    data: 'count',
                    name: 'count'
                }
            ];
            dataTableSettings.order = [
                [1, 'desc']
            ]
        }


        $('#custom_date_picker9').prop('disabled', type == 'byDates' || type == 'byWalletYear');
        // Initialize the DataTable with the new settings
        $('#reportWalletTable').DataTable(dataTableSettings);
    }

    function onClickAffiliates() {

        var result = getStartAndEndDate('Affiliat');
        let currentDate = `${result.startDate} - ${result.endDate}`

        deativeTabs();
        Affiliates1 = true;

        var refferalsTable = $('#AffiliatesTable1').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            "lengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "pageLength": 10,
            dom: '<"d-flex justify-content-between align-items-center"lBf>rtip', // Custom layout
            "buttons": [{
                    extend: 'csv',
                    title: 'Report-Affiliates(' + currentDate + ')', // Custom title for
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'excel',
                    title: 'Report-Affiliates(' + currentDate + ')', // Custom title for Ex
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'pdf',
                    title: 'Report-Affiliates(' + currentDate + ')', // Custom title for
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                }
            ],
            ajax: {
                url: "{{ route('affiliates_records') }}",
                data: function(d) {
                    // Add additional data here
                    d.startdate = result.startDate;
                    d.enddate = result.endDate;

                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name',


                },
                {
                    data: 'referral',
                    name: 'referral',
                    orderable: false,
                },
                {
                    data: 'type',
                    name: 'type',
                    orderable: false,
                },
                {
                    data: 'country',
                    name: 'country',
                    orderable: false,
                },
                {
                    data: 'city',
                    name: 'city',
                    orderable: false,
                },
                {
                    data: 'wallet',
                    name: 'wallet',
                    orderable: false,
                    render: function(data) {
                        return '$' + data;
                    }
                },
                {
                    data: 'created_at',
                    name: 'created_at',

                },
                {
                    data: 'get_affiliate.status',
                    name: 'get_affiliate.status',
                    render: function(data) {
                        if (data == 1) {
                            return 'Active';
                        }
                        return 'Deactive';
                    },
                },


            ],

        });
    }

    function onChangeAffiliatesReport(type, text) {
        let selectedText = text;

        var result = getStartAndEndDate('Affiliat');
        let currentDate = `${result.startDate} - ${result.endDate}`
        $('#reportAffiliates').show();

        let reportTitle = '';
        let ajaxType = type;
        let columns = [];

        switch (type) {
            case 'subscribersReferred':
                reportTitle = 'By No. of Subscribers Referred';
                columns = [{
                        title: "Subscribers Referred",
                        data: 'name',
                        name: 'name'
                    },
                    {
                        title: "No. of Affiliates",
                        data: 'total_referrals',
                        name: 'total_referrals'
                    }
                ];
                break;
            case 'earnedComission':
                reportTitle = 'By Amount of Commissions Earnt';
                columns = [{
                        title: "Affiliate Users",
                        data: 'name',
                        name: 'name',

                    },
                    {
                        title: "No. of Affiliates",
                        data: 'total_commission',
                        name: 'total_commission'
                    }
                ];
                break;
            case 'country':
                reportTitle = 'By Country';
                columns = [{
                        title: "Country",
                        data: 'country',
                        name: 'country'
                    },
                    {
                        title: "No. of Affiliates",
                        data: 'total_users',
                        name: 'total_users'
                    }
                ];
                break;
            case 'subscriberType':
                reportTitle = 'By Subscriber Type';
                columns = [{
                        title: "Subscriber Type",
                        data: 'category',
                        name: 'category'
                    },
                    {
                        title: "No. of Affiliates",
                        data: 'total_users',
                        name: 'total_users'
                    }
                ];
                break;
            case 'subscribedPlan':
                reportTitle = 'By Subscribed Plan';
                columns = [{
                        title: "Subscribed Plan",
                        data: 'membership',
                        name: 'membership'
                    },
                    {
                        title: "No. of Affiliates",
                        data: 'total_users',
                        name: 'total_users'
                    }
                ];
                break;
            case 'currentWalletCredits':
                reportTitle = 'By Current Wallet Credits';
                columns = [{
                        title: "Affiliate Users",
                        data: 'name',
                        name: 'name',

                    },
                    {
                        title: "No. of Affiliates",
                        data: 'amount_added',
                        name: 'amount_added'
                    }
                ];
                break;




            default:
                return;
        }

        $('#affiliateReportTitle').html(reportTitle);

        $('#reportAffiliateTable').DataTable({
            processing: true,
            serverSide: true,
            searching: false, // Disable the search box
            info: false, // Disable the "Showing x to y of z entries" message
            paging: false,
            destroy: true,
            dom: 'Blfrtip',
            buttons: [{
                    extend: 'csv',
                    title: `Report-Affiliates ${selectedText}(${currentDate})`, // Custom title for CSV
                    exportOptions: {
                        modifier: {
                            page: 'all'
                        }
                    } // Export all data, not just the current page
                },
                {
                    extend: 'excel',
                    title: `Report-Affiliates ${selectedText}(${currentDate})`, // Custom title for Excel
                    exportOptions: {
                        modifier: {
                            page: 'all'
                        }
                    } // Export all data, not just the current page
                },
                {
                    extend: 'pdf',
                    title: `Report-Affiliates ${selectedText}(${currentDate})`, // Custom title for PDF
                    exportOptions: {
                        modifier: {
                            page: 'all'
                        }
                    } // Export all data, not just the current page
                }
            ],
            ajax: {
                url: "{{ route('affiliatesReport') }}",
                data: function(d) {
                    d.type = ajaxType;
                    d.startdate = result.startDate;
                    d.enddate = result.endDate
                }
            },
            columns: columns,
            order: [
                [1, 'desc']
            ]
        });
    }

    function onClickSupportTickets() {

        var result = getStartAndEndDate('Support');
        let currentDate = `${result.startDate} - ${result.endDate}`

        deativeTabs();
        SupportTickets1 = true;

        var refferalsTable = $('#SupportTicketsTable1').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            "lengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "pageLength": 10,
            dom: '<"d-flex justify-content-between align-items-center"lBf>rtip', // Custom layout
            "buttons": [{
                    extend: 'csv',
                    title: 'Report-tickets(' + currentDate + ')', // Custom title for CSV
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'excel',
                    title: 'Report-tickets(' + currentDate + ')', // Custom t
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'pdf',
                    title: 'Report-tickets(' + currentDate + ')', // Custom
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                }
            ],
            ajax: {
                url: "{{ route('manage_support') }}",
                data: function(d) {
                    // Add additional data here
                    d.startdate = result.startDate;
                    d.enddate = result.endDate;

                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    title: '#',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'ticket_no',
                    name: 'ticket_no'
                },
                {
                    data: 'subscriber',
                    name: 'subscriber'
                },
                {
                    data: 'client',
                    name: 'client'
                },
                {
                    data: 'issue',
                    name: 'issue'
                },
                {
                    data: 'support',
                    name: 'support'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },

                //{ data: 'action', name: 'action' },

            ],

        });
    }

    function onchangeSupportReport(type, text) {
        let selectedText = text;

        // Ensure DataTable is properly destroyed and HTML table is cleared
        if ($.fn.DataTable.isDataTable('#reportSupportTable')) {
            $('#reportSupportTable').DataTable().clear().destroy();
            $('#reportSupportTable').empty(); // Remove existing columns and rows
        }
        var result = getStartAndEndDate('Support');
        let currentDate = `${result.startDate} - ${result.endDate}`
        $('#reportSupport').show();
        let dataTableSettings = {
            processing: true,
            serverSide: true,
            searching: false, // Disable the search box
            info: false, // Disable the "Showing x to y of z entries" message
            paging: false,
            destroy: true,
            dom: 'Blfrtip',
            "buttons": [{
                    extend: 'csv',
                    title: `Report-Support Tickets ${selectedText}(${currentDate})`, // Custom title for CSV
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'excel',
                    title: `Report-Support Tickets ${selectedText}(${currentDate})`, // Custom title for Excel
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'pdf',
                    title: `Report-Support Tickets ${selectedText}(${currentDate})`, // Custom title for PDF
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                }
            ],
            ajax: {
                url: "{{ route('supportReport') }}",
                data: function(d) {
                    d.type = type;
                    d.startdate = result.startDate;
                    d.enddate = result.endDate;
                }
            },
            order: [
                [1, 'desc']
            ]
        };
        // Configure DataTable based on report type
        if (type == 'byTicketType') {
            $('#clientReportTitle7').html('Tickets By Types');
            dataTableSettings.columns = [{
                    title: "Support Type",
                    data: 'support',
                    name: 'support'
                },
                {
                    title: "No. of Tickets",
                    data: 'number_of_tickets',
                    name: 'number_of_tickets'
                }
            ];
        } else if (type == 'byTime') {
            $('#clientReportTitle7').html('Tickets By Duration');
            dataTableSettings.columns = [{
                    title: "Duration",
                    data: 'period',
                    name: 'period'
                },
                {
                    title: "No. of Tickets",
                    data: 'total_activities',
                    name: 'total_activities'
                }
            ];
        } else if (type == 'byTimeTaken') {
            $('#clientReportTitle7').html('Tickets By Time Taken');
            dataTableSettings.columns = [{
                    title: "Duration",
                    data: 'time_interval',
                    name: 'time_interval'
                },
                {
                    title: "No. of Tickets",
                    data: 'total_tickets',
                    name: 'total_tickets'
                },
            ];
        } else if (type == 'bySupportStaff') {
            $('#clientReportTitle7').html('Tickets Solved');
            dataTableSettings.columns = [{
                    title: "User name (ID)",
                    data: 'username',
                    name: 'username'
                },
                {
                    title: "User_id",
                    data: 'user_id',
                    name: 'user_id'
                },
                {
                    title: "Avg Time",
                    data: 'avg_time_taken_hours',
                    name: 'avg_time_taken_hours'
                },
                {
                    title: "No. of Tickets Solved",
                    data: 'no_of_tickets_solved',
                    name: 'no_of_tickets_solved'
                }
            ];
        }




        // Initialize the DataTable with the new settings
        $('#reportSupportTable').DataTable(dataTableSettings);
    }

    function onClickDemoRequest() {

        var result = getStartAndEndDate('Demo');
        let currentDate = `${result.startDate} - ${result.endDate}`


        deativeTabs();
        demoRequest1 = true;
        var refferalsTable = $('#demoRequestTable1').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            order: [],
            "lengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "pageLength": 10,
            dom: '<"d-flex justify-content-between align-items-center"lBf>rtip', // Custom layout
            "buttons": [{
                    extend: 'csv',
                    title: 'Report-Subscribers(' + currentDate + ')', // Custom title for
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'excel',
                    title: 'Report-Subscribers(' + currentDate + ')', // Custom title for E
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'pdfHtml5',

                    title: 'Report-Subscribers(' + currentDate + ')', // Custom title for
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                }
            ],
            ajax: {
                url: "{{ route('demoReport') }}",
                data: function(d) {
                    // Add additional data here
                    d.startdate = result.startDate;
                    d.enddate = result.endDate;

                }
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'country',
                    name: 'country'
                },
                {
                    data: 'job_title',
                    name: 'job_title'
                },
                {
                    data: 'how_did_hear',
                    name: 'how_did_hear'
                }, {
                    data: 'served_by',
                    name: 'served_by'
                }, {
                    data: 'created_at',
                    name: 'created_at'
                }, {
                    data: 'service_date',
                    name: 'service_date'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                //{ data: 'action', name: 'action' },

            ],

        });
    }

    function onChangeDemoRequest(type, text) {
        let selectedText = text;

        // Ensure DataTable is properly destroyed and HTML table is cleared

        var result = getStartAndEndDate('Demo');
        let currentDate = `${result.startDate} - ${result.endDate}`
        $('#demoRequestTable').show();
        let dataTableSettings = {
            processing: true,
            serverSide: true,
            searching: false, // Disable the search box
            info: false, // Disable the "Showing x to y of z entries" message
            paging: false,
            destroy: true,
            dom: 'Blfrtip',
            "buttons": [{
                    extend: 'csv',
                    title: `Report-Demo Request ${selectedText}(${currentDate})`, // Custom title for CSV
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'excel',
                    title: `Report-Demo Request ${selectedText}(${currentDate})`, // Custom title for Excel
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'pdf',
                    title: `Report-Demo Request ${selectedText}(${currentDate})`, // Custom title for PDF
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                }
            ],
            ajax: {
                url: "{{ route('demoRequestReport') }}",
                data: function(d) {
                    d.type = type;
                    d.startdate = result.startDate;
                    d.enddate = result.endDate;
                }
            },
            order: [
                [1, 'desc']
            ]
        };

        // Configure DataTable based on report type
        if (type == 'byStatus') {
            $('#clientReportTitle7').html('Demo Request ByStatus');
            dataTableSettings.columns = [{
                    title: "Demo Request Status",
                    data: 'status',
                    name: 'status'
                },
                {
                    title: "No. of Tickets",
                    data: 'status_count',
                    name: 'status_count'
                }
            ];
        } else if (type == 'byCountry') {
            $('#clientReportTitle7').html('Demo Request ByCountry');
            dataTableSettings.columns = [{
                    title: "Demo request Country",
                    data: 'country',
                    name: 'country'
                },
                {
                    title: "No. of Tickets",
                    data: 'total_request',
                    name: 'total_request'
                }
            ];
        } else if (type == 'byTimeline') {
            $('#clientReportTitle7').html('Demo Request  ByTime Taken');
            dataTableSettings.columns = [{
                    title: "Duration",
                    data: 'time_interval',
                    name: 'time_interval'
                },
                {
                    title: "No. of Tickets",
                    data: 'total_tickets',
                    name: 'total_tickets'
                },
            ];
        } else if (type == 'byTimeTaken') {
            console.log('ddasds11a');
            $('#clientReportTitle7').html('Demo Request BySolved');
            console.log('ddasdsa');
            dataTableSettings.columns = [{
                    title: "Time Interval",
                    data: 'time_interval',
                    name: 'time_interval'
                },
                {
                    title: "Total Ticket",
                    data: 'total_tickets',
                    name: 'total_tickets'
                },

            ];
        }
        // Initialize the DataTable with the new settings
        $('#demoRequestTable').DataTable(dataTableSettings);

    }


    function onClickActivityLogs() {

        // This arrangement can be altered based on how we want the date's format to appear.
        var result = getStartAndEndDate('Activity');
        let currentDate = `${result.startDate} - ${result.endDate}`

        deativeTabs();
        ActivityLog1 = true;

        var refferalsTable = $('#ActivityLogTable1').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            "lengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "pageLength": 10,
            dom: '<"d-flex justify-content-between align-items-center"lBf>rtip', // Custom layout
            "buttons": [{
                    extend: 'csv',
                    title: 'Report-Activity log(' + currentDate + ')', // Custom title fo
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'excel',
                    title: 'Report-Activity log(' + currentDate + ')', // Custom title for
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'pdf',
                    title: 'Report-Activity log(' + currentDate + ')', // Custom title fo
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                }
            ],
            ajax: {
                url: "{{ route('activity_log') }}",
                data: function(d) {
                    // Add additional data here
                    d.startdate = result.startDate;
                    d.enddate = result.endDate;

                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    title: '#',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'activity_name',
                    name: 'activity_name'
                },
                {
                    data: 'user_name',
                    name: 'user_name'
                },
                {
                    data: 'activity_detail',
                    name: 'activity_detail'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },


            ],

        });
    }

    function onchangeActivityReport(type, text) {

        let selectedText = text;


        // Ensure DataTable is properly destroyed and HTML table is cleared
        if ($.fn.DataTable.isDataTable('#reportActivityTable')) {
            $('#reportActivityTable').DataTable().clear().destroy();
            $('#reportActivityTable').empty(); // Remove existing columns and rows
        }

        var result = getStartAndEndDate('Activity');
        let currentDate = `${result.startDate} - ${result.endDate}`
        $('#reportActivity').show();

        let dataTableSettings = {
            processing: true,
            serverSide: true,
            searching: false, // Disable the search box
            info: false, // Disable the "Showing x to y of z entries" message
            paging: false,
            destroy: true,
            dom: 'Blfrtip',
            buttons: [{
                    extend: 'csv',
                    title: `Report-Activity Log ${selectedText}(${currentDate})`, // Custom title for CSV
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'excel',
                    title: `Report-Activity Log ${selectedText}(${currentDate})`, // Custom title for Excel
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                },
                {
                    extend: 'pdf',
                    title: `Report-Activity Log ${selectedText}(${currentDate})`, // Custom title for PDF
                    exportOptions: {
                        modifier: {
                            page: 'all' // Export all data, not just the current page
                        }
                    }
                }
            ],
            ajax: {
                url: "{{ route('activityReport') }}",
                data: function(d) {
                    d.type = type;
                    d.startdate = result.startDate;
                    d.enddate = result.endDate;

                }
            },
            order: [
                [1, 'desc']
            ]
        };

        // Configure DataTable based on report type
        if (type == 'byActivityType') {
            $('#clientReportTitle8').html('Activity ByType');
            dataTableSettings.columns = [{
                    title: "Activity Type",
                    data: 'activity_name',
                    name: 'activity_name'
                },
                {
                    title: "No. of Activities",
                    data: 'count',
                    name: 'count',
                    orderable: false,
                    searchable: false
                }
            ];
        } else if (type == 'byTime') {
            $('#clientReportTitle8').html('Activity ByDuration');
            dataTableSettings.columns = [{
                    title: "Activity Duration",
                    data: 'period',
                    name: 'period',
                    orderable: false,
                    searchable: false
                },
                {
                    title: "No. of Activities",
                    data: 'total_activities',
                    name: 'total_activities',
                    orderable: false,
                    searchable: false
                }
            ];
        } else if (type == 'bySubscribers') {
            $('#clientReportTitle8').html('Activity BySubscribers');
            dataTableSettings.columns = [{
                    title: "Subscribers",
                    data: 'subscriber_id',
                    name: 'subscriber_id',
                    orderable: false,
                    searchable: false
                },
                {
                    title: "No. of Activities",
                    data: 'total_activities',
                    name: 'total_activities',
                    orderable: false,
                    searchable: false
                }
            ];
        }

        // Initialize the DataTable with the new settings
        $('#reportActivityTable').DataTable(dataTableSettings);



    }








    function deleteapplication(id) {
        var conf = confirm('Delete Application');
        if (conf == true) {
            window.location.href = "delete_application/" + id + "";
        }
    }











    // fgjk
    // fgjk




    jQuery.noConflict();
    (function($) {
        // onClickSubscribers();

        var start = moment().subtract(29, 'days').startOf('month');
        var end = moment().subtract(29, 'days').endOf('month');

        function cb(start, end) {
            // Update the display for each date picker
            $('#custom_date_picker span, #custom_date_picker2 span, #custom_date_picker3 span, #custom_date_picker4 span, #custom_date_picker5 span, #custom_date_picker6 span, #custom_date_picker66 span, #custom_date_picker7 span,#custom_date_picker71 span, #custom_date_picker8 span, #custom_date_picker9 span, #custom_date_picker10 span, #custom_date_picker11 span, #custom_date_picker12 span, #custom_date_picker13 span, #custom_date_picker14 span')
                .html(
                    start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY')
                );
        }

        // Apply daterangepicker to all elements
        $('#custom_date_picker, #custom_date_picker2, #custom_date_picker3, #custom_date_picker4, #custom_date_picker5, #custom_date_picker6, #custom_date_picker66, #custom_date_picker7,#custom_date_picker71,  #custom_date_picker8, #custom_date_picker9, #custom_date_picker10, #custom_date_picker11, #custom_date_picker12, #custom_date_picker13, #custom_date_picker14')
            .daterangepicker({
                startDate: start,
                endDate: end,
                maxDate: moment(),
                locale: {
                    format: 'DD-MM-YYYY',
                    customRangeLabel: 'Custom Duration' // Rename Custom Range
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Last Week': [moment().subtract(6, 'days'), moment()],
                    'Last Month': [moment().subtract(29, 'days'), moment()],
                    'Last Quarter': [moment().subtract(3, 'months').startOf('month'), moment().endOf('month')],
                    'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year')
                        .endOf('year')
                    ],
                    'Since Inception': [moment('2000-01-01'),
                        moment()
                    ] // Adjust the date to the actual beginning date of your records
                }
            }, cb);

        function cb(start, end, label) {
            // Set the value in the date picker input
            window.selectedLabel = label;
        }

        // Set the initial value
        cb(start, end, 'Today');

        // Handle the apply event for each date picker
        $('#custom_date_picker, #custom_date_picker2, #custom_date_picker3, #custom_date_picker4, #custom_date_picker5, #custom_date_picker6, #custom_date_picker66, #custom_date_picker7,  #custom_date_picker71 , #custom_date_picker8, #custom_date_picker9, #custom_date_picker10, #custom_date_picker11, #custom_date_picker12, #custom_date_picker13, #custom_date_picker14')
            .on('apply.daterangepicker', function(ev, picker) {
                // Get the start and end date values from the picker
                var startdate = picker.startDate.format('DD-MM-YYYY');
                var enddate = picker.endDate.format('DD-MM-YYYY');
                const selectedRangeLabel = picker.chosenLabel;
                // Depending on the active table, trigger the appropriate function
                if (Subscribers1 == true) {
                    onClickSubscribers();
                    var type = $('#subscriberfilter').val();
                    if (type.trim() !== '') {
                        onchangeSubscriberReport(type);
                    }
                } else if (dataTable1 == true) {

                    onClickClients();
                    var type = $('#clientfilter').val();
                    let selectedText = $('#clientfilter').find('option:selected').text();
                    if (type.trim() !== '') {
                        onchangeClientReport(type, selectedText);
                    }

                } else if (applicationTable1 == true) {
                    onClickApplication();
                    var type = $('#applicationFilter').val();
                    let selectedText = $('#applicationFilter').find('option:selected').text();
                    if (type.trim() !== '') {
                        onChangeApplicationReport(type, selectedText);
                    }
                } else if (userTable1 == true) {

                    onClickUsers();
                    var type = $('#userFilter').val();
                    let selectedText = $('#userFilter').find('option:selected').text();
                    if (type.trim() !== '') {
                        onchangeUserReport(type, selectedText);
                    }
                } else if (Documents1 == true) {
                    onClickDocuments();
                    var type = $('#documentFilter').val();
                    console.log('type' + type)
                    let selectedText = $('#documentFilter').find('option:selected').text();
                    if (type.trim() !== '') {
                        onchangeDocumentReport(type, selectedText);
                    }

                } else if (communicationTable1 == true) {
                    onClickCommunications();
                    var type = $('#communicationFilter').val();
                    console.log('type' + type)
                    let selectedText = $('#communicationFilter').find('option:selected').text();
                    if (type.trim() !== '') {
                        onchangeCommunicationReport(type, selectedText);
                    }
                } else if (invoiceTable1 == true) {
                    onClickInvoices();
                    var type = $('#invoiceFilter').val();
                    console.log('type' + type)
                    let selectedText = $('#invoiceFilter').find('option:selected').text();
                    if (type.trim() !== '') {
                        onchangeInvoicesReport(type, selectedText);
                    }
                } else if (invoiceAPTable1 == true) {
                    onClickInvoicesAP();
                    var type = $('#invoiceAPFilter').val();
                    console.log('type' + type)
                    let selectedText = $('#invoiceAPFilter').find('option:selected').text();
                    if (type.trim() !== '') {
                        onchangeInvoicesAPReport(type, selectedText);
                    }
                } else if (paymentsTable1 == true) {
                    onClickPayments();
                    var type = $('#paymentFilter').val();
                    console.log('type' + type)
                    let selectedText = $('#paymentFilter').find('option:selected').text();
                    if (type.trim() !== '') {
                        onchangePaymentsReport(type, selectedText);
                    }
                } else if (paymentsAPTable1 == true) {
                    onClickPaymentsAP();
                    var type = $('#paymentAPFilter').val();
                    console.log('type' + type)
                    let selectedText = $('#paymentAPFilter').find('option:selected').text();
                    if (type.trim() !== '') {
                        onchangePaymentsAPReport(type, selectedText);
                    }
                } else if (refferalsTable1 == true) {
                    onClickReferrals();
                    var type = $('#referralFilter').val();
                    console.log('type' + type)
                    let selectedText = $('#referralFilter').find('option:selected').text();
                    if (type.trim() !== '') {
                        onChangeReferralsReport(type, selectedText);
                    }
                } else if (SupportTickets1 == true) {
                    onClickSupportTickets();
                } else if (ActivityLog1 == true) {
                    onClickActivityLogs();

                    var type = $('#activityFilter').val();
                    console.log('type' + type)
                    let selectedText = $('#activityFilter').find('option:selected').text();
                    if (type.trim() !== '') {
                        onchangeActivityReport(type, selectedText);
                    }
                } else if (Affiliates1 == true) {
                    onClickAffiliates();
                    var type = $('#affiliateFilter').val();
                    console.log('type' + type)
                    let selectedText = $('#affiliateFilter').find('option:selected').text();
                    if (type.trim() !== '') {
                        onChangeAffiliatesReport(type, selectedText);
                    }

                } else if (walletsTable1 == true) {
                    onClickWallets();
                    var type = $('#walletFilter').val();
                    console.log('type' + type)
                    let selectedText = $('#walletFilter').find('option:selected').text();
                    if (type.trim() !== '') {
                        onchangeWalletReport(type, selectedText);
                    }

                } else if (demoRequest1 == true) {
                    onClickDemoRequest()
                    var type = $('#demoRequestFilter').val();
                    console.log('type' + type)
                    let selectedText = $('#demoRequestFilter').find('option:selected').text();
                    if (type.trim() !== '') {
                        onChangeDemoRequest(type, selectedText);
                    }
                }
            });
    })(jQuery);






    // -------------------------------------------------------------------------------
    // ---------------------- Affiliates  Report ------------------------------------------
    // -------------------------------------------------------------------------------
</script>
@endsection()
