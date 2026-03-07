@extends('web.layout.main')

@section('main-section')
  <div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-4 loginouter-box">
            <form class="details-box login-box" method="POST" action="{{ route('update_user') }}">
              @csrf
              <input type="hidden" name="local_time" class="localtime" />
              <input type="hidden" name="moredetails" value="moredetails">
                <h3 class="mb-5 pt-3 text-center">Please provide few more details</h3>
                <div class="log-img mb-5">
                  <img src="{{ asset('web_assets/images/loginimg.png') }}" width="60" height="60" alt="">
                </div>
                <div class="mb-4">
                    <input name="organization" minlength="3" maxlength="100" required type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Organization Name">           
                  </div>
                  <div class="mb-4">
                    <input name="designation" minlength="3" maxlength="100" required type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Your designation">
                  </div>
                  <div class="mb-4">
                    <select name="employee_strength" required class="form-select" id="exampleInputEmail1" aria-describedby="emailHelp">
                      <option value="">Employee Strength</option>
                      <option value="1-10">1-10</option>
                      <option value="10-20">10-20</option>
                      <option value="20-50">20-50</option>
                      <option value="50-100">50-100</option>
                      <option value="Above 100">Above 100</option>
                    </select>
                  </div>
                  <div class="mb-4">
                    <input name="address_line" minlength="3" maxlength="150" required type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Address line">
                  </div>
                  <div class="mb-4">
                    <select name="country" id="country" required class="form-select" aria-label="Default select example">
                        <option value="">Country</option>
                        @foreach($countries as $country)
                        <option value="{{ $country->id }}">{{ $country->country_name }}</option>
                        @endforeach
                      </select>
                  </div>
                  <div class="mb-4">
                    <select name="state" id="state" required class="form-select" aria-label="Default select example">
                        <option selected value="">Select State/County</option>
                      </select>
                  </div>
                  <div class="mb-4">
                    <input type="text" name="city" minlength="3" maxlength="100" required class="form-control" aria-label="Default select example" placeholder="City/Town">
                  </div>
                  <div class="mb-4">
                    <input name="pincode" minlength="3" maxlength="10" style="text-transform:uppercase" required type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Postcode">
                  </div>
                  <div class="mb-4">
                    <select name="timezone" id="timezone" required class="form-select" aria-label="Default select example">
                        <option value="">Select Timezone</option>
                        @foreach($tzlist as $zone)
                        <option value="{{ $zone }}">{{ $zone }}</option>
                        @endforeach
                      </select>
                  </div>
                  <button type="submit" class="form-control btn btn-primary mb-4">Next</button>
                  <!-- <a href="dashboard.html" class="btn btn-primary mb-4">Next</a> -->
                  <!-- <p class="text-center reg-logbtn">Already have an account! <a href="{{ route('user_login') }}" class="text-dark"> <strong>Login</strong></a></p> -->
            </form>
        </div>
        <div class="col-lg-4"></div>
    </div>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
</script>
<script>
    $(document).ready(function(){
        $("#country").change(function(){
          var country = $(this).val();
          // console.log(counrty);
          $.ajax({
              url: "{{route('get_states')}}",
              method: 'POST',
              data: {
                  "_token": "{{ csrf_token() }}",
                  country: country,
              },
              cache:false,
              success: function(data){
                console.log(data);
                  $("#state").html(data);
              }
          });
          $.ajax({
              url: "{{route('get_timezone')}}",
              method: 'POST',
              data: {
                  "_token": "{{ csrf_token() }}",
                  country: country,
              },
              cache:false,
              success: function(data){
                console.log(data);
                  $("#timezone").html(data);
              }
          });
        });
      });
</script>

@endsection()
