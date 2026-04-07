@extends('web.layout.main')

@section('main-section')
@php

use App\Models\UserRoles;
$client_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Clients')->first();
$application_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Applications')->first();
$communication_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Communication')->first();
$invoice_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Invoices')->first();
$payment_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Payments')->first();
$report_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Reports')->first();
$subscription_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Subscription')->first();
$setting_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Settings')->first();
$support_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Support')->first();
@endphp
    <div class="col-lg-9 user-details">
        <h5 class="det-head mt-4">{{ $client->name }}</h5>
        <p class="det-class">{{ $client->city }}</p>
        <div class="row">
            <div class="col-lg-5 c-detail">
                <p>Client Details</p>
                <div class="info-client">
                    <div class="row m-0" style="width: 100%;">
                        <div class="col-12 text-end editss">
                            <img @if($client_roles->update_only == 1) onclick="document.getElementById('update_img_box').style.display='flex';" @endif
                                src="{{ asset('web_assets/images/edit.png') }}"width="20" height="20" alt="">
                        </div>
                    </div>
                    @if ($client->profile_img != '')
                        <img src="{{ asset('web_assets/users/client' . $client->id . '/' . $client->profile_img) }}"
                            width="200" height="200" alt="">
                    @else
                        <img src="{{ asset('web_assets/images/profile.jpg') }}" width="200" height="200"
                            alt="">
                    @endif
                    <div class="row m-0 mt-3" style="width: 100%;">
                        <div class="col-12 text-end editss">
                            <img style="cursor: pointer;" class="m-0" @if($client_roles->update_only == 1)
                                onclick="document.getElementById('update_box').style.display='flex';" @endif
                                src="{{ asset('web_assets/images/edit.png') }}"width="20" height="20" alt="">
                        </div>
                    </div>
                    <div class="row info-row">
                        <div class="col">
                            <div class="row">
                                <div class="col-6">
                                    <p style="font-weight:550;">Client Id</p>
                                </div>
                                <div class="col-6">
                                    <p>{{ $client->id }}</p>
                                </div>
                                <div class="col-6">
                                    <p style="font-weight:550;">Name</p>
                                </div>
                                <div class="col-6">
                                    <p>{{ $client->name }}</p>
                                </div>
                                <div class="col-6">
                                    <p style="font-weight:550;">Phone Number</p>
                                </div>
                                <div class="col-6">
                                    <p>{{ $client->phone }}</p>
                                </div>
                                <div class="col-6">
                                    <p style="font-weight:550;">Email ID</p>
                                </div>
                                <div class="col-6">
                                    <p>{{ $client->email }}</p>
                                </div>
                                <div class="col-6">
                                    <p style="font-weight:550;">Alternate No.</p>
                                </div>
                                <div class="col-6">
                                    <p>{{ $client->alternate_no }}</p>
                                </div>
                                <div class="col-6">
                                    <p style="font-weight:550;">Nationality</p>
                                </div>
                                <div class="col-6">
                                    <p>{{ $client->nationality }}</p>
                                </div>
                                <div class="col-6">
                                    <p style="font-weight:550;">Passport No.</p>
                                </div>
                                <div class="col-6">
                                    <p>{{ $client->passport_no }}</p>
                                </div>
                                <div class="col-6">
                                    <p style="font-weight:550;">Date of Birth</p>
                                </div>
                                <div class="col-6">
                                    <p>{{ date("d-m-Y", strtotime($client->dob))}}</p>
                                </div>
                                <div class="col-6">
                                    <p style="font-weight:550;">Country</p>
                                </div>
                                <div class="col-6">
                                    <p>{{ $client->country }}</p>
                                </div>
                                <div class="col-6">
                                    <p style="font-weight:550;">Address</p>
                                </div>
                                <div class="col-6">
                                    <p>{{ $client->address }}</p>
                                </div>
                                <div class="col-6">
                                    <p style="font-weight:550;">State/County</p>
                                </div>
                                <div class="col-6">
                                    <p>{{ $client->state }}</p>
                                </div>
                                <div class="col-6">
                                    <p style="font-weight:550;">City/Town</p>
                                </div>
                                <div class="col-6">
                                    <p>{{ $client->city }}</p>
                                </div>
                                <div class="col-6">
                                    <p style="font-weight:550;">Postcode</p>
                                </div>
                                <div class="col-6">
                                    <p>{{ $client->pincode }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 c-detail">
                <p>Application Details</p>
                <div class="info-client i-client">
                    {{-- <div class="row m-0 mt-3" style="width: 100%;">
                        <div class="col-12 text-end editss">
                            <img style="cursor: pointer;" class="m-0"
                                onclick="document.getElementById('update_job_box').style.display='flex';"
                                src="{{ asset('web_assets/images/edit.png') }}"width="20" height="20"
                                alt="">
                        </div>
                    </div> --}}
                    <div class="row info-row">

                        <table id="userTable" class="table fl-table table-hover">
                            <thead>
                                <tr>
                                    <th>Application</th>
                                    <th>Status</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($applications as $app)
                                <tr>
                                    <td>{{ $app->application_name }}</td>
                                    <td>{{ $app->application_status }}</td>
                                    <td><a href="{{ route('view_application', $app->id)}}"><i class="fa-solid fa-eye"></i></a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{-- <div class="col-6">
                            <p>Job ID</p>
                        </div>
                        <div class="col-6">
                            <p>{{ $app->application_id }}</p>
                        </div>
                        <div class="col-6">
                            <p>Job role</p>
                        </div>
                        <div class="col-6">
                            <p>{{ $app->application_name }}</p>
                        </div>
                        <div class="col-6">
                            <p>Job Detail</p>
                        </div>
                        <div class="col-6">
                            <p>{{ $app->application_detail }}</p>
                        </div>
                        <div class="col-6">
                            <p>Job Open Date</p>
                        </div>
                        <div class="col-6">
                            <p>
                                @if ($app->start_date != '')
                                    {{ date('d-m-Y', strtotime($app->start_date)) }}
                                @endif
                            </p>
                        </div>
                        <div class="col-6">
                            <p>Job Status</p>
                        </div>
                        <div class="col-6">
                            <p>{{ $app->application_status }}</p>
                        </div>
                        <div class="col-6">
                            <p>Job Completion Date</p>
                        </div>
                        <div class="col-6">
                            <p>{{ $app->end_date }}</p>
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="col-lg-3 c-detail">
                <p>Activities</p>
                <div class="info-client i-client">
                    @foreach($activities as $activity)
                    <div class="col-12 d-flex p-2 m-0 align-items-center" style="border-bottom: 1px solid lightgrey;">
                        <img class="m-0" src="{{ asset('web_assets/images/'.$activity->activity_icon) }}" width="25" height="25" alt="">
                        <div class="px-2" style="font-size:14px;">{{ $activity->activity_name }}<br><small>{{ date("D M d Y H:i:s",strtotime($activity->created_at))}}</div>
                    </div>
                    {{-- <div class="p-docbox d-flex">
                        <img src="{{ asset('web_assets/images/'.$activity->activity_icon) }}" width="25" height="25" alt="">
                        <p>{{ $activity->activity_name }} <br> <span>{{ date("D M d Y H:i:s",strtotime($activity->created_at))}}</span> </p>
                        <i class="fa-regular fa-circle-check float-right"></i>
                    </div> --}}
                    @endforeach
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-lg-8 c-detail">
                <div class="d-flex justify-content-between align-items-center">
                <p class="m-0">Documents</p>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('generate_ccl_box').style.display='flex';">Generate Client Care Letter</button>
            </div>
                {{-- <div class="col text-end mb-2"><a onclick="document.getElementById('add_docs').style.display='flex';" class="btn btn-primary">+Add Document</a></div> --}}
                <div class="docs-client" style="height: auto;">
                    @if(isset($documents))
                    <table id="document_box" class="fl-table table table-hover bg-white" style="font-size:14px;">
                        <tr>
                            <th class="text-center" style="height: fit-content;">Sr No.</th>
                            <th class="text-center" style="height: fit-content;">Document Name</th>
                            <th class="text-center" style="height: fit-content;">Updated On</th>
                            <th class="text-center" style="height: fit-content;">Download</th>
                        </tr>
                        @foreach($documents as $key => $document)
                        <tr>
                            <td class="text-center">{{ $key+1 }}</td>
                            <td class="text-center">{{ $document->doc_name }}</td>
                            <td class="text-center">{{ date("d-m-Y H:i:s", strtotime($document->updated_at)) }}</td>
                            <td class="text-center"><a href="{{ asset('web_assets/users/client'.$document->client_id.'/docs/'.$document->doc_file) }}" download="{{ $document->doc_file }}"><i class="fa-solid fa-download"></i></a></td>
                        </tr>
                        @endforeach
                    </table>
                    @endif
                </div>
            </div>
            <div class="col-lg-4 c-detail">
                <p>Message Panel</p>
                <div class="message-box">
                    <div id="messages" style="width: 100%;min-height:50px;max-height:250px;display:flex;flex-direction:column;border:1px solid lightgrey;overflow:auto;">
                        @if(isset($messages))
                        @foreach($messages as $msg)
                        <div class="p-1" style="height: fit-content;">
                            <h5>@if($msg->admin_id == null) me: @else admin: @endif <span>{{ date('d M Y, H:i:s', strtotime($msg->created_at)) }}</span></h5>
                            <p class="message">{{ $msg->message }}</p>
                        </div>
                        @endforeach
                        @endif
                    </div>
                    <form id="message_form" class="form-control" method="POST" action="{{ route('send_message') }}" style="border:none;">
                        @csrf
                        <!-- <input type="hidden" name="local_time" class="localtime" /> -->
                        <input type="hidden" name="client_id" value="{{ $client->id }}" />
                        <div class="row px-2">
                            <div class="col-12 m-0 p-0">
                                <textarea class="form-control" rows="2" name="message" placeholder="Type Your Message"></textarea>
                            </div>
                            <div class="col-12 text-end m-0 p-0">
                                <input class="form-control btn btn-primary" type="submit" value="Send" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    </div>

    </div>

    <div id="generate_ccl_box" style="width:100%;display:none;flex-direction:column;position:fixed;top:0;left:0;height:100vh;overflow:scroll;background:rgba(0,0,0,0.3);z-index:99;">
        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4 loginouter-box">
                <div class="col text-end"><button class="btn btn-danger" style="width:fit-content;" onclick="document.getElementById('generate_ccl_box').style.display='none';">Close</button></div>
                <form class="details-box login-box" method="POST" action="{{ route('generate_client_care_letter') }}">
                    @csrf
                    <input type="hidden" name="local_time" class="localtime" />
                    <input type="hidden" name="client_id" value="{{ $client->id }}" />
                    <h3 class="mb-4 pt-3 text-center">Generate Client Care Letter</h3>

                    <div class="mb-3">
                        <label>Document Type</label>
                        <select name="letter_type" class="form-select" required>
                            <option value="">Select</option>
                            <option value="oisc_iaa">Client Care Letter (UK IAA/OISC)</option>
                            <option value="service_agreement">Service Agreement (Non-IAA)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Initial Consultation Date</label>
                        <input type="date" name="consultation_date" class="form-control" max="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Application Type</label>
                        <input type="text" name="application_type" class="form-control" minlength="3" maxlength="150" required>
                    </div>
                    <div class="mb-3">
                        <label>Application Name</label>
                        <input type="text" name="application_name" class="form-control" maxlength="150">
                    </div>
                    <div class="mb-3">
                        <label>Immigration Status</label>
                        <input type="text" name="immigration_status" class="form-control" maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label>Estimated Timeline</label>
                        <input type="text" name="estimated_timeline" class="form-control" minlength="2" maxlength="150" required>
                    </div>
                    <div class="mb-3">
                        <label>Key Dates</label>
                        <textarea name="key_dates" class="form-control" rows="2" maxlength="1000"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Merits of the case (%)</label>
                        <input type="number" name="merits_of_case" class="form-control" min="0" max="100" required>
                    </div>
                    <div class="mb-3">
                        <label>Merits / Case Notes</label>
                        <textarea name="case_notes" class="form-control" rows="2" maxlength="1500"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Professional Fee Details</label>
                        <textarea name="fee_details" class="form-control" rows="2" maxlength="1200"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Agreed Fixed Fee (£)</label>
                        <input type="text" name="fixed_fee" class="form-control" maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label>Home Office Fee (£)</label>
                        <input type="text" name="home_office_fee" class="form-control" maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label>IHS Fee (£)</label>
                        <input type="text" name="ihs_fee" class="form-control" maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label>Additional Costs</label>
                        <textarea name="additional_costs" class="form-control" rows="2" maxlength="1200"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>VAT Note</label>
                        <input type="text" name="vat_note" class="form-control" maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label>Client Instructions</label>
                        <textarea name="client_instructions" class="form-control" rows="3" maxlength="4000"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Advice Given</label>
                        <textarea name="advice_given" class="form-control" rows="3" maxlength="4000"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Work Agreed to be Done</label>
                        <textarea name="work_agreed" class="form-control" rows="3" maxlength="4000"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Line Manager Name</label>
                        <input type="text" name="line_manager_name" class="form-control" maxlength="150">
                    </div>
                    <div class="mb-3">
                        <label>Line Manager Phone</label>
                        <input type="text" name="line_manager_phone" class="form-control" maxlength="50">
                    </div>
                    <div class="mb-3">
                        <label>Line Manager Email</label>
                        <input type="email" name="line_manager_email" class="form-control" maxlength="150">
                    </div>
                    <div class="mb-3">
                        <label>Office Hours</label>
                        <input type="text" name="office_hours" class="form-control" maxlength="150">
                    </div>
                    <div class="mb-3">
                        <label>Complaint Handling Details</label>
                        <textarea name="complaint_handling_details" class="form-control" rows="3" maxlength="1500"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>IAA / OISC Registration Number</label>
                        <input type="text" name="oisc_registration_number" class="form-control" maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label>Authorisation Level</label>
                        <input type="text" name="authorisation_level" class="form-control" maxlength="150">
                    </div>
                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="allowResend" name="allow_resend">
                        <label class="form-check-label" for="allowResend">Re-send due to correction/mistake</label>
                    </div>
                    <div class="mb-3">
                        <label>Correction Note (required if re-sending)</label>
                        <textarea name="correction_note" class="form-control" rows="2" maxlength="500"></textarea>
                    </div>

                    <button type="submit" class="form-control btn btn-primary mb-4">Generate & Send</button>
                </form>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </div>

    <div id="update_box"
        style="width:100%;display: none;flex-direction: column;position: fixed;top: 0;left: 0;height: 100vh;overflow: scroll; background: rgba(0, 0, 0, 0.3);z-index:99;">

        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4 loginouter-box">
                <div class="col text-end"><button class="btn btn-danger" style="width:fit-content;" onclick="document.getElementById('update_box').style.display='none';">Close</button></div>
                <form class="details-box login-box" method="POST" action="{{ route('update_client') }}">
                    @csrf
                    <input type="hidden" name="local_time" class="localtime" />
                    <input type="hidden" name="id" value="{{ $client->id }}">
                    <input type="hidden" name="profile" value="profile">
                    <h3 class="mb-5 pt-3 text-center">Update Profile</h3>
                    <div class="log-img mb-5">
                        @if ($client->profile_img == '')
                            <img src="{{ asset('web_assets/images/loginimg.png') }}" width="60" height="60"
                                alt="">
                        @else
                            <img src="{{ asset('web_assets/users/client' . $client->id . '/' . $client->profile_img) }}"
                                width="60" height="60" alt="">
                        @endif
                    </div>
                    <div class="mb-4">
                        <input name="name" minlength="3" maxlength="100" value="{{ $client->name }}" required type="text" class="form-control"
                            id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Name">
                    </div>
                    <div class="mb-4">
                        <input name="phone" value="{{ $client->phone }}" required type="text" pattern="\d*" minlength="9" maxlength="12" class="form-control"
                            id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Phone">
                    </div>
                    <div class="mb-4">
                        <input name="email" minlength="3" maxlength="100" value="{{ $client->email }}" required type="email" class="form-control"
                            id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email">
                    </div>
                    <div class="mb-4">
                        <input name="alternate_no" value="{{ $client->alternate_no }}" type="text" pattern="\d*" minlength="9" maxlength="12"
                            class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                            placeholder="Alternate No.">
                    </div>
                    <div class="mb-4">
                        <select name="nationality" id="nationality" required class="form-select"
                            aria-label="Default select example">
                            <option selected value="">Select Nationality</option>
                            @foreach ($countries as $country)
                                <option {{ $client->nationality == $country->country_name ? 'selected' : '' }}
                                    value="{{ $country->id }}">{{ $country->country_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <input name="passport_no" minlength="6" maxlength="14" pattern="^[A-Z0-9]+$" value="{{ $client->passport_no }}" onkeyup="this.value = this.value.toUpperCase();" required type="text" class="form-control"
                            id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Passport No.">
                    </div>
                    <div class="mb-4">
                        <input name="dob" value="{{ $client->dob }}" required type="date" max="{{ date('Y-m-d') }}" class="form-control date"
                            id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Date of Birth">
                    </div>
                    <div class="mb-4">
                        <select name="country" id="country" required class="form-select"
                            aria-label="Default select example">
                            <option selected value="">Country</option>
                            @foreach ($countries as $country)
                                <option {{ $client->country == $country->country_name ? 'selected' : '' }}
                                    value="{{ $country->id }}">{{ $country->country_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <input name="address" minlength="3" maxlength="150" value="{{ $client->address }}" required type="text" class="form-control"
                            id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Address">
                    </div>
                    <div class="mb-4">
                        <select name="state" id="state" required class="form-select"
                            aria-label="Default select example">
                            @foreach ($states as $state)
                                    <option {{ $client->state == $state->name ? 'selected' : '' }}
                                        value="{{ $state->name }}">{{ $state->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <input type="text" minlength="3" maxlength="100" value="{{ $client->city }}" name="city" required class="form-control"
                            aria-label="Default select example" placeholder="City">
                    </div>
                    <div class="mb-4">
                        <input name="pincode" minlength="3" maxlength="10" style="text-transform:uppercase" value="{{ $client->pincode }}" required type="text"
                            class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                            placeholder="Postcode">
                    </div>
                    <button type="submit" class="form-control btn btn-primary mb-4">Save</button>
                    <!-- <a href="dashboard.html" class="btn btn-primary mb-4">Next</a> -->
                    <!-- <p class="text-center reg-logbtn">Already have an account! <a href="{{ route('login') }}" class="text-dark"> <strong>Login</strong></a></p> -->
                </form>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </div>
    <div id="add_docs" style="width:100%;display: none;flex-direction: column;position: fixed;top: 0;left: 0;height: 100vh;overflow: scroll; background: rgba(0, 0, 0, 0.3);z-index:99;">
        <button class="btn btn-danger" style="position:fixed;top:10px;right:20px;" onclick="document.getElementById('add_docs').style.display='none';">Close</button>
        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4 loginouter-box">
                <form class="details-box login-box" method="POST" action="{{ route('upload_client_doc') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="local_time" class="localtime" />
                <input type="hidden" name="client_id" value="{{ $client->id }}" />
                    <h3 class="mb-5 pt-3 text-center">Upload Document</h3>
                    <div class="log-img mb-5">
                    @if($client->profile_img == "")
                    <img src="{{ asset('web_assets/images/loginimg.png') }}" width="60" height="60" alt="">
                    @else
                        <img src="{{ asset('web_assets/users/client'.$client->id.'/'.$client->profile_img) }}" width="60" height="60" alt="">
                    @endif
                    </div>
                    <div class="mb-4">
                        <input name="doc_name" minlength="3" maxlength="100" required type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Document Name">
                    </div>
                    <div class="mb-4">
                        <input name="doc_file" required type="file" class="form-control" id="doc_file_upload" aria-describedby="emailHelp" accept=".jpg,.jpeg,.png,.pdf">
                        <label style="font-size:12px;">Select jpg, jpeg, png or pdf formats up to 4MB.</label>
                    </div>
                    <button type="submit" class="form-control btn btn-primary mb-4">Save</button>
                    <!-- <a href="dashboard.html" class="btn btn-primary mb-4">Next</a> -->
                    <!-- <p class="text-center reg-logbtn">Already have an account! <a href="{{ route('login') }}" class="text-dark"> <strong>Login</strong></a></p> -->
                </form>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </div>
    <div id="update_img_box"
        style="width:100%;display: none;flex-direction: column;position: fixed;top: 0;left: 0;height: 100vh;overflow: scroll; background: rgba(0, 0, 0, 0.3);justify-content: center;z-index:99;">

        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4 loginouter-box">
                <div class="col text-end"><button class="btn btn-danger" style="width:fit-content;" onclick="document.getElementById('update_img_box').style.display='none';">Close</button></div>
                <form class="details-box login-box" method="POST" action="{{ route('update_client') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="local_time" class="localtime" />
                    <input type="hidden" name="id" value="{{ $client->id }}">
                    <input type="hidden" name="profile_image" value="profile_image">
                    <h3 class="mb-5 pt-3 text-center">Update Profile Image</h3>
                    <div class="log-img mb-5">
                        @if ($client->profile_img == '')
                            <img src="{{ asset('web_assets/images/loginimg.png') }}" width="60" height="60"
                                alt="">
                        @else
                            <img style="border-radius: 50%;"
                                src="{{ asset('web_assets/users/client' . $client->id . '/' . $client->profile_img) }}"
                                width="60" height="60" alt="">
                        @endif
                    </div>

                    <div class="col d-flex justify-content-center align-items-center mb-5"
                        onclick="document.getElementById('select_pic').click();">
                        <div style="width:100%;height:200px;box-shadow: 0px 0px 5px 0px lightgrey;border-radius: 10px;justify-content: center;align-items: center;display: flex;position:relative;"
                            title="click to upload file">
                            <input id="select_pic" type="file" name="profile_img" style="display: none;" accept=".jpg,.jpeg,.png">
                            <p style="position:absolute;">Click to Upload File</p>
                            <img id="profile_pic_preview"
                                style="width: auto;height: auto;max-width: 100%;max-height: 100%;" src="">
                        </div>
                    </div>

                    <button type="submit" disabled="disabled" id="save_photo" class="form-control btn btn-primary mb-4">Save</button>
                    <!-- <a href="dashboard.html" class="btn btn-primary mb-4">Next</a> -->
                    <!-- <p class="text-center reg-logbtn">Already have an account! <a href="{{ route('login') }}" class="text-dark"> <strong>Login</strong></a></p> -->
                </form>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </div>
    <div id="update_job_box"
        style="width:100%;display: none;flex-direction: column;position: fixed;top: 0;left: 0;height: 100vh;overflow: scroll; background: rgba(0, 0, 0, 0.3);">
        <button class="btn btn-danger" style="position:fixed;top:10px;right:20px;"
            onclick="document.getElementById('update_job_box').style.display='none';">Close</button>
        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4 loginouter-box">
                <form class="details-box login-box" method="POST" action="{{ route('update_client') }}">
                    @csrf
                    <input type="hidden" name="local_time" class="localtime" />
                    <input type="hidden" name="id" value="{{ $client->id }}">
                    <input type="hidden" name="job" value="job">
                    <h3 class="mb-5 pt-3 text-center">Update Job Details</h3>
                    <div class="log-img mb-5">
                        @if ($client->profile_img == '')
                            <img src="{{ asset('web_assets/images/loginimg.png') }}" width="60" height="60"
                                alt="">
                        @else
                            <img src="{{ asset('web_assets/users/client' . $client->id . '/' . $client->profile_img) }}"
                                width="60" height="60" alt="">
                        @endif
                    </div>
                    <div class="mb-4">
                        <input name="job_id" value="{{ $client->job_id }}" required type="text"
                            class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                            placeholder="Name">
                    </div>
                    <div class="mb-4">
                        <input name="job_detail" value="{{ $client->job_detail }}" required type="text"
                            class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                            placeholder="Phone">
                    </div>
                    <div class="mb-4">
                        <input name="job_open_date" value="{{ $client->job_open_date }}" required type="date"
                            class="form-control date" id="exampleInputEmail1" aria-describedby="emailHelp"
                            placeholder="Email">
                    </div>
                    <div class="mb-4">
                        <input name="job_status" value="{{ $client->job_status }}" required type="text"
                            class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                            placeholder="Alternate No.">
                    </div>
                    <div class="mb-4">
                        <input name="job_completion_date" value="{{ $client->job_completion_date }}" type="date"
                            class="form-control date" id="exampleInputEmail1" aria-describedby="emailHelp"
                            placeholder="Age">
                    </div>
                    <button type="submit" class="form-control btn btn-primary mb-4">Save</button>
                    <!-- <a href="dashboard.html" class="btn btn-primary mb-4">Next</a> -->
                    <!-- <p class="text-center reg-logbtn">Already have an account! <a href="{{ route('login') }}" class="text-dark"> <strong>Login</strong></a></p> -->
                </form>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#select_pic").change(function() {
                const file = this.files[0];
                var filepath = $(this).val();
                var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.JPG|\.JPEG|\.PNG)$/i;
                if (!allowedExtensions.exec(filepath)) {
                    Swal.fire({
                        title: "Oops..",
                        icon:"info",
                        html: "Please select valid file format <br>( jpg, jpeg, png )"
                    });
                    $(this).val("");
                    return false;
                }
                const size = (this.files[0].size / 1024 / 1024).toFixed(2);
                if (size > 4) {
                    Swal.fire({
                        title: "Oops..",
                        icon:"info",
                        html: "Please select file upto 4MB"
                    });
                    $(this).val("");
                    return false;
                }
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        $("#profile_pic_preview").attr("src", event.target.result);
                    };
                    reader.readAsDataURL(file);
                }
                $("#save_photo").removeAttr('disabled');
            });
            $(document).on('change', '#doc_file_upload', function() {
                var filepath = $(this).val();
                var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pdf|\.JPG|\.JPEG|\.PNG|\.PDF)$/i;
                if (!allowedExtensions.exec(filepath)) {
                    Swal.fire({
                        title: "Oops..",
                        icon: "info",
                        html: "Please select valid file format <br>( jpg, jpeg, png or pdf )"
                    });
                    $(this).val("");
                    return false;
                }
                const size = (this.files[0].size / 1024 / 1024).toFixed(2);
                if (size > 4) {
                    Swal.fire({
                        title: "Oops..",
                        icon: "info",
                        html: "Please select file upto 4MB"
                    });
                    $(this).val("");
                    return false;
                }
            });

            $("#country").change(function() {
                var country = $(this).val();
                // console.log(counrty);
                $.ajax({
                    url: "{{ route('get_states') }}",
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        country: country,
                    },
                    cache: false,
                    success: function(data) {
                        console.log(data);
                        $("#state").html(data);
                    }
                });
            });
            $(document).on('submit', '#message_form', function(e){
                e.preventDefault();
                let formdata = new FormData(this);
                $.ajax({
                    url: "{{ route('send_message') }}",
                    method: "POST",
                    data: formdata,
                    contentType: false,
                    processData: false,
                    cache: false,
                    success: function(data){
                        $("#messages").load(location.href + " #messages>*", "");
                        $('#message_form').trigger('reset');
                        console.log(data);
                    }
                });
            });
            setInterval(() => {
                $("#messages").load(location.href + " #messages>*", "");
            }, 1000);
        });
    </script>


@if ($errors->has('doc_file'))
<script>
    Swal.fire({
        title: 'Oops..',
        icon: 'info',
        html: @json($errors->first('doc_file'))
    })
</script>
@endif

@if (session()->has('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Congratulations',
        text: 'Profile Updated Successfully.'
    })
</script>
@endif
@if (session()->has('uploaded'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Congratulations',
            text: 'Document Uploaded Successfully.'
        })
    </script>
@endif
@if (session()->has('updated'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Congratulations',
            text: 'Document Updated Successfully.'
        })
    </script>
@endif

@if (session()->has('ccl_sent'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Done',
        text: @json(session('ccl_sent'))
    })
</script>
@endif
@if (session()->has('ccl_exists'))
<script>
    Swal.fire({
        icon: 'info',
        title: 'Notice',
        text: @json(session('ccl_exists'))
    })
</script>
@endif
@if ($errors->has('correction_note'))
<script>
    Swal.fire({
        icon: 'info',
        title: 'Oops..',
        text: @json($errors->first('correction_note'))
    })
</script>
@endif

@if (session()->has('ccl_error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Email Failed',
        text: @json(session('ccl_error'))
    })
</script>
@endif
@if ($errors->any() && !$errors->has('correction_note'))
<script>
    Swal.fire({
        icon: 'info',
        title: 'Validation',
        text: @json($errors->first())
    })
</script>
@endif

@endsection()
