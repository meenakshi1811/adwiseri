@extends('web.layout.main')

@section('main-section')

          <div class="col-lg-7">
            <div class="data-box">
              <div class="row m-0" style="width:100%;">
                <div class="col-6 col-md-3 m-0 p-1">
                  <div class="client-box col-12 m-0" style="background-color: #ee5e85; border-radius: 10px;color: #ffffff;">
                      {{-- <img src="{{ asset('web_assets/images/clientdta.png') }}" width="50" height="50" alt=""> --}}
                      {{-- <i style="display:flex;align-items:center;justify-content:center;font-size: 32px;border-radius:50%;width:50px;height:50px;text-align:center;" class="fa fa-user text-info p-1"></i> --}}
                      <i style="font-size: 30px; width: 50px; height: 50px; background: #ffffff; border-radius: 50%;"
                      class="fa fa-user text-info p-1 d-flex text-center align-items-center justify-content-center"></i>
                      <h4>{{ count($clients) }}</h4>
                      <p style="font-weight: bolder!important;">@if(count($clients) > 1)Clients @else Client @endif</p>
                  </div>
                </div>
                <div class="col-6 col-md-3 m-0 p-1">
                    <div class="client-box col-12 m-0" style="background-color: #49bd27; border-radius: 10px;color:#ffffff;">
                        {{-- <img src="{{ asset('web_assets/images/clientdta.png') }}" width="50" height="50" alt=""> --}}
                        <i style="font-size: 30px;width:50px;height:50px; background:#ffffff;border-radius:50%;"
                         class="fa fa-list text-info p-1 d-flex text-center align-items-center justify-content-center"></i>
                        <!--<i style="display:flex;align-items:center;justify-content:center;font-size: 32px;border:2px solid rgb(118, 210, 247);border-radius:50%;width:50px;height:50px;text-align:center;" class="fa fa-user text-info p-1"></i>-->
                        <h4>{{ count($applications) }}</h4>
                        <p style="font-weight: bolder!important;">@if(count($applications) > 1)Applications @else Application @endif</p>
                    </div>
                  </div>
                <div class="col-6 col-md-3 m-0 p-1" >
                  <div class="client-box col-12 m-0" style="background-color: #337cff; border-radius: 10px;color: #ffffff;">
                      {{-- <img src="{{ asset('web_assets/images/clientdta.png') }}" width="50" height="50" alt=""> --}}
                      {{-- <i style="display:flex;align-items:center;background:#ffffff;justify-content:center;font-size: 32px;border:border-radius:50%;width:50px;height:50px;text-align:center;" class="fa fa-users text-info p-1"></i> --}}
                      <i style="font-size: 30px; width: 50px; height: 50px; background: #ffffff; border-radius: 50%;"
                      class="fa fa-users text-info p-1 d-flex text-center align-items-center justify-content-center"></i>
                      <h4>{{ count($users) }}</h4>
                      <p style="font-weight: bolder!important;">@if(count($users) > 1) Users @else User @endif</p>
                  </div>
                </div>
                <div class="col-6 col-md-3 m-0 p-1">
                    <div class="client-box col-12 m-0"  style="background-color: #725a11; border-radius: 10px;color: #ffffff;">
                        {{-- <img src="{{ asset('web_assets/images/clientdta.png') }}" width="50" height="50" alt=""> --}}
                        <i style="font-size: 30px;width:50px;height:50px; background: #ffffff;border-radius:50%;" class="fa fa-file-invoice text-info p-1 d-flex text-center align-items-center justify-content-center"></i>
                        <h4 style="font-weight: bolder!important;font-size:18px;margin-top:4px">AR : &nbsp; {{ $invoiceARCount }}</h4>
                        <h4 style="font-weight: bolder!important;font-size:18px;margin-top:4px">AP : &nbsp; {{ $invoiceAPCount }}</h4>
                        <p style="font-weight: bolder!important;">Invoices</p>
                    </div>
                  </div>

                  <div class="col-6 col-md-3 m-0 p-1">
                    <div class="client-box col-12 m-0" style="background-color: #afa94c; border-radius: 10px;color: #ffffff;">
                        <i style="font-size: 30px;width:50px;height:50px; background: #ffffff;border-radius:50%;" class="fa fa-credit-card text-info p-1 d-flex text-center align-items-center justify-content-center"></i>
                        <!--<i style="display:flex;align-items:center;justify-content:center;font-size: 36px;border:2px solid rgb(118, 210, 247);border-radius:50%;width:50px;height:50px;text-align:center;" class="fa fa-dollar text-info p-1"></i>-->
                        <!-- Display AP value -->
                        <h4 style="font-weight: bolder!important;font-size:18px;margin-top:4px">AR : &nbsp; {{ $totalPaymentsAR }}</h4>
                        <h4 style="font-weight: bolder!important;font-size:18px;margin-top:4px">AP : &nbsp; {{ $totalPayments }}</h4>
                        <p style="font-weight: bolder!important;">Payments</p>
                    </div>
                  </div>

                <div class="col-6 col-md-3 m-0 p-1">
                  <div class="client-box col-12 m-0" style="background-color: #a56911fc; border-radius: 10px;color: #ffffff;">
                      <i style="font-size: 30px;width:50px;height:50px; background: #ffffff;border-radius:50%;" class="fa fa-handshake text-info p-1 d-flex text-center align-items-center justify-content-center"></i>
                      <!--<i style="display:flex;align-items:center;justify-content:center;font-size: 36px;border:2px solid rgb(118, 210, 247);border-radius:50%;width:50px;height:50px;text-align:center;" class="fa fa-dollar text-info p-1"></i>-->
                      <h4>{{ count($referrals) }}</h4>
                      <p style="font-weight: bolder!important;">Referrals</p>
                  </div>
                </div>
                <div class="col-6 col-md-3 m-0 p-1">
                    <div class="client-box col-12 m-0" style="background-color: #9C27B0; border-radius: 10px;color: #ffffff;">
                        {{-- <i style="display:flex;align-items:center;justify-content:center;font-size: 36px;border:2px solid rgb(118, 210, 247);border-radius:50%;width:50px;height:50px;text-align:center;" class="fa fa-dollar text-info p-1"></i> --}}
                        <i style="font-size: 30px; width: 50px; height: 50px; background: #ffffff; border-radius: 50%;"
                        class="fa fa-dollar text-info p-1 d-flex text-center align-items-center justify-content-center"></i>
                        <h4>${{ $user->wallet }}</h4>
                        <p style="font-weight: bolder!important;">Wallet (USD)</p>
                    </div>
                  </div>
                  <div class="col-6 col-md-3 m-0 p-1">
                    <div class="client-box col-12 m-0" style="background-color: #185487; border-radius: 10px;color: #ffffff;">
                        {{-- <i style="display:flex;align-items:center;justify-content:center;font-size: 36px;border:2px solid rgb(118, 210, 247);border-radius:50%;width:50px;height:50px;text-align:center;" class="fa fa-calendar text-info p-1"></i> --}}
                        <i style="font-size: 30px; width: 50px; height: 50px; background: #ffffff; border-radius: 50%;"
                        class="fa fa-calendar text-info p-1 d-flex text-center align-items-center justify-content-center"></i>
                        <h4>{{count($meetings) }}</h4>
                        <p style="font-weight: bolder!important;">Meeting Notes</p>
                    </div>
                  </div>
              </div>
            </div>
            <div class="row m-0 p-0">
              {{-- <div class="col-6 bg-white p-3 border" style="height: fit-content;">
                <h5 class="text-center">Users</h5>
                <div id="userChart" style="width: 100%; height: 300px;"></div>
              </div> --}}
              <div class="col-md-6 rounded-5 bg-white p-3 border" style="height: fit-content;">
                <h5 class="text-center fw-bold">Clients</h5>
                <div id="clientChart" style="width: 100%; height: 300px;"></div>
              </div>
              <div class="col-md-6 rounded-5 bg-white p-3  border" style="height: fit-content;">
                <h5 class="text-center fw-bold">Applications</h5>
                <div id="countryChart" style="width: 100%; height: 300px;"></div>
              </div>
              <div class="col-md-6 rounded-5  bg-white p-3 border" style="height: fit-content;">
                <h5 class="text-center fw-bold">Users</h5>
                <div id="assignmentChart" style="width: 100%; height: 300px;"></div>
              </div>
              <div class="col-md-6 rounded-5 bg-white p-3 border" style="height: fit-content;">
                <h5 class="text-center fw-bold">Payments</h5>
                <div id="paymentChart" style="width: 100%; height: 300px;"></div>
              </div>
            </div>

        </div>
        <div class="col-lg-3 activity-box">
          <div class="activebox">
            <h4 style="font-size:20px!important;font-weight:800!important">Activity</h4>
          </div>
          <div class="doc-files">
            @foreach($activities as $activity)
            <div class="p-docbox d-flex">
                <img src="{{ asset('web_assets/images/'.$activity->activity_icon) }}" width="25" height="25" alt="">
                <p>{{ $activity->activity_name }} <br> <span>{{ date("D M d Y H:i:s",strtotime($activity->created_at))}}</span> </p>
                {{-- <i class="fa-regular fa-circle-check float-right"></i>   --}}
            </div>
            @endforeach

          </div>
        </div>
    </div>

  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script>

    function colorizer(){
      const characters = 'ABCDEF0123456789';
      let result = '#';
      const charactersLength = characters.length;
      for(let i = 0; i < 6; i++) {
          result += characters.charAt(Math.floor(Math.random() * charactersLength));
      }
      console.log(result);
      return result;
    }
    anychart.onDocumentReady(function() {

      // set the data
      var data = [
          {x: "Sub", value: 51},
          {x: "BM", value: 30},
          {x: "Cons", value: 35},
          {x: "SS", value: 45}
      ];

      // create the chart
      var chart = anychart.pie();

      // set the chart title
      chart.title("Users By Work Level");

      // add the data
      chart.data(data);

      chart.innerRadius("30%");
      chart.background().fill("#F2F2F2"); // Change to any color you prefer

      // display the chart in the container
      chart.container('userChart');
      chart.draw();

      });


      // anychart.onDocumentReady(function() {

      //   // set the data
      //   var data = [
      //       {x: "Net Banking", value: 74},
      //       {x: "Credit Card", value: 58},
      //       {x: "Debit Card", value: 62},
      //       {x: "UPI", value: 105}
      //   ];

      //   // create the chart
      //   var chart = anychart.pie();

      //   // set the chart title
      //   chart.title("Payments By Payment Mode");

      //   // add the data
      //   chart.data(data);

      //   chart.innerRadius("30%");
       //chart.background().fill("#F2F2F2"); // Change to any color you prefer

      //   // display the chart in the container
      //   chart.container('paymentChart');
      //   chart.draw();

      //   });

      $(document).ready(() => {
        $(".anychart-credits").css('display','none');
      });

      anychart.onDocumentReady(function() {

          // set the data
          var data = [
            @foreach($total_countries as $key => $contry)
              @if($contry != 0)
              {x: `{!! htmlspecialchars_decode($key) !!}`, value: {{ $contry }} },

              @endif
            @endforeach
          ];

          // create the chart
          var chart = anychart.pie();

          // set the chart title
          chart.title("Applications by  Category");

          // add the data
          chart.data(data);

          chart.innerRadius("30%");
          chart.background().fill("#F2F2F2"); // Change to any color you prefer

          // display the chart in the container
          chart.container('countryChart');
          chart.draw();

          });

        $(document).ready(() => {
          $(".anychart-credits").css('display','none');
        });


      anychart.onDocumentReady(function() {

          // set the data
          var data = [
            @foreach($total_clients as $key => $clint)
              @if($clint != 0)
              {x: "{{ $key }}", value: {{ $clint }}, fill: (colorizer())},
              @endif
            @endforeach
          ];

          // create the chart
          var chart = anychart.pie();

          // set the chart title
          chart.title("Clients by Country");

          // add the data
          chart.data(data);

          chart.innerRadius("30%");
          chart.background().fill("#F2F2F2"); // Change to any color you prefer

          // display the chart in the container
          chart.container('clientChart');
          chart.draw();

          });


        anychart.onDocumentReady(function() {

            // set the data
            var data = [
                @foreach ($total_payments as $key => $paymentMode)
            {
                x: "{{ $key }}", // Label with payment mode, transactions, and amount
                value: {{ $paymentMode['total_transactions'] }}, // Use total_transactions to size the slices
                extra: { total_amount: {{ $paymentMode['total_amount'] }} }, // Attach extra data for tooltips
                fill: colorizer(), // Generate random color for each slice
            },
            @endforeach
            ];

            // create the chart
            var chart = anychart.pie();

            // set the chart title
            chart.title("Payments by Mode");

            // add the data
            chart.data(data);

            chart.innerRadius("30%");
            chart.background().fill("#F2F2F2"); // Change to any color you prefer

            // display the chart in the container
            chart.container('paymentChart');
            chart.draw();

            });

          anychart.onDocumentReady(function() {

          // set the data
          var data = [
            @foreach($total_assignments as $key => $clint)
              @if($clint != 0)
              {x: "{{ $key }}", value: {{ $clint }}, fill: (colorizer())},
              @endif
            @endforeach
          ];

          // create the chart
          var chart = anychart.pie();

          // set the chart title
          chart.title("Users by Application Assigned");

          // add the data
          chart.data(data);

          chart.innerRadius("30%");
          chart.background().fill("#F2F2F2"); // Change to any color you prefer

          // display the chart in the container
          chart.container('assignmentChart');
          chart.draw();

          });

        $(document).ready(() => {
          $(".anychart-credits").css('display','none');
        });
  </script>

@endsection()
