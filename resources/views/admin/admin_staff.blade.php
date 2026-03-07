@extends('admin.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
            <div class="client-dashboard">
                <div class="client-btn d-flex mb-2 ">
                    <form class="form-inline d-flex justify-content-between w-100">
                        <h3 class="text-primary">Admin (Staff)</h3>
                        <p class="mt-1">
                          <a href="{{ route('admin_new_staff') }}" class="m-0">Add New</a>
                          {{-- <a href="{{ route('users_export') }}" class="m-0">Export</a> --}}
                        </p>
                        {{-- <div class="d-flex ">
                            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                        </div> --}}
                      </form>
                      {{-- <i class="fa-solid fa-magnifying-glass"></i> --}}
                </div>
                <div class="table-wrapper">
                    <table class="table table-hover table-bordered fl-table" id="userTable">
                        <thead>
                        <tr>
                        <tr>
                            <th class="p-1 text-center" >#</th>
                            {{-- <th class="p-1 text-center" >UserID</th> --}}
                            <th class="p-1 text-center" >Name</th>
                            <th class="p-1 text-center" >Email</th>
                            <th class="p-1 text-center" >Phone</th>
                            <th class="p-1 text-center" >Country</th>
                            <th class="p-1 text-center" >City</th>
                            <th class="p-1 text-center" >Designation</th>
                            <th class="p-1 text-center" >Created </th>
                            <th class="p-1 text-center" >Status</th>
                            <th class="p-1 text-center" >Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($siteusers as $key => $siteuser)
                        <tr>
                            <td class="text-center">{{ $key+1 }}</td>
                            {{-- <td>{{ $siteuser->id }}</td> --}}
                            <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $siteuser->name }}" class="text-center" style="position: relative;">@if(strlen($siteuser->name) > 22){{ substr($siteuser->name, 0, 22) }}... <span onmouseover="this.style.opacity='1';" onmouseout="this.style.opacity='0';" style="display:flex;opacity:0;align-items:center;padding:5px;position: absolute;left:0px;top:25px;height:100%;background:lightgrey;min-width:100%; width:fit-content;">{{$siteuser->name}} ({{ $siteuser->id }})</span> @else {{$siteuser->name}} ({{ $siteuser->id }}) @endif</td>
                            <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $siteuser->email }}" class="text-center" style="position: relative;">@if(strlen($siteuser->email) > 22){{ substr($siteuser->email, 0, 22) }}... <span onmouseover="this.style.opacity='1';" onmouseout="this.style.opacity='0';" style="display:flex;opacity:0;align-items:center;padding:5px;position: absolute;left:0px;top:25px;height:100%;background:lightgrey;min-width:100%; width:fit-content;">{{$siteuser->email}}</span> @else {{$siteuser->email}} @endif</td>
                            <td class="text-center">{{ $siteuser->phone }}</td>
                            <td class="text-center">{{ $siteuser->country }}</td>
                            <td class="text-center">{{ $siteuser->city }}</td>
                            <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $siteuser->designation }}" class="text-center" style="position: relative;">@if(strlen($siteuser->designation) > 22){{ substr($siteuser->designation, 0, 22) }}... <span onmouseover="this.style.opacity='1';" onmouseout="this.style.opacity='0';" style="display:flex;opacity:0;align-items:center;padding:5px;position: absolute;left:0px;top:25px;height:100%;background:lightgrey;min-width:100%; width:fit-content;">{{$siteuser->designation}}</span> @else {{$siteuser->designation}} @endif</td>
                            <td class="text-center">{{  \Carbon\Carbon::parse($siteuser->created_at)->format('d-m-Y') }}</td>
                            <td class="text-center">@if($siteuser->status == 'true') <a style="background:green;border-color:green;" href="{{ route('subscriber_status', $siteuser->id) }}" class="p-0 px-1">Active</a> @else <a style="background:red;border-color:red;" href="{{ route('subscriber_status', $siteuser->id) }}" class="p-0 px-1">Inactive</a> @endif</td>
                            <td class="text-center">
                                <a style="background:transparent;border:none;" class="p-0 m-0 text-dark" href="{{ route('view_user', $siteuser->id)}}"><i class="fa-solid fa-eye btn text-info p-1 m-0"></i></a>
                                <i class="fa-solid fa-edit btn text-primary p-1 m-0" style="font-size:14px;" onclick="updateuser({{ $siteuser->id }})"></i>
                                <i class="fa-solid fa-trash btn p-1 text-danger" style="font-size:14px;" onclick="deleteuser({{ $siteuser->id }})"></i>
                            </td>
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


  @if(session()->has('deleted'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'User Deleted Successfully!'
      })
    </script>

  @endif
  @if(session()->has('status_updated'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'User Status Changed Successfully!'
      })
    </script>

  @endif
  @if(session()->has('user_updated'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Congratulations',
        text: 'User Data Updated Successfully!'
      })
    </script>

  @endif
  @if(session()->has('admin_staff_added'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Congratulations',
        text: 'New Staff Added Successfully!'
      })
    </script>

  @endif

@endsection()

@push('scripts')
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
          window.location.href = "siteuser_update/"+id+"";
        }
      })
    }
</script>
@endpush
