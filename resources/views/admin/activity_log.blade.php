@extends('admin.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
            <div class="client-dashboard">
                <div class="client-btn d-flex mb-2 ">
                    <form class="form-inline d-flex justify-content-between w-100">
                        <h3 class="text-primary text-center flex-grow-1 text-center m-0">Activity Logs</h3>
                        {{-- <div class="d-flex ">
                            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                        </div> --}}
                      </form>
                      {{-- <i class="fa-solid fa-magnifying-glass"></i> --}}
                </div>
                <div class="table-wrapper">
                    <table class="table table-hover table-bordered fl-table" id="clientTable">
                        <thead>
                        <tr>
                            <th class="text-center">Sr No.</th>
                            <th class="text-center">Activity</th>
                            <th class="text-center">User</th>
                            <th class="text-center">Detail</th>
                            <th class="text-center">Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($activities as $key => $act)
                        <tr>
                            <td class="text-center">{{ $key+1 }}</td>
                            <td class="text-center">{{ $act->activity_name }}</td>
                            <td class="text-center">{{ $act->user_name }}</td>
                            <td class="text-center">{{ $act->activity_detail }}</td>
                            <td class="text-center">{{ date("d-m-Y H:i:s", strtotime($act->created_at)) }}</td>
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
      function deleteclient(id){
          var conf = confirm('Delete Client');
          if(conf == true){
              window.location.href = "delete_clients/"+id+"";
          }
      }
  </script>

  @if(session()->has('deleted'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'Client Deleted Successfully!'
      })
    </script>

  @endif

@endsection()
