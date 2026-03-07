@extends('admin.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
            <div class="client-dashboard">
                <div class="client-btn d-flex mb-2 ">
                    <form class="form-inline d-flex justify-content-between w-100">
                        <h3 class="text-primary">Feedbacks</h3>
                        {{-- <p>
                          <a href="{{ route('new_client') }}" class="m-0">Add New</a>
                          <a href="{{ route('clients_export') }}" class="m-0">Export</a>
                        </p> --}}
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
                            <th class="p-1 text-center">ID</th>
                            <th class="p-1 text-center">User Name(User_ID)</th>
                            <th class="p-1 text-center">Rating</th>
                            <th class="p-1 text-center">Feedback</th>
                            <th class="p-1 text-center">Feedback date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($feedbacks as $feedback)
                        <tr>
                            <td class="text-center">{{ $feedback->id }}</td>
                            <td class="text-center" style="position: relative;">{{ $feedback->user->name .'('.$feedback->user_id.')' }}</td>
                            <td class="text-center">{{ $feedback->rating }}</td>
                            <td class="text-center">{{ $feedback->feedback }}</td>
                            <td class="text-center">{{  \Carbon\Carbon::parse($feedback->created_at)->format('d-m-Y') }}</td>

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
            window.location.href = "delete_clients/"+id+"";
          }
        })
          // var conf = confirm('Delete Client');
          // if(conf == true){
          //     window.location.href = "delete_clients/"+id+"";
          // }
      }
      function updateclient(id){
        Swal.fire({
          title: 'Are you sure?',
          text: "You want to update record!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = "client_update/"+id+"";
          }
        })
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
  @if(session()->has('client_added'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'New Client Added Successfully!'
      })
    </script>

  @endif
  @if(session()->has('client_updated'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'Client Updated Successfully!'
      })
    </script>

  @endif

@endsection()
