@extends('admin.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
            <div class="client-dashboard">
                <div class="client-btn d-flex justify-content-between mb-4">
                    <h3 class="text-primary px-3">Reports</h3>
                </div>
                <div class="col p-3 border">
                    <div class="client-btn d-flex justify-content-between mb-4">
                        <h4 class="text-info">Application Report</h4>
                        <p>
                            <a href="{{ route('applications_report_export') }}" class="m-0">Export</a>
                        </p>
                    </div>
                    <div class="px-3">Total Applications : {{ count($applications) }}</div>
                    <h6 class="mt-3" style="font-weight: 600;">Application Types</h6>
                    <div class="row px-3">
                        <div class="col-md-6 col-lg-3"><p>Visas & Immigration Advisory : {{ count($visa) }}</p></div>
                        <div class="col-md-6 col-lg-3"><p>Law Firm : {{ count($law) }}</p></div>
                        <div class="col-md-6 col-lg-3"><p>Travel Agency : {{ count($travel) }}</p></div>
                    </div>
                    <div class="col border p-3" style="width:100%;">
                        <div id="appChart" style="width: 100%; height: 300px"></div>
                    </div>
                    <div class="row m-0 p-2 d-flex justify-content-between">
                        <p>Applications by Subcategories</p>
                        <select id="sub_categories">
                            <option value="Visa">Visas & Immigration Advisory</option>
                            <option value="Law">Law Firm</option>
                            <option value="Travel">Travel Agency</option>
                        </select>
                    </div>
                    <div id="visa" class="col border p-3" style="width:100%;">
                        <div id="visaChart" style="width: 100%; height: 300px"></div>
                    </div>
                    <div id="law" class="col border p-3" style="width:100%;display:none;">
                        <div id="lawChart" style="width: 100%; height: 300px"></div>
                    </div>
                    <div id="travel" class="col border p-3" style="width:100%;display:none;">
                        <div id="travelChart" style="width: 100%; height: 300px"></div>
                    </div>
                </div>
                <div class="col mt-3 p-3 border">
                    <div class="client-btn d-flex justify-content-between mb-4">
                        <h4 class="text-info">Invoice Status Report</h4>
                        <p>
                            <a href="{{ route('invoices_report_export') }}" class="m-0">Export</a>
                        </p>
                    </div>
                    <div class="px-3">Total Invoices : {{ $total_invoices }}<br>Amount : {{ $total_amt }}</div>
                    <h6 class="mt-3" style="font-weight: 600;">Invoice Status</h6>
                    <div class="row px-3">
                        <div class="col-md-6 col-lg-3"><p>Paid Invoices : {{ $total_paid }}<br><small>Amount : {{ $paid_total }}</small></p></div>
                        <div class="col-md-6 col-lg-3"><p>Pending Invoices : {{ $total_unpaid }}<br><small>Amount : {{ $unpaid_total }}</small></p></div>
                        <div class="col-md-6 col-lg-3"><p>Partially Paid Invoices : 0<br><small>Amount : 0</small></p></div>
                        <div class="col-md-6 col-lg-3"><p>Refunded Invoices : 0<br><small>Amount : 0</small></p></div>
                    </div>
                    <div class="col border p-3" style="width:100%;">
                        <div id="invoiceChart" style="width: 100%; height: 300px"></div>
                        {{-- <canvas id="invoiceChart" style="width:100%;max-width:600px;"></canvas> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
  </script>
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
            {x: "Visas & Immigration Advisory", value: {{ count($visa) }}},
            {x: "Law Firm", value: {{ count($law) }}},
            {x: "Travel Agency", value: {{ count($travel) }}},
        ];
  
        // create the chart
        var chart = anychart.pie();
  
        // set the chart title
        chart.title("Applications by Category");
  
        // add the data
        chart.data(data);

        chart.innerRadius("30%");
  
        // display the chart in the container
        chart.container('appChart');
        chart.draw();
        $(document).ready(() => {
              $(".anychart-credits").css('display','none');
          });
    });

    
    anychart.onDocumentReady(function() {
        
        // set the data
        var data = [
            @foreach($total_visa_categ as $key => $sub_categ)
            {x: "{!! $key !!}", value: {{ $sub_categ }}, fill: (colorizer())},
            @endforeach
        ];
  
        // create the chart
        var chart = anychart.pie();
  
        // set the chart title
        chart.title("Applications of Visas & Immigration Advisory");
  
        // add the data
        chart.data(data);

        chart.innerRadius("30%");
  
        // display the chart in the container
        chart.container('visaChart');
        chart.draw();
        $(document).ready(() => {
              $(".anychart-credits").css('display','none');
          });
    });
    
    anychart.onDocumentReady(function() {
        
        // set the data
        var data = [
            @foreach($total_law_categ as $key => $sub_categ)
            {x: "{{ $key }}", value: {{ $sub_categ }}, fill: (colorizer())},
            @endforeach
        ];
  
        // create the chart
        var chart = anychart.pie();
  
        // set the chart title
        chart.title("Applications of Law Firm");
  
        // add the data
        chart.data(data);

        chart.innerRadius("30%");
  
        // display the chart in the container
        chart.container('lawChart');
        chart.draw();
        $(document).ready(() => {
              $(".anychart-credits").css('display','none');
          });
    });
    
    anychart.onDocumentReady(function() {
        
        // set the data
        var data = [
            @foreach($total_travel_categ as $key => $sub_categ)
            {x: "{{ $key }}", value: {{ $sub_categ }}, fill: (colorizer())},
            @endforeach
        ];
  
        // create the chart
        var chart = anychart.pie();
  
        // set the chart title
        chart.title("Applications of Travel Agency");
  
        // add the data
        chart.data(data);

        chart.innerRadius("30%");
  
        // display the chart in the container
        chart.container('travelChart');
        chart.draw();
        $(document).ready(() => {
              $(".anychart-credits").css('display','none');
          });
    });

    anychart.onDocumentReady(function() {
        
        // set the data
        var data = [
            {x: "Paid Invoices", value: {{ $total_paid }}},
            {x: "Pending Invoices", value: {{ $total_unpaid }}},
            {x: "Partialy Paid Invoices", value: 0},
            {x: "Refunded Invoices", value: 0},
        ];
  
        // create the chart
        var chart = anychart.pie();
  
        // set the chart title
        chart.title("Complete Invoices Report");
  
        // add the data
        chart.data(data);

        chart.innerRadius("30%");
  
        // display the chart in the container
        chart.container('invoiceChart');
        chart.draw();
        $(document).ready(() => {
              $(".anychart-credits").css('display','none');
          });
    });
      $(document).ready(() => {
        $(".anychart-credits").css('display','none');
        $("#sub_categories").on('change', function(){
            var categ = $(this).val();
            if(categ == "Visa"){
                $("#visa").css('display','block');
                $("#law").css('display','none');
                $("#travel").css('display','none');
            }
            else if(categ == "Law"){
                $("#visa").css('display','none');
                $("#law").css('display','block');
                $("#travel").css('display','none');
            }
            else{
                $("#visa").css('display','none');
                $("#law").css('display','none');
                $("#travel").css('display','block');
            }
        });
          $("#country").change(function(){
            var country = $(this).val();
            // console.log(counrty);
            $.ajax({
                url: 'get_states',
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
          });
          $("#subscriber").change(function(){
            var id = $(this).val();
            var name = 'subscriber';
            // console.log(counrty);
            $.ajax({
                url: 'get_job_role',
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                    name: name,
                },
                cache:false,
                success: function(data){
                  console.log(data);
                    $("#job_role").html(data);
                }
            });
          });
      });
  </script>
  <script>
      function deleteuser(id){
          var conf = confirm('Delete User');
          if(conf == true){
              window.location.href = "delete_user/"+id+"";
          }
      }
  </script>
  
  @if(session()->has('deleted'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'User Deleted Successfully!'
      })
    </script>
  
  @endif

@endsection()
            