@extends('web.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
            <div class="client-dashboard">
                <div class="col-12 d-flex justify-content-between align-items-center mb-3">

                    <h3 class="text-primary text-center flex-grow-1 text-center m-0">Users (Staff)</h3>
                    <p>
                        <a href="{{ route('add_user') }}">Add User</a>
                        @if(count($siteusers) != 0)
                        <a href="{{ route('export_users') }}">Export</a>
                        @endif
                </p>
                </div>

              <div class="row m-0 pb-2">
                <div class="col-6 border p-1 text-center bg-info text-white">
                  Users
                </div>
                <div class="col-6 border p-1 text-center top_modules" onclick="window.location.href = '{{ route('user_role') }}';">
                  User Access Rights
                </div>
              </div>

                @if(count($siteusers) != 0)
                <div class="table-wrapper">
                    <table class="fl-table table table-hover p-0 m-0" id="clientTable">
                        <thead>
                        <tr>
                            <th class="text-center">Sr No.</th>
                            <th class="text-center">User Name</th>
                            <th class="text-center">City</th>
                            <th class="text-center">Phone No</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Designation</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">View</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($siteusers as $key=>$siteuser)
                        <tr>
                            <td class="p-1 text-center">{{ $key+1 }}</td>
                            <td class="p-1 text-center"  data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $siteuser->name }}"  style="position: relative;">@if(strlen($siteuser->name) > 22){{ substr($siteuser->name, 0, 22) }}... <span onmouseover="this.style.opacity='1';" onmouseout="this.style.opacity='0';" style="display:flex;opacity:0;align-items:center;padding:5px;position: absolute;left:0px;top:25px;height:100%;background:lightgrey;min-width:100%; width:fit-content;">{{$siteuser->name}}</span> @else {{$siteuser->name}} @endif</td>
                            <td class="p-1 text-center">{{ $siteuser->city }}</td>
                            <td class="p-1 text-center">{{ $siteuser->phone }}</td>
                            <td class="p-1 text-center"  data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $siteuser->email }}"  style="position: relative;">@if(strlen($siteuser->email) > 22){{ substr($siteuser->email, 0, 22) }}... <span onmouseover="this.style.opacity='1';" onmouseout="this.style.opacity='0';" style="display:flex;opacity:0;align-items:center;padding:5px;position: absolute;left:0px;top:25px;height:100%;background:lightgrey;min-width:100%; width:fit-content;">{{$siteuser->email}}</span> @else {{$siteuser->email}} @endif</td>
                            <td class="p-1 text-center">{{ $siteuser->designation }}</td>
                            <td class="p-1 text-center">@if($siteuser->status == 'true') <a style="background:green;border-color:green;" href="#" onclick="userstatus({{ $siteuser->id }})" class="p-0 px-1">Active</a> @else <a style="background:red;border-color:red;" href="#" onclick="userstatus({{ $siteuser->id }})" class="p-0 px-1">Inactive</a> @endif</td>
                            <td class="p-1 text-center action-icon">
                                <a href="{{ route('siteuser_profile', $siteuser->id) }}" style="text-decoration:none;"><i class="fa-solid fa-eye btn p-1 text-info" style="font-size:14px;"></i></a>
                                {{-- <i class="fa-solid fa-trash btn p-1 text-danger" style="font-size:14px;" onclick="deleteuser({{ $siteuser->id }})"></i> --}}
                            </td>
                        </tr>
                        @endforeach

                        <tbody>
                    </table>
                </div>
                @else
                <p class="text-secondary px-3">Users Not Added...</p>
                @endif
                {{-- <div class="table-btn">
                    <button>Previous</button>
                    <button>1</button>
                    <button>2</button>
                    <button>3</button>
                    <button>Next</button>
                </div> --}}
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
      var localtime = new Date();
      var conf = confirm('Delete User');
      if(conf == true){
          window.location.href = "delete_siteuser/"+id+"/"+localtime.toString()+"";
      }
  }
    function userstatus(id){
        var localtime = new Date();
        var conf = confirm('Are you sure about activating/deactivating selected User ?');
        if(conf == true){
            window.location.href = "subscriber_status/"+id+"/"+localtime.toString()+"";
        }
    }
</script>
@if(session()->has('user_added'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Congratulations',
      text: 'User Added Successfully.'
    })
  </script>

@endif
@if(session()->has('deleted'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: 'User Deleted Successfully!'
    })
  </script>

@endif
@if(session()->has('user_limit'))
  <script>
    Swal.fire({
      icon: 'warning',
      title: 'User Limit Reached!',
      text: 'Upgrade membership to add more Users!',
      showCancelButton: true,
      confirmButtonText: 'Upgrade',
      cancelButtonText: 'Will do it later',
      buttonsStyling: true
    }).then((result) => {
      if (result.isConfirmed) {
        // Redirect to the upgrade page
        window.location.href = '{{ route('membership') }}'; // Replace with your actual upgrade URL
      }
    });
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
@endsection()
