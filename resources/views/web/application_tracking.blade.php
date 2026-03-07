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


<style>
.flow-wrapper {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    justify-content: flex-start;
    gap: 10px;
}

.circle {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;

    padding: 10px;
    min-width: 120px;
    max-width: 200px; /* optional */
    
    border: 2px solid #007BFF;
    border-radius: 50%;
    background-color: #f0f8ff;

    aspect-ratio: 1 / 1; /* maintains circular shape */
    
    text-align: center;
    box-sizing: border-box;
    word-break: break-word;
    
    font-size: 14px;
    line-height: 1.4;
}


.arrow, .down-arrow {
    font-size: 24px;
    margin: 0 10px;
    align-self: center;
}
.down-arrow {
    writing-mode: vertical-rl;
    transform: rotate(180deg);
}
</style>

        <div class="col-lg-10 column-client">
            <div class="client-dashboard">
                <div class="client-btn d-flex justify-content-between align-items-center mt-3 ">
                    <h3 class="text-primary text-center flex-grow-1 text-center m-0">Application Tracking</h3>
                    @if(count($clients) > 0)
                    <button class="btn btn-info text-white" type="button" @if($application_roles->write_only == 1 or $application_roles->read_write_only == 1) id="add_new" @endif>Add New</button>
                    @else
                    <button class="btn btn-info text-white" type="button" @if($application_roles->write_only == 1 or $application_roles->read_write_only == 1) id="add_new_zero" @endif>Add New</button>
                    @endif
                    <button style="display: none;" class="btn btn-info text-white" type="button" id="back">Back</button>

                </div>
                <div class="row m-0 p-2">
                    <div class="col-3 border p-1 text-center top_modules" onclick="window.location.href = '{{ route('applications') }}';">
                      Applications
                    </div>
                    <div class="col-3 border p-1 text-center top_modules" onclick="window.location.href = '{{ route('client_documents') }}';">
                      Documents
                    </div>
                    <div class="col-3 border p-1 text-center top_modules" @if($user->user_type == "Subscriber") onclick="window.location.href = '{{ route('user_applications') }}';" @endif>
                      Application Management
                    </div>
                    <div class="col-3 border p-1 text-center bg-info text-white">
                      Application Tracking
                    </div>
                </div>


                <div class="col">
                    <form id="registration_form" class="register-box login-box" method="POST" action="{{ route('admin_new_invoice_post') }}" onsubmit="document.getElementById('invoice_submit').setAttribute('disabled','true');">
                        @csrf
                        <div class="row">
                            <h3 style="font-weight: 400!important;padding-bottom:15px;" class="text-primary text-center flex-grow-1 text-center m-0">Application Tracking</h3>
                            <div class="col-md-3 p-1">
                                <label>Client<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-7 p-1">
                                <select name="client_id" id="client" required class="form-control form-select @error('client') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp">
                                    <option value="">Select Client</option>
                                    {{-- Options will be loaded via AJAX --}}
                                </select>
                                @error('client')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <!-- APPLICATION DROPDOWN -->
                            <div class="col-md-3 p-1">
                                <label>Application<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-7 p-1">
                                <select name="application" id="application" required class="form-control form-select @error('client') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp">
                                    <option value="">Select Application</option>
                                </select>
                                @error('client')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                           
                            <div class="col-md-3 p-1">
                            </div>
                            <div class="col-md-12 p-3 text-center">
                                <button type="button" id="view_report" class="form-control btn btn-primary" style="width: fit-content;" onclick="viewReport(); verifyDropDowns();">View Report</button>
                                <button type="button" id="view_chart" class="form-control btn btn-primary" style="width: fit-content;" onclick="viewChart(); verifyDropDowns();">View Chart</button>
                            </div>
                            
                        </div>
                    </form>
                </div>
                <br>
                <center>
                    <h4 style="color:#0D6EFD" class=" text-center flex-grow-1 text-center m-0" id="tracking_id"></h4>
                </center>
                <br>
                <div class="table-wrapper" id="report_section" style="display: none;">
                    <table class="table table-hover table-bordered fl-table" id="clientTable">
                        <thead>
                        <tr>
                           <th class="text-center">Sr No.</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Dates</th>
                            <th class="text-center">User (ID)</th>
                        </tr>
                        </thead>
                        <tbody id="application_table_body">

                        <tbody>
                    </table>
                </div>
                <div class="table-wrapper" id="chart_section" style="display: none;">
                    <div id="application_flow_chart" class="flow-container mt-4"></div>

                </div>
                {{-- <div class="table-btn">
                    <button>Previous</button>
                    <button>1</button>
                    <button>Next</button>
                </div> --}}
            </div>
        </div>
    </div>

  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
  </script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
     function viewReport() {
        // Toggle buttons
        $('#view_report').prop('disabled', true);
        $('#view_chart').prop('disabled', false);

        // Toggle divs
        $('#report_section').show();
        $('#chart_section').hide();
    }

    function viewChart() {
        // Toggle buttons
        $('#view_chart').prop('disabled', true);
        $('#view_report').prop('disabled', false);

        // Toggle divs
        $('#chart_section').show();
        $('#report_section').hide();
    }

    function renderFlowChart(statuses) {
      let html = '<div class="flow-wrapper">';
      
      statuses.forEach((item, index) => {
          html += `
              <div class="circle">
                  <strong>${item.user}</strong><br/>
                  ${item.start_date} - ${item.end_date}<br/>
                  <em>${item.status}</em>
              </div>
          `;
          if (index < statuses.length - 1) {
              // Add arrow unless it's the last one
              if (!(item.status === 'Appeal' && statuses[index + 1].status === 'Closed')) {
                  html += `<div class="arrow">→</div>`;
              } else {
                  html += `<div class="down-arrow">↓</div>`;
              }
          }
      });

      html += '</div>';

      $('#application_flow_chart').html(html);
  }


    // Optional: Load "Report" view by default
    $(document).ready(function () {
        // viewReport(); // or viewChart();
    });
    $(document).ready(function () {
        // Load Clients by Subscriber
        // $('#subscriber').on('change', function () {
            document.getElementById("application").disabled = true;

            $('#client').empty().append('<option value="">Loading...</option>');
            $('#application').empty().append('<option value="">Select Application</option>');
        
            if (1==1) {
                $.ajax({
                    url: '{{ route('clients.bySubscriber', '') }}',
                    type: 'GET',
                    success: function (data) {
                        $('#client').empty().append('<option value="">Select Client</option>');
                        $.each(data, function (key, client) {
                            $('#client').append('<option value="' + client.id + '">' + client.name + ' (' + client.id + ')' + '</option>');
                        });
                    }
                });
            } else {
                $('#client').empty().append('<option value="">Select Client</option>');
            }
        // });

        // Load Applications by Client
        $('#client').on('change', function () {
            var clientId = $(this).val();
            $('#application').empty().append('<option value="">Loading...</option>');

            if (clientId) {
                $.ajax({
                    url: '{{ route('applications.byClient', '') }}/' + clientId,
                    type: 'GET',
                    success: function (data) {
                        $('#application').empty().append('<option value="">Select Application</option>');
                        $.each(data, function (key, app) {
                            $('#application').append('<option value="' + app.id + '">' + app.application_name + ' (' + app.id + ')' + '</option>');
                        });
                        document.getElementById("application").disabled = false;
                    }
                });
            } else {
                $('#application').empty().append('<option value="">Select Application</option>');
            }
        });
    });
    </script>
    <script>
        function verifyDropDowns () {
            // const subscriber = $('#subscriber').val();
            const client = $('#client').val();
            const application = $('#application').val();

            if (!client || !application) {
                $('#tracking_id').text("No data found. Please select both Client and Application."); 
                $('#tracking_id').css("color", "red"); 
                $('#report_section').hide();
                $('#view_report').prop('disabled', false);
                $('#view_chart').prop('disabled', false);
            } else {
                
                $('#tracking_id').css("color", "#0D6EFD"); 
                // You can trigger form submission or another action here
            }
        }

    </script>
    <script>
      $(document).ready(function () {
          $('#application').on('change', function () {
              const applicationId = $(this).val();
              const applicationText = $(this).find('option:selected').text();  // This gets the text of the selected option
              if (applicationId) {
                  $.ajax({
                      url: '/admin/get-application-data/' + applicationId,
                      type: 'GET',
                      success: function (data) {
                          let rows = '';

                          if (data.length > 0) {
                            $('#tracking_id').text(applicationText);  // Assuming data[0].name contains the desired text
                            viewReport();
                            verifyDropDowns();
                              data.forEach(item => {
                                  rows += `
                                      <tr>
                                          <td class="text-center">${item.index}</td>
                                          <td class="text-center">${item.status}</td>
                                          <td class="text-center">${item.start_date} - ${item.end_date}</td>
                                          <td class="text-center">${item.user}</td>
                                      </tr>
                                  `;
                              });
                          } else {
                              rows = `<tr><td colspan="4" class="text-center">No data found.</td></tr>`;
                              $('#tracking_id').text('No application selected');  // Fallback text when no data
                          }

                          $('#application_table_body').html(rows);
                          renderFlowChart(data);
                      },
                      error: function () {
                          alert('Failed to fetch application data.');
                          $('#application_table_body').html('');
                          $('#tracking_id').text('Error loading application data');  // Fallback text in case of error
                      }
                  });
              } else {
                  $('#application_table_body').html('');
                  $('#tracking_id').text('Please select an application');
              }
          });
      });
  </script>

  <script>
    function deleteapplication(id){
        var conf = confirm('Delete Application');
        if(conf == true){
            window.location.href = "delete_application/"+id+"";
        }
    }
  </script>
  <script>
      $(document).ready(() => {

        $("#add_new_zero").click(function(){
            Swal.fire({
            icon: 'info',
            title: 'Oops...',
            text: 'There is no applications created.'
            });
        });
      })
  </script>

@if(session()->has('deleted'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: 'Application Deleted Successfully!'
    })
  </script>

@endif
@if(session()->has('application_added'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: 'New Application Added Successfully!'
    })
  </script>

@endif
@if(session()->has('application_updated'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: 'Application Updated Successfully!'
    })
  </script>

@endif
@endsection()
