@extends('admin.layout.main')

@section('main-section')

<div class="col-lg-10 column-client">
    <div class="col d-flex justify-content-between">
        <h3 class="text-primary text-center flex-grow-1 text-center m-0">Support</h3>
        <a href="{{ route('manage_faq') }}" style="height:fit-content;">FAQs</a>
    </div>
    <div class="col">
        <!-- <h5 class="px-2 m-0 mb-3">Tickets</h5> -->
        <h3 id="reportTitle1">Tickets</h3>
        <div class="table-wrapper">
            <table class="table table-hover table-bordered fl-table" id="clientTable">
                <thead>
                <tr>
                    <th class="text-center">Sr.No.</th>
                    <th class="text-center">Ticket ID</th>
                    <th class="text-center">SubscriberName(SubID)</th>
                    <th class="text-center">Issue</th>
                    <th class="text-center">Department</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">UserName(UserID)</th>
                    <th class="text-center">Date</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($tickets as $key => $tic)
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td class="text-center">{{ $tic->ticket_no }}</td>
                    <td class="text-center">{{ $tic->subscriber ? $tic->subscriber->name .'('.$tic->subscriber_id.')' : '' }}</td>
                    <td class="text-center"><div style="max-height: 80px;overflow:auto;">{{ $tic->issue }}</div></td>
                    <td class="text-center">{{ $tic->support }}</td>
                    <td class="text-center">@if($tic->status == 'Open') <a style="background:green;border-color:green;" href="{{ route('update_query_status', $tic->id) }}" class="p-0 px-1">Open</a> @else <a style="background:red;border-color:red;" href="{{ route('update_query_status', $tic->id) }}" class="p-0 px-1">Closed</a> @endif</td>
                    {{-- <td><a style="background:none;border:none;padding:0px;" onclick="{{ route('update_query_status', $tic->id) }}">{{ $tic->status }}</a></td> --}}
                    <td class="text-center">{{ $tic->served_by ? $tic->servedBy->name .'('.$tic->served_by.')' : "" }}</td>
                    <td class="text-center">{{ $tic->created_at }}</td>
                    <td class="text-center">
                        <a style="background:none; border:none;" onclick="window.location.href = '{{ route('view_query', $tic->id) }}';" class="m-0 p-0"><i class="fa-solid fa-eye btn p-1 text-info" style="font-size:14px;"></i></a>
                        <i class="fa-solid fa-edit btn p-1 text-success" onclick="queryresponse({{ $tic->id }})" style="font-size:14px;"></i>
                        <i class="fa-solid fa-trash btn p-1 text-danger" onclick="deletequery({{ $tic->id }})" style="font-size:14px;"></i>
                        <i class="fa-solid fa-user-plus btn p-1 text-primary" style="font-size:14px;"
                        data-bs-toggle="modal"
                        data-bs-target="#assignModal"
                        onclick="openAssignModal({{ $tic->id }})">

                     </i>
                    </td>
                </tr>
                @endforeach

                <tbody>
            </table>
        </div>
    </div>

</div>
    </div>

  </div>
<!-- Modal -->
<div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignModalLabel">Assign User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assignForm" method="POST" action="{{ route('assign_supports') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="ticket_id" id="ticketId">
                    <div class="form-group">
                        <label for="userSelect">Support Staff:</label>
                        <select name="user_id" id="userSelect" class="form-control" required>
                            <option value="">-- Select Support Staff --</option>
                            @foreach($supportStaff as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Assign</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>



  @if(session()->has('query_deleted'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'Query Deleted Successfully!'
      })
    </script>

  @endif
  @if(session()->has('success_assign'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: 'User assigned successfully.'
    })
  </script>

@endif

@endsection()

  @push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
  function deletequery(id){
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
          window.location.href = "delete_query/"+id+"";
        }
      })
    }
      function queryresponse(id){
        Swal.fire({
          title: 'Are you sure?',
          text: "You want to response this record!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = "query_response/"+id+"";
          }
        })
      }
      function openAssignModal(ticketId) {
        console.log('Assigning ticket ID:', ticketId);
        document.getElementById('ticketId').value = ticketId;
      }
  </script>
  @endpush
