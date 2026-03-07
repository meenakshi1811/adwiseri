@extends('web.layout.main')

@section('main-section')
  
<div class="col-lg-10 column-client">
    @if(isset($application))
    <div class="client-dashboard">
        <div class="client-btn d-flex mb-2 ">
            <form class="form-inline d-flex justify-content-between w-100">
                <h3 class="text-primary"><b>{{ $application->application_name }}</b></h3>
            </form>
        </div>
        <div class="col">
            <div class="row px-3">
                <div class="col-md-4 p-1">
                    <label>Client ID</label>
                </div>
                <div class="col-md-8 p-1">
                    {{ $application->client_id }}
                </div>
                <div class="col-md-4 p-1">
                    <label>Application ID</label>
                </div>
                <div class="col-md-8 p-1">
                    {{ $application->application_id }}
                </div>
                <div class="col-md-4 p-1">
                    <label>Application</label>
                </div>
                <div class="col-md-8 p-1">
                    {{ $application->application_name }}
                </div>
                <div class="col-md-4 p-1">
                    <label>Application Country</label>
                </div>
                <div class="col-md-8 p-1">
                    {{ $application->application_country }}
                </div>
                <div class="col-md-4 p-1">
                    <label>Application Detail</label>
                </div>
                <div class="col-md-8 p-1">
                    {{ $application->application_detail }}
                </div>
                <div class="col-md-4 p-1">
                    <label>Application Start Date</label>
                </div>
                <div class="col-md-8 p-1">
                    {{ date("d-m-Y", strtotime($application->start_date)) }}
                </div>
                <div class="col-md-4 p-1">
                    <label>Application Status</label>
                </div>
                <div class="col-md-8 p-1">
                    {{ $application->application_status }}
                </div>
                <div class="col-md-4 p-1">
                    <label>Application End Date</label>
                </div>
                <div class="col-md-8 p-1">
                    @if($application->end_date != null)
                    {{ date("d-m-Y", strtotime($application->end_date)) }}
                    @endif
                </div>
            </div>
        </div>
        
    </div>
    @endif
</div>
    </div>

  </div>
  <script>
    function deleteapplication(id){
        var conf = confirm('Delete Application');
        if(conf == true){
            window.location.href = "delete_application/"+id+"";
        }
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
