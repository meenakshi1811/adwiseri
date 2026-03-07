@extends('admin.layout.main')

@section('main-section')

        <div class="col-lg-10 userdash-client column-client">
            <h3>{{ $vuser->name }}</h3>
            <div class="profile-detail">
                <div class="col-lg-7 profile-data" style="border-color: lightgrey;">
                    <!-- <div class="row">
                        <div class="col-11"></div>
                        <div class="col-1 editss">
                            <img style="cursor: pointer;" onclick="document.getElementById('update_box').style.display='flex';" src="{{ asset('web_assets/images/edit.png') }}"width="20" height="20" alt="">
                        </div>
                    </div> -->
                    <div class="row det-row">
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">Name</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ $vuser->name }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">Phone Number</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ $vuser->phone }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">Email ID</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ $vuser->email }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">Date of Birth</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ date("d-m-Y", strtotime($vuser->dob)) }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">Wallet Credit</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ $vuser->wallet }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">Referral</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ $vuser->referral }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">Category</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ $vuser->category }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">Sub Category</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ $vuser->sub_category }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">Organization</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ $vuser->organization }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">Designation</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ $vuser->designation }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">Employee Strength</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ $vuser->employee_strength }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">Address Line</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ $vuser->address_line }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">Country</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ $vuser->country }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">State/County</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ $vuser->state }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">City</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ $vuser->city }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">Postcode</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ $vuser->pincode }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">Subscription</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ $vuser->membership }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">Subscription Start Date</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ date("d-m-Y", strtotime($vuser->membership_start_date)) }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">Subscription Expiry</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ date("d-m-Y", strtotime($vuser->membership_expiry_date)) }}
                        </div>
                        
                    </div>
                </div>
                <div class="col-lg-4 profile-pic" style="border-color: lightgrey;">
                    <!-- <div class="row">
                        <div class="col-10"></div>
                        <div class="col-2">
                            <img onclick="document.getElementById('update_img_box').style.display='flex';" src="{{ asset('web_assets/images/edit.png') }}"width="20" height="20" alt="">
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="col-lg-7 profilepic-row">
                            @if($vuser->profile_img != "")
                            <img src="{{ asset('web_assets/users/user'.$vuser->id.'/'.$vuser->profile_img) }}" width="200" height="200" alt="">
                            @else
                            <img src="{{ asset('web_assets/images/profile.jpg') }}" width="200" height="200" alt="">
                            @endif
                        </div>
                        <div class="col-lg-5"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

  </div>

@endsection()
            