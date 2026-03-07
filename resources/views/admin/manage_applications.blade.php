@extends('admin.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
            <div class="client-btn d-flex mb-2 ">
                {{-- <form class="form-inline d-flex justify-content-between w-100"> --}}
                    <h3 class="text-primary text-center flex-grow-1 text-center m-0">Applications</h3>
                    <p class="mt-2">

                      {{-- <a href="{{ route('applications_export') }}" class="m-0">Export</a> --}}
                      <a href="{{ route('new_application') }}" class="m-0">Add New</a>
                    </p>
                    {{-- <div class="d-flex ">
                        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                    </div> --}}
                  {{-- </form> --}}
                  {{-- <i class="fa-solid fa-magnifying-glass"></i> --}}
            </div>
            <div class="client-dashboard">
              <div class="row m-0 pb-2">
                <div class="col-3 border p-1 text-center bg-info text-white tab-anchor">
                  Applications
                </div>
                <div class="col-3 border p-1 text-center tab-anchor" onclick="window.location.href = '{{ route('documents') }}';">
                  Documents
                </div>
                <div class="col-3 border p-1 text-center tab-anchor" onclick="window.location.href = '{{ route('application_management') }}';">
                  Application Management
                </div>
                <div class="col-3 border p-1 text-center tab-anchor" onclick="window.location.href = '{{ route('application_tracking') }}';">
                  Application Tracking
                </div>
              </div>

                <div class="table-wrapper">
                    <table class="table table-hover table-bordered fl-table" id="clientTable">
                        <thead>
                        <tr>
                           <th class="text-center">Sr No.</th>
                            <th class="text-center">Sub_Name (Sub_ID)</th>
                            <th class="text-center"> Client_Name (Client ID)</th>
                            <th class="text-center">Application (ID)</th>
                            {{-- <th>Application Type</th> --}}
                            <th class="text-center">Visa Country</th>
                             <th class="text-center">Home Country</th>
                            <th class="text-center">Status</th>
                            <th class="squeeze-column text-center">Start Date</th>
                            <th class="squeeze-column text-center">End Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($applications as $key=>$app)
                        <tr>
                        <td class="text-center" style="position: relative;"> {{$key+1}} </td>
                            <td class="text-center" style="position: relative;">{{ $app->subscriber ? $app->subscriber->name . ' (' . $app->subscriber_id . ')' : '' }}</td>
                            <td class="text-center" style="position: relative;">{{ $app->client ? $app->client->name . ' (' . $app->client->id . ')' : '' }}</td>
                            <td class="text-center" style="position: relative;">{{  $app->application_name. ' (' .$app->application_id. ')'  }}</td>
                            {{-- <td style="position: relative;">@if(strlen($app->application_name) > 22){{ substr($app->application_name, 0, 22) }}... <span onmouseover="this.style.opacity='1';" onmouseout="this.style.opacity='0';" style="display:flex;opacity:0;align-items:center;padding:5px;position: absolute;left:0px;top:25px;height:100%;background:lightgrey;min-width:100%; width:fit-content;">{{$app->application_name}}</span> @else {{$app->application_name}} @endif</td> --}}

                            <td class="text-center">{{ $app->visa_country }}</td>
                            <td class="text-center">{{ $app->application_country }}</td>
                            <td class="text-center">{{ $app->application_status }}</td>
                            <td class="text-center">{{ date("d-m-Y", strtotime($app->start_date)) }}</td>
                            <td class="text-center" >@if($app->end_date != null){{ date("d-m-Y", strtotime($app->end_date)) }}@endif</td>
                            <td class="text-center">
                                <a style="background:transparent;border:none;" class="p-0 m-0 text-dark" href="{{ route('application_view', $app->id)}}"><i class="fa-solid fa-eye btn text-info p-1 m-0"></i></a>
                                <i class="fa-solid fa-edit btn text-primary p-1 m-0" style="font-size:14px;" onclick="updateapplication({{ $app->id }})"></i>
                                <i class="fa-solid fa-trash btn p-1 text-danger" style="font-size:14px;" onclick="deleteapplication({{ $app->id }})"></i>
                            </td>
                            {{-- <td>
                                <a class="p-1 text-dark" href=""><i class="fa-solid fa-eye"></i></a>
                                <i class="fa-solid fa-trash btn p-1 text-danger" style="font-size:14px;" onclick="deleteapplication({{ $app->id }})"></i>
                            </td> --}}
                        </tr>
                        @endforeach

                        <tbody>
                    </table>
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
  <script>
    function deleteapplication(id){
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = "delete_application/"+id+"";
        }
      })
    }
      function updateapplication(id){
        Swal.fire({
          title: 'Are you sure?',
          text: "You want to update this record!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = "application_update/"+id+"";
          }
        })
      }
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
