@extends('admin.layout.main')

@section('main-section')
    <div class="col-lg-7">
        <div class="data-box">
            <div class="col-12 row m-0">
                <!-- Subscribers -->
                <div class="col-md-3 p-1">
                    <div class="client-box" style="background-color: #695EEE; border-radius: 10px; color: #ffffff;">
                        <i style="font-size: 30px; width: 50px; height: 50px; background: #ffffff; border-radius: 50%;"
                            class="fa fa-users-cog text-info p-1 d-flex text-center align-items-center justify-content-center"></i>
                        <h4>{{ count($subscribers) }}</h4>
                        <p style="font-weight: bolder!important;">
                            @if (count($subscribers) > 1)
                                Subscribers
                            @else
                                Subscriber
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Clients -->
                <div class="col-md-3 p-1">
                    <div class="client-box" style="background-color: #FF5733; border-radius: 10px; color: #ffffff;">
                        <i style="font-size: 30px; width: 50px; height: 50px; background: #ffffff; border-radius: 50%;"
                            class="fa fa-user text-info p-1 d-flex text-center align-items-center justify-content-center"></i>
                        <h4>{{ count($clients) }}</h4>
                        <p style="font-weight: bolder!important;">
                            @if (count($clients) > 1)
                                Clients
                            @else
                                Client
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Applications -->
                <div class="col-md-3 p-1">
                    <div class="client-box" style="background-color: #33C6FF; border-radius: 10px; color: #ffffff;">
                        <i style="font-size: 30px; width: 50px; height: 50px; background: #ffffff; border-radius: 50%;"
                            class="fa fa-list text-info p-1 d-flex text-center align-items-center justify-content-center"></i>
                        <h4>{{ count($applications) }}</h4>
                        <p style="font-weight: bolder!important;">
                            @if (count($applications) > 1)
                                Applications
                            @else
                                Application
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Users -->
                <div class="col-md-3 p-1">
                    <div class="client-box" style="background-color: #b0a727; border-radius: 10px; color: #ffffff;">
                        <i style="font-size: 30px; width: 50px; height: 50px; background: #ffffff; border-radius: 50%;"
                            class="fa fa-users text-info p-1 d-flex text-center align-items-center justify-content-center"></i>
                        <h4>{{ count($users) }}</h4>
                        <p style="font-weight: bolder!important;">
                            @if (count($users) > 1)
                                Users
                            @else
                                User
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Invoices -->
                <div class="col-md-3 p-1">
                    <div class="client-box" style="background-color: #ff07fa; border-radius: 10px; color: #ffffff;">
                        <i style="font-size: 30px; width: 50px; height: 50px; background: #ffffff; border-radius: 50%;"
                            class="fa fa-dollar text-info p-1 d-flex text-center align-items-center justify-content-center"></i>
                        <h4>{{ count($invoices) }}</h4>
                        <p style="font-weight: bolder!important;">
                            @if (count($invoices) > 1)
                                Invoices
                            @else
                                Invoice
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Referrals (Again) -->
                <div class="col-md-3 p-1">
                    <div class="client-box" style="background-color: #4CAF50; border-radius: 10px; color: #ffffff;">
                        <i style="font-size: 30px; width: 50px; height: 50px; background: #ffffff; border-radius: 50%;"
                            class="fa fa-handshake text-info p-1 d-flex text-center align-items-center justify-content-center"></i>
                        <h4>{{ count($refferals) }}</h4>
                        <p style="font-weight: bolder!important;">
                            @if (count($refferals) > 1)
                                Referrals
                            @else
                                Referral
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Messages -->
                <div class="col-md-3 p-1">
                    <div class="client-box" style="background-color: #FF9800; border-radius: 10px; color: #ffffff;">
                        <i style="font-size: 30px; width: 50px; height: 50px; background: #ffffff; border-radius: 50%;"
                            class="fa fa-envelope text-info p-1 d-flex text-center align-items-center justify-content-center"></i>
                            @php 
                                        echo '<h4 style="font-size:13px">Sent: '. count($messagings->where('admin_id', '!=', null)) .'</h4>';

                                        echo '<h4 style="font-size:13px">Received : '. count($messagings->where('admin_id', '=', null)) .'</h4>';
                                 
                            @endphp
                        <p style="font-weight: bolder!important;">
                            @if (count($messagings) > 1)
                                Messages
                            @else
                                Message
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Tickets -->
                <div class="col-md-3 p-1">
                    <div class="client-box" style="background-color:#f47803; border-radius: 10px; color: #ffffff;">
                        <i style="font-size: 28px; width: 50px; height: 50px; background: #ffffff; border-radius: 50%;"
                            class="fa fa-flag text-info p-1 d-flex text-center align-items-center justify-content-center"></i>
                            @php 
                            $i = 0;
                                foreach ($ticketsStatus as $data){
                                    if($i==0){
                                        echo '<h4 style="font-size:13px">Open: '. $data .'</h4>';                                        
                                    } elseif($i==1){
                                        echo '<h4 style="font-size:13px">Closed: '. $data .'</h4>';
                                    }
                                    $i++;
                                }
                            @endphp
                        <p style="font-weight: bolder!important;">Tickets</p>
                    </div>
                </div>
            </div>


        </div>
        <div class="row m-0 p-0">
            <div class="col-6 rounded-5 bg-white p-3  border" style="height: fit-content;">
                <h5 class="text-center fw-bold">Subscribers </h5>
                <div id="subscriberChart" style="width: 100%; height: 300px;"></div>
            </div>
            <div class="col-6 rounded-5 bg-white p-3 border" style="height: fit-content;">
                <h5 class="text-center fw-bold">Clients</h5>
                <div id="clientChart" style="width: 100%; height: 300px;"></div>
            </div>
            <div class="col-6 rounded-5 bg-white p-3 border" style="height: fit-content;">
                <h5 class="text-center fw-bold">Applications</h5>
                <div id="applicationChart" style="width: 100%; height: 300px;"></div>
            </div>
            <div class="col-6 rounded-5 bg-white p-3 border" style="height: fit-content;">
                <h5 class="text-center fw-bold">Users</h5>
                <div id="userChart" style="width: 100%; height: 300px;"></div>
            </div>
            <div class="col-6 rounded-5 bg-white p-3 border" style="height: fit-content;">
                <h5 class="text-center fw-bold">Payments</h5>
                <div id="paymentChart" style="width: 100%; height: 300px;"></div>
            </div>
            <div class="col-6 rounded-5 bg-white p-3 border" style="height: fit-content;">
                <h5 class="text-center fw-bold">Communications</h5>
                <div id="communicationChart" style="width: 100%; height: 300px;"></div>
            </div>
            <div class="col-6 rounded-5 bg-white p-3 border" style="height: fit-content;">
                <h5 class="text-center fw-bold">Support</h5>
                <div id="ticketChart" style="width: 100%; height: 300px;"></div>
            </div>
            <div class="col-6 rounded-5 bg-white p-3 border" style="height: fit-content;">
                <h5 class="text-center fw-bold">Activities</h5>
                <div id="activityChart" style="width: 100%; height: 300px;"></div>
            </div>
        </div>
        {{-- <div class="pie-chartbox">
              <div class="pie-box">
                <h5 class="text-center">Subscribers</h5>
                <div id="subscriberChart" style="width: 100%; height: 100%"></div>
              </div>
              <div class="pie-box">
                <h5 class="text-center">Payment</h5>
                <div id="paymentChart" style="width: 100%; height: 100%"></div>
              </div>
              <div class="pie-box">
                <h5 class="text-center">Documents uploaded monthly, yearly</h5>
                <button class="mt-5">Document Upload</button> <br>
                <img src="{{ asset('web_assets/images/pie.png') }}" width="170" height="170" alt="">
                <ul>
                  <li class="doc"><span></span> Doc</li>
                  <li class="png"><span></span> Png</li>
                  <li class="jpg"><span></span> Jpg</li>
                  <li class="pdf"><span></span> Pdf</li>
                </ul>
              </div>
            </div> --}}
    </div>
    <div class="col-lg-3 activity-box">
        <div class="activebox">
            <h4>Activity
                {{-- <p class="text-end" style=" margin-top: -20px;">
                    <i class="fas fa-bell"></i>
                </p> --}}
            </h4>


        </div>
        <div class="doc-files mb-3">
            @foreach ($activities as $activity)
                <div class="p-docbox d-flex">
                    <img src="{{ asset('web_assets/images/' . $activity->activity_icon) }}" width="25" height="25"
                        alt="">
                    <p style="font-weight: bolder!important;">{{ $activity->activity_name }} <br>
                        <span>{{ date('D M d Y H:i:s', strtotime($activity->created_at)) }}</span> </p>
                    {{-- <i class="fa-regular fa-circle-check float-right"></i>   --}}
                </div>
            @endforeach
            <a href="{{ route('activity_log') }}">Read More...</a>
        </div>
    </div>
    </div>

    </div>
@endsection()
@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
        var all_colors = [];

        function colorizer() {
            const characters = 'ABCDEF0123456789';
            let result = '#';
            const charactersLength = characters.length;
            for (let i = 0; i < 6; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            // console.log(result);
            return result;
        }

        anychart.onDocumentReady(function() {

            // set the data
            var data = [
                @foreach ($total_subscribers as $key => $subs)
                    {
                        x: "{{ $key }}",
                        value: {{ $subs }},
                        fill: (colorizer())
                    },
                @endforeach
            ];

            // create the chart
            var chart = anychart.pie();

            // set the chart title
            chart.title("Subscribers By Plan");

            // add the data
            chart.data(data);

            chart.innerRadius("30%");
            chart.background().fill("#F2F2F2"); // Change to any color you prefer

            // display the chart in the container
            chart.container('subscriberChart');
            chart.draw();

        });

        anychart.onDocumentReady(function() {
            var data = [
                @php
                    $colors = [];
                    foreach ($total_clients as $key => $subs) {
                        // Generate a unique random RGB color
                        do {
                            $randomColor = sprintf(
                                'rgb(%d,%d,%d)',
                                rand(0, 255), // Red
                                rand(0, 255), // Green
                                rand(0, 255), // Blue
                            );
                        } while (in_array($randomColor, $colors)); // Ensure no duplicate colors

                        $colors[] = $randomColor; // Store used colors
                        echo "{ x: \"$key\", value: $subs, fill: \"$randomColor\" },";
                    }
                @endphp
            ];

            // Create the chart
            var chart = anychart.pie();

            // Set the chart title
            chart.title("Clients By Country");

            // Add the data with unique colors
            chart.data(data);

            // Set inner radius for a donut chart
            chart.innerRadius("30%");
            chart.background().fill("#F2F2F2"); // Change to any color you prefer


            // Display the chart in the container
            chart.container('clientChart');
            chart.draw();
        });

        anychart.onDocumentReady(function() {

            //   // set the data
            var data = [
                @php
                    $colors = [];
                    foreach ($total_applications as $key => $subs) {
                        // Generate a unique random RGB color
                        do {
                            $randomColor = sprintf(
                                'rgb(%d,%d,%d)',
                                rand(0, 255), // Red
                                rand(0, 255), // Green
                                rand(0, 255), // Blue
                            );
                        } while (in_array($randomColor, $colors)); // Ensure no duplicate colors

                        $colors[] = $randomColor; // Store used colors
                        echo "{ x: \"$key\", value: $subs, fill: \"$randomColor\" },";
                    }
                @endphp
            ];

            // Create the chart
            var chart = anychart.pie();

            // Set the chart title
            chart.title("Applications By Category");

            // Add the data with unique colors
            chart.data(data);

            // Set inner radius for a donut chart
            chart.innerRadius("30%");
            chart.background().fill("#F2F2F2"); // Change to any color you prefer

            // Display the chart in the container
            chart.container('applicationChart');
            chart.draw();

        });

        anychart.onDocumentReady(function() {

            // set the data
            var data = [
                @foreach ($total_tickets as $key => $subs)
                    {
                        x: "{{ $key }}",
                        value: {{ $subs }},
                        fill: (colorizer())
                    },
                @endforeach
            ];

            // create the chart
            var chart = anychart.pie();

            // set the chart title
            chart.title("Tickets By Status");

            // add the data
            chart.data(data);

            chart.innerRadius("30%");
            chart.background().fill("#F2F2F2"); // Change to any color you prefer

            // display the chart in the container
            chart.container('ticketChart');
            chart.draw();

        });


        anychart.onDocumentReady(function() {

            // set the data
            var data = [
                @foreach ($total_users as $key => $usr)
                    {
                        x: "{{ $key }}",
                        value: {{ $usr }},
                        fill: (colorizer())
                    },
                @endforeach

            ];

            // create the chart
            var chart = anychart.pie();

            // set the chart title
            chart.title("Users By Designation");

            // add the data
            chart.data(data);

            chart.innerRadius("30%");
            chart.background().fill("#F2F2F2"); // Change to any color you prefer

            // display the chart in the container
            chart.container('userChart');
            chart.draw();

        });

        anychart.onDocumentReady(function() {

            var data = [
                @php
                    $colors = [];
                    foreach ($total_activities as $key => $subs) {
                        // Generate a unique random RGB color
                        do {
                            $randomColor = sprintf(
                                'rgb(%d,%d,%d)',
                                rand(0, 255), // Red
                                rand(0, 255), // Green
                                rand(0, 255), // Blue
                            );
                        } while (in_array($randomColor, $colors)); // Ensure no duplicate colors

                        $colors[] = $randomColor; // Store used colors
                        echo "{ x: \"$key\", value: $subs, fill: \"$randomColor\" },";
                    }
                @endphp
            ];

            // Create the chart
            var chart = anychart.pie();

            // Set the chart title
            chart.title("Activities By Activity Type");

            // Add the data with unique colors
            chart.data(data);

            // Set inner radius for a donut chart
            chart.innerRadius("30%");
            chart.background().fill("#F2F2F2"); // Change to any color you prefer

            // Display the chart in the container
            chart.container('activityChart');
            chart.draw();
        });

        $(document).ready(() => {
            $(".anychart-credits").css('display', 'none');
        });


        anychart.onDocumentReady(function() {

            // set the data
            var data = [
                @foreach ($total_discussions as $key => $contry)
                    @if ($contry != 0)
                        {
                            x: "{{ $key }}",
                            value: {{ $contry }},
                            fill: (colorizer())
                        },
                    @endif
                @endforeach
            ];

            // create the chart
            var chart = anychart.pie();

            // set the chart title
            chart.title("Meeting Notes By Mode");

            // add the data
            chart.data(data);

            chart.innerRadius("30%");
            chart.background().fill("#F2F2F2"); // Change to any color you prefer

            // display the chart in the container
            chart.container('communicationChart');
            chart.draw();
            $(document).ready(() => {
              $(".anychart-credits").css('display','none');
          });
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
            chart.title("Payments By Mode");

            // add the data
            chart.data(data);

            chart.innerRadius("30%");
            chart.background().fill("#F2F2F2"); // Change to any color you prefer

            // display the chart in the container
            chart.container('paymentChart');
            chart.draw();
            $(document).ready(() => {
              $(".anychart-credits").css('display','none');
          });
        });

        $(document).ready(() => {
            $(".anychart-credits").css('display', 'none');
        });
    </script>
@endpush
