@extends('admin.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
            <div class="client-dashboard">
                <div class="client-btn d-flex mb-2 ">
                    <form class="form-inline d-flex justify-content-between w-100">
                        <h3 class="text-primary">Demo Requests</h3>
                        {{-- <p>
                          <a href="{{ route('new_subscriber') }}" class="m-0">Add New</a>
                          <a href="{{ route('subscribers_export') }}" class="m-0">Export</a>
                        </p> --}}
                      </form>
                </div>
                <div class="table-wrapper">
                    <table class="table table-hover table-bordered fl-table" id="subscriberTable" width="100%">
                        <thead>
                        <tr>
                            <th>S.N.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Country</th>
                            <th>Job Title</th>
                            <th>Request Date</th>
                            <th>Service Date</th>
                            <th>Status</th>
                            {{-- <th>Action</th> --}}
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($demos as $key => $demo)
                            <tr>
                                <td class="text-center">{{ $key+1 }}</td>
                                <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $demo->name }}" class="text-center" style="position: relative;">@if(strlen($demo->name) > 10){{ substr($demo->name, 0, 10) }}... <span onmouseover="this.style.opacity='1';" onmouseout="this.style.opacity='0';" style="display:flex;opacity:0;align-items:center;padding:5px;position: absolute;left:0px;top:25px;height:100%;background:black;color:white;min-width:100%; width:fit-content;">{{$demo->name}}</span> @else {{$demo->name}} @endif</td>
                                <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $demo->email }}" class="text-center" style="position: relative;">@if(strlen($demo->email) > 10){{ substr($demo->email, 0, 10) }}... <span onmouseover="this.style.opacity='1';" onmouseout="this.style.opacity='0';" style="display:flex;opacity:0;align-items:center;padding:5px;position: absolute;left:0px;top:25px;height:100%;background:black;color:white;min-width:100%; width:fit-content;">{{$demo->email}}</span> @else {{$demo->email}} @endif</td>
                                <td class="text-center">{{ $demo->phone }}</td>
                                <td class="text-center">{{ $demo->country }}</td>
                                <td class="text-center">{{ $demo->job_title }}</td>
                                <td class="text-center">{{ date("d-m-Y", strtotime($demo->created_at)) }}</td>
                                <td class="text-center">{{ date("d-m-Y", strtotime($demo->updated_at)) }}</td>
                                <td class="text-center">@if($demo->status == 'true') <a style="background:green;border-color:green;" href="{{ route('demo_status', $demo->id) }}" class="p-0 px-1">Done</a> @else <a style="background:red;border-color:red;" href="{{ route('demo_status', $demo->id) }}" class="p-0 px-1">Pending</a> @endif</td>
                                {{-- <td>
                                    <a style="background:transparent;border:none;" class=" p-0 m-0 text-dark" href="{{ route('view_user', $demo->id)}}"><i class="fa-solid fa-eye btn text-info p-1 m-0"></i></a>
                                    <i class="fa-solid fa-edit btn text-primary p-1 m-0" style="font-size:14px;" onclick="updateuser({{ $demo->id }})"></i>
                                    <i class="fa-solid fa-trash btn p-1 text-danger" style="font-size:14px;" onclick="deleteuser({{ $demo->id }})"></i>
                                </td> --}}
                            </tr>
                            @endforeach
                        <tbody>
                    </table>
                    {{-- <div class="table-btn col-12 text-end">
                        {{ $subscribers->links('pagination::bootstrap-4') }}
                        <button>Previous</button>
                        <button>1</button>
                        <button>Next</button>
                    </div> --}}
                </div>

            </div>
        </div>
    </div>

  </div>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
    function deleteuser(id){
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
          window.location.href = "delete_user/"+id+"";
        }
      })
    }
      function updateuser(id){
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
            window.location.href = "update_subscriber/"+id+"";
          }
        })
      }
  </script>

  @if(session()->has('deleted'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'Demo Deleted Successfully!'
      })
    </script>

  @endif
  @if(session()->has('status_updated'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'Demo Status Changed Successfully!'
      })
    </script>

  @endif
  @if(session()->has('user_updated'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Congratulations',
        text: 'Subscriber Data Updated Successfully!'
      })
    </script>

  @endif
  @if(session()->has('user_added'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Congratulations',
        text: 'New Subscriber Added Successfully!'
      })
    </script>

  @endif

@endsection()
