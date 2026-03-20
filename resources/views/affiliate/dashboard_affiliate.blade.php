@extends('affiliate.layout.main')

@section('main-section')

<div class="col-lg-9">
    <div class="row">
        <!-- Referrals Card -->
        <div class="col-md-4">
            <div class="client-box" style="background-color: #4CAF50; border-radius: 10px; color: #ffffff;">
                <i style="font-size: 30px; width: 50px; height: 50px; background: #ffffff; border-radius: 50%;" class="fa fa-handshake text-info p-1 d-flex text-center align-items-center justify-content-center"></i>
                <h4>{{  round($total_referrals,2) }}</h4>
                <p style="font-weight: bolder!important;">@if($total_referrals > 1) Referrals @else Referral @endif</p>
            </div>
        </div>

        <!-- Commission Card -->
        <div class="col-md-4">
            <div class="client-box" style="background-color: #FFC107; border-radius: 10px; color: #ffffff;">
                <i style="font-size: 30px; width: 50px; height: 50px; background: #ffffff; border-radius: 50%;" class="fa fa-dollar-sign text-info p-1 d-flex text-center align-items-center justify-content-center"></i>
                <h4>$ {{ $comission_earned }}</h4>
                <p style="font-weight: bolder!important;">Total Commission</p>
            </div>
        </div>

        <!-- Wallet Card -->
        <div class="col-md-4">
            <div class="client-box" style="background-color: #9C27B0; border-radius: 10px; color: #ffffff;">
                <i style="font-size: 30px; width: 50px; height: 50px; background: #ffffff; border-radius: 50%;" class="fa-solid fa-wallet text-info p-1 d-flex text-center align-items-center justify-content-center"></i>
                <h4>$ {{ round($wallet,2) }}</h4>
                <p style="font-weight: bolder!important;">Wallet Credit</p>
            </div>
        </div>
    </div>
    <div class="row mt-5">

        <div class="col-6 rounded-5 bg-white p-3 border" style="height: fit-content;">
            <h5 class="text-center fw-bold">Subscribers By Plan </h5>
            <div id="subscriberChart" style="width: 100%; height: 300px;"></div>
        </div>
        <div class="col-6 rounded-5 bg-white p-3 border" style="height: fit-content;">
            <h5 class="text-center fw-bold">Subscribers By Plan Duration</h5>
            <div id="SubscriberByPlanDuration" style="width: 100%; height: 300px;"></div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-6 rounded-5 bg-white p-3 border" style="height: fit-content;">
            <h5 class="text-center fw-bold">Subscribers By Country</h5>
            <div id="SubscriberByCountry" style="width: 100%; height: 300px;"></div>
        </div>
        <div class="col-6 rounded-5 bg-white p-3 border" style="height: fit-content;">
            <h5 class="text-center fw-bold">Subscribers By Type</h5>
            <div id="SubscriberByType" style="width: 100%; height: 300px;"></div>
        </div>

    </div>

</div>
</div>
</div>
    @push('other-scripts')
    <script>
        $(document).ready(() => {


            var all_colors = [];
      function colorizer(){
        const characters = 'ABCDEF0123456789';
        let result = '#';
        const charactersLength = characters.length;
        for(let i = 0; i < 6; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        // console.log(result);
        return result;
      }

      anychart.onDocumentReady(function() {

      // set the data
    //   var data = [
    //         @foreach($total_subscribers as $key => $subs)
    //           {x: "{{ $key }}", value: {{ $subs }}, fill: (colorizer())},
    //         @endforeach
    //   ];

       var data = [
            @foreach($grouped_data as $membership_duration => $plans)
                @foreach($plans as $membership => $subs)
                    {
                        x: "{{ $membership }} ({{ $membership_duration }} years)",  // Label with membership type and duration
                        value: {{ $subs['total_users'] }},
                        fill:  colorizer()  // Dynamic coloring (optional)
                    },
                @endforeach
            @endforeach
        ];

      // create the chart
      var chart = anychart.pie();

      // set the chart title
    //   chart.title("Subscribers By Plan");
      console.log('data', data);
    if (data.length === 0) {
        chart.noData().label().enabled(true).text("No Data to Show").fontSize(16);
    } else {
        // Add the data
        chart.data(data);
        chart.innerRadius("30%"); // Doughnut style
        chart.background().fill("#F2F2F2");
    }

      // display the chart in the container
      chart.container('subscriberChart');
      chart.draw();
          $(document).ready(() => {
              $(".anychart-credits").css('display','none');
          });
      });
      anychart.onDocumentReady(function() {
        // Set the data dynamically
        var data = [
            @foreach($grouped_data as $membership_duration => $plans)
                @foreach($plans as $membership => $subs)
                    {
                        x: "{{ $membership }} ({{ $membership_duration }} years)",  // Label with membership type and duration
                        value: {{ $subs['total_users'] }},
                        fill:  colorizer()  // Dynamic coloring (optional)
                    },
                @endforeach
            @endforeach
        ];

        // Create the pie chart
        var chart = anychart.pie();

        // Set the chart title
        // chart.title("Subscriber Distribution by Membership Duration");
        if (data.length === 0) {
            chart.noData().label().enabled(true).text("No Data to Show").fontSize(16);
        } else {
            // Add the data
            chart.data(data);
            chart.innerRadius("30%"); // Doughnut style
            chart.background().fill("#F2F2F2");
        }

        // Display the chart in the container
        chart.container('SubscriberByPlanDuration');
        chart.draw();
        $(document).ready(() => {
              $(".anychart-credits").css('display','none');
          });
    });
    anychart.onDocumentReady(function () {
    // Generate random colors dynamically


    // Set the data dynamically
    var data = [
        @foreach($final_grouped_data as $country => $plans)
            @foreach($plans as $membership => $subs)
                {
                    x: "{{ $country }} - {{ $membership }} ({{ $subs['total_users'] }} users)", // Label with country, membership, and user count
                    value: {{ $subs['total_users'] }},
                    fill: colorizer(), // Dynamic color for each slice
                },
            @endforeach
        @endforeach
    ];

    // Create the pie chart
    var chart = anychart.pie();

    // Set the chart title
    // chart.title("Subscriber Distribution by Country and Membership");

    if (data.length === 0) {
        chart.noData().label().enabled(true).text("No Data to Show").fontSize(16);
    } else {
        // Add the data
        chart.data(data);
        chart.innerRadius("30%"); // Doughnut style
        chart.background().fill("#F2F2F2");
    }

    // Display the chart in the container
    chart.container('SubscriberByCountry');
    chart.draw();
    $(document).ready(() => {
              $(".anychart-credits").css('display','none');
          });
});
anychart.onDocumentReady(function () {
    // Generate random colors dynamically


    // Set the data dynamically
    var finalGroupedDataSubcategory = @json($final_grouped_data_subcategory);

// Prepare chart data
var data = [];
for (var subCategory in finalGroupedDataSubcategory) {
    if (finalGroupedDataSubcategory.hasOwnProperty(subCategory)) {
        data.push({
            x: `${subCategory} (${finalGroupedDataSubcategory[subCategory].total_users} users)`,
            value: finalGroupedDataSubcategory[subCategory].total_users,
            fill: colorizer(), // Assign random colors
        });
    }
}

    // Create the pie chart
    var chart = anychart.pie();

    // Set the chart title
    // chart.title("Subscriber Distribution by Country and Membership");

    if (data.length === 0) {
        chart.noData().label().enabled(true).text("No Data to Show").fontSize(16);
    } else {
        // Add the data
        chart.data(data);
        chart.innerRadius("30%"); // Doughnut style
        chart.background().fill("#F2F2F2");
    }

    // Display the chart in the container
    chart.container('SubscriberByType');
    chart.draw();
    $(document).ready(() => {
              $(".anychart-credits").css('display','none');
          });
});


            var start = moment().subtract(29, 'days');
            var end = moment();

            function cb(start, end) {
                $('#custom_date_picker span').html(start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY'));
            }

            $('#custom_date_picker').daterangepicker({
                startDate: start,
                endDate: end,
                maxDate: moment(),
                locale: {
                    format: 'DD-MM-YYYY'
                },
                ranges: {
                    'Since Inception': [moment('2000-01-01'), moment()],
                    'Today': [moment(), moment()],
                    'Last Week': [moment().subtract(6, 'days'), moment()],
                    'Last Month': [moment().subtract(29, 'days'), moment()],
                    'Last Quarter': [moment().subtract(3, 'months').startOf('month'), moment().endOf('month')],
                    'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
                }
            }, cb);

            cb(start, end);

            $('#custom_date_picker').on('apply.daterangepicker', function (ev, picker) {
                // Custom functionality can go here
            });


//             function showNoDataMessage(chartId, message = "No data to show") {
//     const canvas = document.getElementById(chartId);
//     const ctx = canvas.getContext('2d');
//     const { width, height } = canvas;

//     ctx.clearRect(0, 0, width, height); // Clear the canvas
//     ctx.font = "16px Arial";
//     ctx.fillStyle = "#666";
//     ctx.textAlign = "center";
//     ctx.textBaseline = "middle";
//     ctx.fillText(message, width / 2, height / 2);
// }

// $.ajax({
//     url: "{{ route('affiliate.dashboard_affiliate') }}",
//     type: 'GET',
//     dataType: 'json',
//     success: function (response) {
//         console.log(response);

//         // Common Chart Options
//         const commonOptions = {
//             type: 'pie', // or 'bar', 'line', depending on the chart type
//             options: {
//                 responsive: true,
//                 maintainAspectRatio: false, // Ensures consistent sizing
//                 plugins: {
//                     legend: {
//                         position: 'bottom', // Positions legend at the bottom
//                         labels: {
//                             boxWidth: 15, // Size of the legend box
//                             padding: 10, // Space around labels
//                         },
//                     },
//                 },
//                 scales: {
//                     x: {
//                         ticks: {
//                             autoSkip: false, // Prevent skipping of labels
//                             maxRotation: 0,  // Force horizontal labels
//                             minRotation: 0,  // Prevent rotation
//                         },
//                     },
//                     y: {
//                         beginAtZero: true, // Start the y-axis at zero
//                     },
//                 },
//             },
//         };

//         // Function to apply consistent dimensions
//         function setChartDimensions(canvasId, width = 400, height = 400) {
//             const canvas = document.getElementById(canvasId);
//             canvas.width = width;
//             canvas.height = height;
//         }

//         // Show "No Data" message
//         function showNoDataMessage(canvasId) {
//             const canvas = document.getElementById(canvasId);
//             const ctx = canvas.getContext('2d');
//             ctx.clearRect(0, 0, canvas.width, canvas.height);
//             ctx.font = '16px Arial';
//             ctx.textAlign = 'center';
//             ctx.fillText('No Data Available', canvas.width / 2, canvas.height / 2);
//         }

//         // Membership Chart
//         setChartDimensions('membershipChart');
//         if (response.subscribers_byPlan_result[1] && response.subscribers_byPlan_result[1].length > 0) {
//             const membershipData = {
//                 labels: response.subscribers_byPlan_result[0],
//                 datasets: [{
//                     label: 'SUB Count by Membership',
//                     data: response.subscribers_byPlan_result[1],
//                     backgroundColor: [
//                         'rgba(255, 99, 132, 0.2)',
//                         'rgba(54, 162, 235, 0.2)',
//                         'rgba(255, 206, 86, 0.2)',
//                         'rgba(75, 192, 192, 0.2)',
//                         'rgba(153, 102, 255, 0.2)',
//                         'rgba(255, 159, 64, 0.2)'
//                     ],
//                     borderColor: [
//                         'rgba(255, 99, 132, 1)',
//                         'rgba(54, 162, 235, 1)',
//                         'rgba(255, 206, 86, 1)',
//                         'rgba(75, 192, 192, 1)',
//                         'rgba(153, 102, 255, 1)',
//                         'rgba(255, 159, 64, 1)'
//                     ],
//                     borderWidth: 1
//                 }]
//             };

//             new Chart(
//                 document.getElementById('membershipChart').getContext('2d'),
//                 { ...commonOptions, data: membershipData }
//             );
//         } else {
//             showNoDataMessage('membershipChart');
//         }

//         // Membership Duration Chart
//         setChartDimensions('membershipDurationChart');
//         if (response.subscribers_byPlan_result[3] && response.subscribers_byPlan_result[3].length > 0) {
//             const durationData = {
//                 labels: response.subscribers_byPlan_result[2],
//                 datasets: [{
//                     label: 'SUB Count by Duration',
//                     data: response.subscribers_byPlan_result[3],
//                     backgroundColor: [
//                         'rgba(255, 99, 132, 0.2)',
//                         'rgba(54, 162, 235, 0.2)',
//                         'rgba(255, 206, 86, 0.2)',
//                         'rgba(75, 192, 192, 0.2)',
//                         'rgba(153, 102, 255, 0.2)',
//                         'rgba(255, 159, 64, 0.2)'
//                     ],
//                     borderColor: [
//                         'rgba(255, 99, 132, 1)',
//                         'rgba(54, 162, 235, 1)',
//                         'rgba(255, 206, 86, 1)',
//                         'rgba(75, 192, 192, 1)',
//                         'rgba(153, 102, 255, 1)',
//                         'rgba(255, 159, 64, 1)'
//                     ],
//                     borderWidth: 1
//                 }]
//             };

//             new Chart(
//                 document.getElementById('membershipDurationChart').getContext('2d'),
//                 { ...commonOptions, data: durationData }
//             );
//         } else {
//             showNoDataMessage('membershipDurationChart');
//         }

//         // Repeat the same pattern for the remaining charts
//         // Membership Country Chart
//         setChartDimensions('membershipCountryChart');
//         if (response.subscribers_byPlan_result[5] && response.subscribers_byPlan_result[5].length > 0) {
//             const countryData = {
//                 labels: response.subscribers_byPlan_result[4],
//                 datasets: [{
//                     label: 'SUB Count by Country',
//                     data: response.subscribers_byPlan_result[5],
//                     backgroundColor: [
//                         'rgba(255, 99, 132, 0.2)',
//                         'rgba(54, 162, 235, 0.2)',
//                         'rgba(255, 206, 86, 0.2)',
//                         'rgba(75, 192, 192, 0.2)',
//                         'rgba(153, 102, 255, 0.2)',
//                         'rgba(255, 159, 64, 0.2)'
//                     ],
//                     borderColor: [
//                         'rgba(255, 99, 132, 1)',
//                         'rgba(54, 162, 235, 1)',
//                         'rgba(255, 206, 86, 1)',
//                         'rgba(75, 192, 192, 1)',
//                         'rgba(153, 102, 255, 1)',
//                         'rgba(255, 159, 64, 1)'
//                     ],
//                     borderWidth: 1
//                 }]
//             };

//             new Chart(
//                 document.getElementById('membershipCountryChart').getContext('2d'),
//                 { ...commonOptions, data: countryData }
//             );
//         } else {
//             showNoDataMessage('membershipCountryChart');
//         }
//          // Membership Age Chart
//          if (response.subscribers_byPlan_result[6] && response.subscribers_byPlan_result[7].length > 0) {
//     const commonOptions = {
//         type: 'pie', // Use 'pie' for a consistent chart type
//         options: {
//             responsive: true,
//             maintainAspectRatio: false, // Ensures consistent aspect ratio for all charts
//             plugins: {
//                 legend: {
//                     position: 'bottom', // Positions the legend at the bottom
//                     labels: {
//                         boxWidth: 15, // Size of the legend color box
//                         padding: 10, // Padding between legend items
//                         font: {
//                             size: 14, // Font size for labels
//                         },
//                     },
//                 },
//                 tooltip: {
//                     callbacks: {
//                         label: function (tooltipItem) {
//                             // Custom tooltip to display data values
//                             const label = tooltipItem.label || '';
//                             const value = tooltipItem.raw || 0;
//                             return `${label}: ${value}`;
//                         },
//                     },
//                 },
//             },
//         },
//     };

//     const ageData = {
//         labels: response.subscribers_byPlan_result[7],
//         datasets: [{
//             label: 'SUB Count by Sub category',
//             data: response.subscribers_byPlan_result[6],
//             backgroundColor: [
//                 'rgba(255, 99, 132, 0.2)',
//                 'rgba(54, 162, 235, 0.2)',
//                 'rgba(255, 206, 86, 0.2)',
//                 'rgba(75, 192, 192, 0.2)',
//                 'rgba(153, 102, 255, 0.2)',
//                 'rgba(255, 159, 64, 0.2)'
//             ],
//             borderColor: [
//                 'rgba(255, 99, 132, 1)',
//                 'rgba(54, 162, 235, 1)',
//                 'rgba(255, 206, 86, 1)',
//                 'rgba(75, 192, 192, 1)',
//                 'rgba(153, 102, 255, 1)',
//                 'rgba(255, 159, 64, 1)'
//             ],
//             borderWidth: 1,
//         }],
//     };

//     // Set consistent dimensions for the chart canvas
//     const chartCanvas = document.getElementById('membershipAgeChart');
//     chartCanvas.width = 400; // Set the width of the canvas
//     chartCanvas.height = 400; // Set the height of the canvas

//     // Create the chart
//     new Chart(
//         chartCanvas.getContext('2d'),
//         { ...commonOptions, data: ageData }
//     );
// } else {
//     showNoDataMessage('membershipAgeChart');
// }

//     },
//     error: function (xhr, status, error) {
//         console.error(error);
//     }
// });





        });
    </script>

    @endpush
@stop
