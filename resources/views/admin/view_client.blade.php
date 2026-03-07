@extends('admin.layout.main')

@section('main-section')

        <div class="col-lg-10 userdash-client column-client">
            <h3>{{ $client->name }}</h3>
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
                            <label style="font-weight:550;">Subscriber Id</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ $client->subscriber_id }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">ClientID</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ $client->id }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">Name</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ $client->name }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">Phone Number</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ $client->phone }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">Email ID</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ $client->email }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">Nationality</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ $client->nationality }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">Passport No.</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ $client->passport_no }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">Date of Birth</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ date("d-m-Y", strtotime($client->dob)) }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">Alternate No.</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ $client->alternate_no }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">Address</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ $client->address }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">Country</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ $client->country }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">State/County</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ $client->state }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">City</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ $client->city }}
                        </div>
                        <div class="col-6 p-2 border">
                            <label style="font-weight:550;">Postcode</label>
                        </div>
                        <div class="col-6 p-2 border">
                            {{ $client->pincode }}
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
                            @if($client->profile_img != "")
                            <img src="{{ asset('web_assets/users/client'.$client->id.'/'.$client->profile_img) }}" width="200" height="200" alt="">
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
