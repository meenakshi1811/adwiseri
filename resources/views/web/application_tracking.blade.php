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
.tracking-card {
    border: 1px solid #dbe4ff;
    border-radius: 10px;
    padding: 16px;
    background: #fbfdff;
}

.tracking-label {
    font-weight: 600;
    color: #2f4f8f;
}

.tracking-hint {
    font-size: 12px;
    color: #6c757d;
    margin-top: 6px;
}

.tracking-action-row {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
}

.tracking-action-btn.active {
    background-color: #0d6efd !important;
    color: #fff !important;
    border-color: #0d6efd !important;
    box-shadow: 0 4px 10px rgba(13, 110, 253, 0.25);
}

.flow-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    align-items: center;
    justify-content: center;
}

.flow-step {
    display: flex;
    align-items: center;
    gap: 14px;
}

.flow-arrow {
    font-size: 26px;
    color: #0d6efd;
    font-weight: 700;
    line-height: 1;
}

.status-circle {
    width: 220px;
    height: 220px;
    border-radius: 50%;
    border: 2px solid #d6dce3;
    margin: 0 auto;
    padding: 18px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    text-align: center;
    box-sizing: border-box;
    background: #eceff3;
    box-shadow: 0 8px 16px rgba(35, 48, 64, 0.08);
}

.circle-date {
    font-size: 14px;
    font-weight: 700;
    color: #003f95;
    margin-bottom: 2px;
}

.circle-range {
    font-size: 13px;
    color: #1d3e72;
    font-weight: 600;
}

.status-circle hr {
    width: 100%;
    margin: 9px 0;
    border-color: rgba(0, 83, 196, 0.35);
}

.circle-status {
    font-size: 18px;
    line-height: 1.25;
    font-weight: 700;
    color: #163c76;
}

.circle-user {
    font-size: 14px;
    color: #0f2950;
    font-weight: 600;
}

@media (max-width: 575px) {
    .status-circle {
        width: 195px;
        height: 195px;
        padding: 14px;
    }

    .circle-status {
        font-size: 16px;
    }

    .flow-step {
        width: 100%;
        justify-content: center;
    }

    .flow-arrow {
        display: none;
    }
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


                <div class="col tracking-card">
                    <form id="registration_form" class="register-box login-box" method="POST" action="{{ route('admin_new_invoice_post') }}" onsubmit="document.getElementById('invoice_submit').setAttribute('disabled','true');">
                        @csrf
                        <div class="row">
                            <h3 style="font-weight: 400!important;padding-bottom:15px;" class="text-primary text-center flex-grow-1 text-center m-0">Application Tracking</h3>
                            <div class="col-md-3 p-1">
                                <label class="tracking-label">Select Client<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-7 p-1">
                                <select name="client_id" id="client" required class="form-control form-select @error('client') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp">
                                    <option value="">Select Client</option>
                                    {{-- Options will be loaded via AJAX --}}
                                </select>
                                <div class="tracking-hint">Only clients with applications are shown in this list.</div>
                                @error('client')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <!-- APPLICATION DROPDOWN -->
                            <div class="col-md-3 p-1">
                                <label class="tracking-label">Select Application<span class="text-danger" style="font-size: 18px;">*</span></label>
                            </div>
                            <div class="col-md-7 p-1">
                                <select name="application" id="application" required class="form-control form-select @error('client') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp">
                                    <option value="">Select Application</option>
                                </select>
                                <div class="tracking-hint">All applications related to the selected client are listed here.</div>
                                @error('client')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="col-md-3 p-1">
                            </div>
                            <div class="col-md-12 p-3 text-center tracking-action-row">
                                <button type="button" id="view_status" class="btn btn-outline-primary tracking-action-btn" onclick="viewChart(); verifyDropDowns();">View Status</button>
                                <button type="button" id="download_status" class="btn btn-outline-primary tracking-action-btn" onclick="downloadStatus(); verifyDropDowns();">Download Status</button>
                                <button type="button" id="view_report" class="btn btn-outline-primary tracking-action-btn" onclick="viewReport(); verifyDropDowns();">View Table</button>
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
    function setActiveActionButton(activeButtonId) {
        $('.tracking-action-btn').removeClass('active');
        if (activeButtonId) {
            $('#' + activeButtonId).addClass('active');
        }
    }

    function viewReport() {
        setActiveActionButton('view_report');
        // Toggle buttons
        $('#view_report').prop('disabled', true);
        $('#view_status').prop('disabled', false);

        // Toggle divs
        $('#report_section').show();
        $('#chart_section').hide();
    }

    function viewChart() {
        setActiveActionButton('view_status');
        // Toggle buttons
        $('#view_status').prop('disabled', true);
        $('#view_report').prop('disabled', false);

        // Toggle divs
        $('#chart_section').show();
        $('#report_section').hide();
    }

    function downloadStatus() {
        setActiveActionButton('download_status');
        const chartContent = document.getElementById('application_flow_chart').innerHTML;
        if (!chartContent.trim()) {
            return;
        }

        const clientText = $('#client').find('option:selected').text() || '--';
        const applicationText = $('#application').find('option:selected').text() || '--';

        const printableWindow = window.open('', '_blank');
        printableWindow.document.write(`
            <html>
                <head>
                    <title>Application Tracking Status</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; }
                        .summary-row { margin-bottom: 6px; font-size: 14px; }
                        .summary-label { font-weight: 700; color: #163c76; }
                        .flow-wrapper { display: flex; flex-wrap: wrap; gap: 14px; align-items: center; justify-content: center; }
                        .flow-step { display: flex; align-items: center; gap: 14px; }
                        .flow-arrow { font-size: 26px; color: #0d6efd; font-weight: 700; line-height: 1; }
                        .status-circle { width: 220px; height: 220px; border-radius: 50%; border: 2px solid #d6dce3; margin: 0 auto; padding: 18px; display: flex; flex-direction: column; justify-content: center; text-align: center; box-sizing: border-box; background: #eceff3; }
                        .status-circle hr { width: 100%; margin: 9px 0; border-color: rgba(0,83,196,0.35); }
                        .circle-range { font-size: 13px; color: #1d3e72; font-weight: 600; }
                        .circle-status { font-size: 18px; font-weight: 700; color: #163c76; }
                        .circle-user { font-size: 14px; color: #0f2950; font-weight: 600; }
                    </style>
                </head>
                <body>
                    <h2>Application Tracking Status</h2>
                    <div class="summary-row"><span class="summary-label">Client Name (ID) :-</span> ${clientText}</div>
                    <div class="summary-row"><span class="summary-label">Application (ID) :-</span> ${applicationText}</div>
                    <br>
                    <div>${chartContent}</div>
                </body>
            </html>
        `);
        printableWindow.document.close();
        printableWindow.focus();
        printableWindow.print();
    }

    function formatDateRange(item) {
        const startDate = item.start_date || '--';
        const endDate = item.end_date || '';
        const status = (item.status || '').toLowerCase();

        if (status === 'registration') {
            return startDate;
        }

        if (status === 'pending') {
            return `${startDate} - `;
        }

        if (!endDate || endDate === '--') {
            return `${startDate} - `;
        }

        if (startDate === endDate) {
            return startDate;
        }

        return `${startDate} - ${endDate}`;
    }

    function renderFlowChart(statuses) {
      let html = '<div class="flow-wrapper">';
      
      statuses.forEach((item, index) => {
          const dateRange = formatDateRange(item);

          html += `
              <div class="flow-step">
                  <div class="status-circle">
                      <div class="circle-range">${dateRange}</div>
                      <hr>
                      <div class="circle-status">${item.status || '--'}</div>
                      <hr>
                      <div class="circle-user">${item.user || '--'}</div>
                  </div>
                  ${index < statuses.length - 1 ? '<div class="flow-arrow">→</div>' : ''}
              </div>
          `;
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
                $('#chart_section').hide();
                $('#view_report').prop('disabled', false);
                $('#view_status').prop('disabled', false);
                setActiveActionButton(null);
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
                      url: '{{ route('application.data', '') }}/' + applicationId,
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
                                          <td class="text-center">${formatDateRange(item)}</td>
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
