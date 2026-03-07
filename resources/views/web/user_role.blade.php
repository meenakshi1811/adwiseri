@extends('web.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
            <div class="client-dashboard">
              <div class="row m-0 pb-2">
                <div class="col-6 border p-1 text-center top_modules" onclick="window.location.href = '{{ route('users') }}';">
                  Users
                </div>
                <div class="col-6 border p-1 text-center bg-info text-white">
                  User Access Rights
                </div>
              </div>
                <div class="client-btn d-flex justify-content-between mb-4">
                    <h3 class="text-primary">User Access Rights</h3>
                    <a href="{{ route('add_user_role') }}">Add Access Rights+</a>
                </div>
                @if(count($roles) != 0)
                <div class="table-wrapper">
                    <table class="fl-table table table-hover p-0 m-0" id="clientTable">
                        <thead>
                        <tr>
                           <th class="p-1 text-center">Sr No.</th>
                            <th class="p-1 text-center">User Name</th>
                            <th class="p-1 text-center">Email</th>
                            {{-- <th>Access/Role</th> --}}
                            <th class="p-1 text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($roles as  $key=>$role)
                        <tr>
                        <td class="p-1 text-center">{{$key+1}}</td>
                            <td class="p-1 text-center"  style="position: relative;">{{$role['name']}}</td>
                            <td class="p-1 text-center"  style="position: relative;">{{$role['email']}}</td>
                            {{-- <td>{{ ($role->read_only != 0) ? 'Read':'' }}{{ ($role->write_only != 0) ? ', Insert':'' }}{{ ($role->update_only != 0) ? ', Update':'' }}{{ ($role->delete_only != 0) ? ', Delete':'' }}{{ ($role->read_write_only != 0) ? ', Read/Write ':'' }}</td> --}}
                            <td  class="p-1 text-center action-icon">
                                <a href="{{ route('add_user_role', $role['user_id']) }}" style="text-decoration:none;"><i class="fa-solid fa-edit btn p-1 text-info" style="font-size:14px;"></i></a>
                                <!--<a href="{{ route('delete_user_role', $role['user_id']) }}" style="text-decoration:none;"><i class="fa-solid fa-trash btn p-1 text-danger" style="font-size:14px;"></i></a>-->
                                {{-- <i class="fa-solid fa-trash btn p-1 text-danger" style="font-size:14px;" onclick="deleteuser({{ $role->id }})"></i> --}}
                            </td>
                        </tr>
                        @endforeach

                        <tbody>
                    </table>
                </div>
                @else
                <p class="text-secondary px-3">Access Rights Not Added...</p>
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
  function deleteuser(id){
      var localtime = new Date();
      var conf = confirm('Delete User');
      if(conf == true){
          window.location.href = "delete_role/"+id+"/"+localtime.toString()+"";
      }
  }
    function userstatus(id){
        var localtime = new Date();
        var conf = confirm('Change Status');
        if(conf == true){
            window.location.href = "subscriber_status/"+id+"/"+localtime.toString()+"";
        }
    }
</script>
@if(session()->has('all_access'))
  <script>
    Swal.fire({
      icon: 'info',
      title: 'Oops..',
      text: 'All users have assigned their access rights.'
    })
  </script>

@endif
@if(session()->has('no_user'))
  <script>
    Swal.fire({
      icon: 'info',
      title: 'Oops..',
      text: 'There is no user created yet.'
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
