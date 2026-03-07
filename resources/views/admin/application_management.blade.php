@extends('admin.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
            <div class="client-dashboard">
                <div class="client-btn d-flex mb-2 ">
                    {{-- <form class="form-inline d-flex justify-content-between w-100"> --}}
                        {{-- <h3 class="text-primary">Application Management</h3> --}}
                        <h3 class="text-primary text-center flex-grow-1 text-center m-0">Application Management</h3>
                        <a href="{{ route('new_app_assignment') }}" class="m-0">Assign New</a>
                        {{-- <div class="d-flex ">
                            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                        </div> --}}
                      {{-- </form> --}}
                      {{-- <i class="fa-solid fa-magnifying-glass"></i> --}}
                </div>
              <div class="row m-0 p-2">
                <div class="col-3 border p-1 text-center tab-anchor" onclick="window.location.href = '{{ route('manage_applications') }}';">
                  Applications
                </div>
                <div class="col-3 border p-1 text-center tab-anchor" onclick="window.location.href = '{{ route('documents') }}';">
                  Documents
                </div>
                <div class="col-3 border p-1 text-center bg-info text-white tab-anchor">
                  Application Assignments
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
                            <th class="text-center">Sub_ID</th>
                            <th class="text-center">IMA</th>
                            <th class="text-center">ClientName (ID)</th>
                            <th class="text-center">Application Name (ID)</th>
                            <th class="text-center">Assigned To</th>
                            <th class="text-center">Assigned On</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($assignments as $key=>$assign)
                        <tr>
                        <td class="text-center"> {{ $key+1 }} </td>
                            <td class="text-center">
                                {{ $assign->subscriber_id ?? '' }} <!-- Replace with actual column name from clients -->
                            </td>
                            <td>
                                {{ $assign->user_name ? 'Yes' : 'No' }}
                            </td>
                            <td class="text-center">
                                {{ isset($assign->client_id) ? ($assign->client->name . '(' . $assign->client_id . ')') : '' }} <!-- Replace 'name' with the actual column name -->
                            </td>
                            <td class="text-center">
                                {{ $assign->application->application_name ?? '' }} ({{ $assign->application->id }}) <!-- Replace with actual column name for application name -->
                            </td>
                            <td>
                                {{ $assign->user_name  .'('. $assign->user_id.')'}}
                                <!-- {{ $assign->user_name ?? '' }} -->
                            </td>
                            <td>
                                {{ date("d-m-Y ", strtotime($assign->created_at)) }}
                            </td>
                            <td>
                                <i class="fa-solid fa-edit btn text-primary p-1 m-0" style="font-size:14px;" onclick="updateassignmentt({{ $assign->id }})"></i>
                                <i class="fa-solid fa-trash btn p-1 text-danger" style="font-size:14px;" onclick="deleteassignmentt({{ $assign->id }})"></i>
                            </td>
                        </tr>
                        {{-- <tr> --}}
                            {{-- <td class="text-center">{{$assign->client ? $assign->client->subscriber_id : '' }}</td>
                            <td>{{ $assign->user_name ? 'Yes' :'No' }}</td>
                            <td class="text-center">{{ $assign->client_id ?  $assign->client->name.'('.$assign->client_id.')' : ''; }}</td>
                            <td class="text-center"> {{$assign->application ? $assign->application->application_name : ''}} ({{  $assign->id }})</td>
                            <td>{{ $assign->user_name }}</td>
                            <td>{{ date("d-m-Y H:i:s", strtotime($assign->created_at)) }}</td>
                            <td> --}}
                                {{-- <a style="background:transparent;border:none;" class="p-0 m-0 text-dark" href="{{ route('application_view', $doc->id)}}"><i class="fa-solid fa-eye btn text-info p-1 m-0"></i></a> --}}
                                {{-- <i class="fa-solid fa-edit btn text-primary p-1 m-0" style="font-size:14px;" onclick="updateassignmentt({{ $assign->id }})"></i>
                                <i class="fa-solid fa-trash btn p-1 text-danger" style="font-size:14px;" onclick="deleteassignmentt({{ $assign->id }})"></i>
                            </td> --}}
                            {{-- <td>
                                <a class="p-1 text-dark" href=""><i class="fa-solid fa-eye"></i></a>
                                <i class="fa-solid fa-trash btn p-1 text-danger" style="font-size:14px;" onclick="deleteapplication({{ $app->id }})"></i>
                            </td> --}}
                        {{-- </tr> --}}
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
    function deleteassignmentt(id){
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
          window.location.href = "delete_app_assignment/"+id+"";
        }
      })
    }
      function updateassignmentt(id){
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
            window.location.href = "app_assignment_update/"+id+"";
          }
        })
      }
  </script>

  @if(session()->has('deleted'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'Application Assignment Deleted Successfully!'
      })
    </script>

  @endif
  @if(session()->has('assignment_added'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'New Assignment Added Successfully!'
      })
    </script>

  @endif
  @if(session()->has('assignment_updated'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'Assignment Updated Successfully!'
      })
    </script>

  @endif

@endsection()
