<div class="col-lg-3 dashboardpage">
    <div class="dashboardprofile">
        <div class="profileimg">
            <img src="{{ asset('/web_assets/imgs/Ellipse 18.png') }}" alt="">
            <h5>{{ucfirst(Auth::user()->name)}}</h5>
              @if(Auth::user()->subs_expiry_date != NULL)
            <p>Membership due date at {{Auth::user()->subs_expiry_date}}</p>
            @endif
        </div>
        <div class="profilebtns">
            <div class="profilebtn {{($page == 'home') ? 'active' : ''}}">
                <a href="{{route('home')}}"><button>Dashboard</button></a>
            </div>
            <div class="profilebtn {{($page == 'userprofile') ? 'active' : ''}}">
                <a href="{{route('userprofile')}}"><button>Profile</button></a>
            </div>
            <div class="profilebtn {{($page == 'userdocument') ? 'active' : ''}}">
                <a href="{{route('userdocument')}}"><button>Documents</button></a>
            </div>
       
          <div class="profilebtn {{($page == 'dashnominee') ? 'active' : ''}}" >
                <a href="{{route('dashnominee')}}"><button>Nominee Access Management</button></a>
            </div>

            <div class="profilebtn {{($page == 'dashreward') ? 'active' : ''}}">
                <a href="{{route('dashreward')}}"><button>Rewards</button></a>
            </div>
            <div class="profilebtn {{($page == 'rewardstatus') ? 'active' : ''}}">
                <a href="{{route('dashgiftsstatus')}}"><button>Gifts Status</button></a>
            </div>
           <div class="profilebtn {{($page == 'dashmembership') ? 'active' : ''}}">
                <a href="{{route('dashmembership')}}"><button>Membership</button></a>
            </div>
        </div>
    </div>
</div>