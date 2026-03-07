@extends('web.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
            <div class="client-dashboard">
                <div class="client-btn d-flex mb-4">
                    <a href="{{ route('add_job_role') }}">+Add Job Role</a>
                    <form class="form-inline">
                        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                      </form>
                      <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                @if(count($job_roles) != 0)
                <div class="table-wrapper" style="width:100%;overflow: auto;">
                    <table class="fl-table table table-hover">
                        <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Job Role</th>
                            <th>Added On</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        
                        @foreach($job_roles as $key => $job_role)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $job_role->job_role }}</td>
                            <td>{{ $job_role->created_at }}</td>
                            <td class="action-icon">
                                <a  onclick="update_job_role({{ $job_role->id }},'{{ $job_role->job_role }}')" style="text-decoration:none;"><i class="fa-solid fa-edit btn p-1 text-info" style="font-size:14px;"></i></a>
                                <i class="fa-solid fa-trash btn p-1 text-danger" style="font-size:14px;" onclick="deletejob({{ $job_role->id }})"></i>
                            </td>
                        </tr>
                        @endforeach
                        <tbody>
                    </table>
                </div>
                @else
                <p class="text-secondary px-3">No Job Roles Added...</p>
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
<div id="update_box" style="width:100%;display: none;flex-direction: column;position: fixed;top: 0;left: 0;height: 100vh;overflow: scroll; background: rgba(0, 0, 0, 0.3);">
    <button class="btn btn-danger" style="position:fixed;top:10px;right:20px;" onclick="document.getElementById('update_box').style.display='none';">Close</button>
    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-4 loginouter-box">
            <form class="details-box login-box" id="job_role_form" method="POST" action="{{ route('add_new_job_role') }}">
            @csrf
            <input type="hidden" name="local_time" class="localtime" />
            <input type="hidden" id="job_role_id" name="id" value="">
                <h3 class="mb-5 pt-3 text-center">Update Job Role</h3>
                <div class="log-img mb-5">
                @if($user->profile_img == "")
                <img src="{{ asset('web_assets/images/loginimg.png') }}" width="60" height="60" alt="">
                @else
                    <img src="{{ asset('web_assets/users/user'.$user->id.'/'.$user->profile_img) }}" width="60" height="60" alt="">
                @endif
                </div>
                <div class="mb-4">
                    <input id="job_role_job" minlength="3" maxlength="100" name="job_role" value="" @error('job_role') is-invalid @enderror required type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Name">           
                    @error('job_role')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <button type="submit" class="form-control btn btn-primary mb-4">Save</button>
                <!-- <a href="dashboard.html" class="btn btn-primary mb-4">Next</a> -->
                <!-- <p class="text-center reg-logbtn">Already have an account! <a href="{{ route('user_login') }}" class="text-dark"> <strong>Login</strong></a></p> -->
            </form>
        </div>
        <div class="col-lg-4"></div>
    </div>
</div>
<script>
    function update_job_role(id, job_role){
        document.getElementById('update_box').style.display='flex';
        document.getElementById('job_role_id').setAttribute('value', id);
        document.getElementById('job_role_job').setAttribute('value', job_role);
    }
    function deletejob(id){
        var conf = confirm('Delete Job Role');
        var localtime = new Date();
        if(conf == true){
            window.location.href = "delete_job_role/"+id+"/"+localtime.toString()+"";
        }
    }
</script>
@error('job_role')
<script>
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Please Enter valid Job Role.'
    })
</script>
@enderror
@if(session()->has('job_added'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Congratulations',
      text: 'Job Added Successfully.'
    })
  </script>

@endif
@if(session()->has('job_updated'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Congratulations',
      text: 'Job Updated Successfully.'
    })
  </script>

@endif
@if(session()->has('deleted'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: 'Job Deleted Successfully!'
    })
  </script>

@endif
@endsection()
