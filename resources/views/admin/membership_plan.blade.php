@extends('admin.layout.main')

@section('main-section')

        <div class="col-lg-10 userdash-client column-client">
            <h3 class="text-primary">Subscription - {{ $plan->plan_name }}</h3>
            <div class="profile-detail">
                <div class="col-lg-12 profile-data" style="border-color: lightgrey;">
                    <div class="row">
                        <div class="col editss text-end px-3">
                            <img class="mt-2 me-2" style="cursor: pointer;" onclick="document.getElementById('update_box').style.display='flex';" src="{{ asset('web_assets/images/edit.png') }}"width="20" height="20" alt="">
                        </div>
                    </div>
                    <div class="row det-row">
                        <div class="col">
                            <div class="row">
                                <div class="col-6 border p-2">
                                    <label style="font-weight:550;">Plan Name</label>
                                </div>
                                <div class="col-6 border p-2">
                                    {{ $plan->plan_name }}
                                </div>
                                <div class="col-6 border p-2">
                                    <label style="font-weight:550;">Data Limit</label>
                                </div>
                                <div class="col-6 border p-2">
                                    {{ $plan->data_limit }}
                                </div>
                                <div class="col-6 border p-2">
                                    <label style="font-weight:550;">Client Limit</label>
                                </div>
                                <div class="col-6 border p-2">
                                    {{ $plan->client_limit }}
                                </div>
                                <div class="col-6 border p-2">
                                    <label style="font-weight:550;">Reports</label>
                                </div>
                                <div class="col-6 border p-2">
                                    {{ $plan->reports }}
                                </div>
                                <div class="col-6 border p-2">
                                    <label style="font-weight:550;">Analytics</label>
                                </div>
                                <div class="col-6 border p-2">
                                    {{ $plan->analytics }}
                                </div>
                                <div class="col-6 border p-2">
                                    <label style="font-weight:550;">User License</label>
                                </div>
                                <div class="col-6 border p-2">
                                    {{ $plan->no_of_users }}
                                </div>
                                <div class="col-6 border p-2">
                                    <label style="font-weight:550;">No. of Branches</label>
                                </div>
                                <div class="col-6 border p-2">
                                    {{ $plan->no_of_branches }}
                                </div>
                                <div class="col-6 border p-2">
                                    <label style="font-weight:550;">Price (USD)</label>
                                </div>
                                <div class="col-6 border p-2">
                                    US ${{ $plan->price_per_year }}
                                </div>
                                <div class="col-6 border p-2">
                                    <label style="font-weight:550;">Validity</label>
                                </div>
                                <div class="col-6 border p-2">
                                    {{ $plan->validity }} Days
                                </div>
                                <div class="col-6 border p-2">
                                    <label style="font-weight:550;">Messages</label>
                                </div>
                                <div class="col-6 border p-2">
                                    {{ $plan->messaging }}
                                </div>
                                <div class="col-6 border p-2">
                                    <label style="font-weight:550;">Invoicing</label>
                                </div>
                                <div class="col-6 border p-2">
                                    {{ $plan->invoicing }}
                                </div>
                                <div class="col-6 border p-2">
                                    <label style="font-weight:550;">Multi Device Support</label>
                                </div>
                                <div class="col-6 border p-2">
                                    {{ $plan->multi_device_support }}
                                </div>
                                <div class="col-6 border p-2">
                                    <label style="font-weight:550;">Secure Environment</label>
                                </div>
                                <div class="col-6 border p-2">
                                    {{ $plan->secure_environment }}
                                </div>
                                <div class="col-6 border p-2">
                                    <label style="font-weight:550;">Multi Currency Support</label>
                                </div>
                                <div class="col-6 border p-2">
                                    {{ $plan->multi_currency_support }}
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-6">
                            <p>@if($vuser->name == "") --- @else {{ $vuser->name }} @endif</p>
                            <p>@if($vuser->phone == "") --- @else {{ $vuser->phone }} @endif</p>
                            <p>@if($vuser->email == "") --- @else {{ $vuser->email }} @endif</p>
                            <p>@if($vuser->category == "") --- @else {{ $vuser->category }} @endif</p>
                            <p>@if($vuser->sub_category == "") --- @else {{ $vuser->sub_category }} @endif</p>
                            <p>@if($vuser->organization == "") --- @else {{ $vuser->organization }} @endif</p>
                            <p>@if($vuser->designation == "") --- @else {{ $vuser->designation }} @endif</p>
                            <p>@if($vuser->employee_strength == "") --- @else {{ $vuser->employee_strength }} @endif</p>
                            <p>@if($vuser->country == "") --- @else {{ $vuser->country }} @endif</p>
                            <p>@if($vuser->state == "") --- @else {{ $vuser->state }} @endif</p>
                            <p>@if($vuser->city == "") --- @else {{ $vuser->city }} @endif</p>
                            <p>@if($vuser->pincode == "") --- @else {{ $vuser->pincode }} @endif</p>
                            
                        </div>  --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>

    <div id="update_box" style="width:100%;display: none;flex-direction: column;position: fixed;top: 0;left: 0;height: 100vh;overflow: scroll; background: rgba(0, 0, 0, 0.3);">
        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4 loginouter-box">
                <div class="col text-end"><button class="btn btn-danger" style="width:fit-content;" onclick="document.getElementById('update_box').style.display='none';">Close</button></div>
                <form class="details-box login-box" method="POST" action="{{ route('post_membership') }}">
                @csrf
                <input type="hidden" name="id" value="{{ $plan->id }}">
                    <h3 class="mb-5 pt-3 text-center">Update Plan</h3>
                    <div class="log-img mb-5">
                    @if($user->profile_img == "")
                    <img src="{{ asset('web_assets/images/loginimg.png') }}" width="60" height="60" alt="">
                    @else
                        <img src="{{ asset('web_assets/users/user'.$user->id.'/'.$user->profile_img) }}" width="60" height="60" alt="">
                    @endif
                    </div>
                    <div class="mb-4">
                        <input name="plan_name" minlength="3" maxlength="100" value="{{ $plan->plan_name }}" required type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Plan Name">           
                    </div>
                    <div class="mb-4">
                        <input name="data_limit" minlength="1" maxlength="100" value="{{ $plan->data_limit }}" required type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Data Limit">           
                    </div>
                    <div class="mb-4">
                        <input name="client_limit" minlength="1" maxlength="100" value="{{ $plan->client_limit }}" required type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Client Limit">           
                    </div>
                    <div class="mb-4">
                        <input name="reports" minlength="1" maxlength="100" value="{{ $plan->reports }}" required type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Reports">           
                    </div>
                    <div class="mb-4">
                        <select name="analytics" required class="form-control form-select" id="exampleInputEmail1" aria-describedby="emailHelp">
                            <option value="">Select Analytics</option>
                            <option {{ ($plan->analytics == "Yes") ? 'selected' : '' }} value="Yes">Yes</option>
                            <option {{ ($plan->analytics == "No") ? 'selected' : '' }} value="No">No</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <input name="no_of_users" minlength="1" maxlength="100" value="{{ $plan->no_of_users }}" required type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="User License">           
                    </div>
                    <div class="mb-4">
                        <input name="no_of_branches" minlength="1" maxlength="100" value="{{ $plan->no_of_branches }}" required type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="No. of Branches">           
                    </div>
                    <div class="mb-4">
                        <input name="price_per_year" min="0" max="99999999" value="{{ $plan->price_per_year }}" required type="number" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Price / Year(USD)">           
                    </div>
                    <div class="mb-4">
                        <input name="validity" value="{{ $plan->validity }}" required type="number" max="365" min="30" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Validity(Days)">           
                    </div>
                    <div class="mb-4">
                        <input name="messaging" minlength="2" maxlength="150" value="{{ $plan->messaging }}" required type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Messages">           
                    </div>
                    <div class="mb-4">
                        <select name="invoicing" required class="form-control form-select" id="exampleInputEmail1" aria-describedby="emailHelp">
                            <option value="">Select Invoicing</option>
                            <option {{ ($plan->invoicing == "Yes") ? 'selected' : '' }} value="Yes">Yes</option>
                            <option {{ ($plan->invoicing == "No") ? 'selected' : '' }} value="No">No</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <select name="multi_device_support" required class="form-control form-select" id="exampleInputEmail1" aria-describedby="emailHelp">
                            <option value="">Select Multi Device Support</option>
                            <option {{ ($plan->multi_device_support == "Yes") ? 'selected' : '' }} value="Yes">Yes</option>
                            <option {{ ($plan->multi_device_support == "No") ? 'selected' : '' }} value="No">No</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <select name="secure_environment" required class="form-control form-select" id="exampleInputEmail1" aria-describedby="emailHelp">
                            <option value="">Select Secure Environment</option>
                            <option {{ ($plan->secure_environment == "Yes") ? 'selected' : '' }} value="Yes">Yes</option>
                            <option {{ ($plan->secure_environment == "No") ? 'selected' : '' }} value="No">No</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <select name="multi_currency_support" required class="form-control form-select" id="exampleInputEmail1" aria-describedby="emailHelp">
                            <option value="">Select Multi Currency Support</option>
                            <option {{ ($plan->multi_currency_support == "Yes") ? 'selected' : '' }} value="Yes">Yes</option>
                            <option {{ ($plan->multi_currency_support == "No") ? 'selected' : '' }} value="No">No</option>
                        </select>
                    </div>
                    <button type="submit" class="form-control btn btn-primary mb-4">Save</button>
                    <!-- <a href="dashboard.html" class="btn btn-primary mb-4">Next</a> -->
                </form>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </div>
    <div id="update_img_box" style="width:100%;display: none;flex-direction: column;position: fixed;top: 0;left: 0;height: 100vh;overflow: scroll; background: rgba(0, 0, 0, 0.3);justify-content: center;">
        <button class="btn btn-danger" style="position:fixed;top:10px;right:20px;" onclick="document.getElementById('update_img_box').style.display='none';">Close</button>
        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4 loginouter-box">
                <form class="details-box login-box" method="POST" action="{{ route('update_user') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="profile_image" value="profile_image">
                    <h3 class="mb-5 pt-3 text-center">Update Profile Image</h3>
                    <div class="log-img mb-5">
                    @if($user->profile_img == "")
                    <img src="{{ asset('web_assets/images/loginimg.png') }}" width="60" height="60" alt="">
                    @else
                        <img style="border-radius: 50%;" src="{{ asset('web_assets/users/user'.$user->id.'/'.$user->profile_img) }}" width="60" height="60" alt="">
                    @endif
                    </div>

                    <div class="col d-flex justify-content-center align-items-center mb-5" onclick="document.getElementById('select_pic').click();">
                        <div style="width:100%;height:200px;box-shadow: 0px 0px 5px 0px lightgrey;border-radius: 10px;justify-content: center;align-items: center;display: flex;position:relative;" title="click to upload file">
                            <input id="select_pic" type="file" name="profile_img" style="display: none;">
                            <p style="position:absolute;">Click to Upload File</p>
                            <img id="profile_pic_preview" style="width: auto;height: auto;max-width: 100%;max-height: 100%;" src="">
                        </div>
                    </div>
                    
                    <button type="submit" class="form-control btn btn-primary mb-4">Save</button>
                    <!-- <a href="dashboard.html" class="btn btn-primary mb-4">Next</a> -->
                </form>
            </div>
            <div class="col-lg-4"></div>
        </div>
    </div>
@if(session()->has('success'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Congratulations',
        text: 'Membership Plan Updated Successfully.'
      })
    </script>
  
  @endif
@endsection()
            