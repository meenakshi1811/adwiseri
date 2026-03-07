@extends('admin.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
            <div class="client-dashboard">
                <div class="client-btn d-flex mb-2 ">

                    {{-- <form class="form-inline justify-content-between align-items-center mt-3 w-100"> --}}
                        <h3 class="text-primary text-center flex-grow-1 text-center m-0">Subscribers</h3>
                        @if(!$user->is_support)
                        <p>

                          {{-- <a href="{{ route('subscribers_export') }}" class="m-0">Export</a> --}}
                          <a href="{{ route('new_subscriber') }}" class="m-0">Add New</a>
                        </p>
                        @endif
                        {{-- <div class="d-flex ">
                            <select name="filters" id="filter" class="form-control m-0">
                                <option value="">Filter</option>
                                <option value="">Name</option>
                                <option value="">Country</option>
                                <option value="">City</option>
                                <option value="">Pincode</option>
                                <option value="">Status</option>
                            </select>
                            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                        </div> --}}
                      {{-- </form> --}}
                      {{-- <i class="fa-solid fa-magnifying-glass"></i> --}}
                </div>
                {{-- <div class="col px-2 d-flex justify-content-between">
                    <div>
                        View
                        <select class="p-1" name="show_data" id="show_data">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div><p>
                        Displaying {{$subscribers->count()}} of {{ $subscribers->total() }} subscriber(s).
                    </p></div>
                </div> --}}
                <div class="table-wrapper">
                    <table class="fl-table table table-hover p-0 m-0" id="subscriberTable" width="100%">
                    {{-- <table class="table table-hover table-bordered fl-table" id="subscriberTable" width="100%"> --}}
                        <thead>
                        <tr>
                            <th class="text-center">Sr No.</th>
                            <th class="text-center">Sub_Name</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Phone</th>
                            <th class="text-center">Country</th>
                            {{-- <th>City</th> --}}
                            <th>City/Town</th>
                            <th class="text-center">Plan</th>
                            <th class="text-center">StartDate</th>
                            <th class="text-center">EndDate</th>
                            <th class="text-center">LOA</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($subscribers as $key => $subscriber)
                            <tr>
                                <td class="text-center">{{ $key+1 }}</td>
                                <td  data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $subscriber->name }}" class="text-center" style="position: relative;">@if(strlen($subscriber->name) > 10){{ substr($subscriber->name, 0, 22) }}... <span onmouseover="this.style.opacity='1';" onmouseout="this.style.opacity='0';" style="display:flex;opacity:0;align-items:center;padding:5px;position: absolute;left:0px;top:25px;height:100%;background:lightgrey;min-width:100%; width:fit-content;">{{$subscriber->name}}</span> @else {{$subscriber->name}} @endif</td>
                                <td  data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $subscriber->email }}" class="text-center" style="position: relative;">@if(strlen($subscriber->email) > 10){{ substr($subscriber->email, 0, 22) }}... <span onmouseover="this.style.opacity='1';" onmouseout="this.style.opacity='0';" style="display:flex;opacity:0;align-items:center;padding:5px;position: absolute;left:0px;top:25px;height:100%;background:lightgrey;min-width:100%; width:fit-content;">{{$subscriber->email}}</span> @else {{$subscriber->email}} @endif</td>
                                <td class="text-center">{{ $subscriber->phone }}</td>
                                <td class="text-center">{{ $subscriber->country }}</td>
                                {{-- <td>{{ $subscriber->city }}</td> --}}
                                <td class="text-center">{{ $subscriber->city }}</td>
                                <td class="text-center">{{ $subscriber->membership }}</td>
                                <td class="text-center">{{  \Carbon\Carbon::parse($subscriber->membership_start_date)->format('d-m-Y') }}</td>
                                <td class="text-center">{{  \Carbon\Carbon::parse($subscriber->membership_expiry_date)->format('d-m-Y') }}</td>
                                <td class="text-center">
                                    @if($subscriber->membership_start_date)
                                        @php
                                            $years = \Carbon\Carbon::parse($subscriber->membership_start_date)->diffInYears(\Carbon\Carbon::parse($subscriber->membership_expiry_date));
                                        @endphp

                                        @if($years > 0)
                                            {{ $years }} {{ $years == 1 ? 'year' : 'years' }}
                                        {{-- @else
                                            Less than a year --}}
                                        @endif
                                    {{-- @else
                                        N/A --}}
                                    @endif
                                                                </td>
                                <td class="text-center">@if($subscriber->status == 'true') <a style="background:green;border-color:green;" @if(!$user->is_support) href="{{ route('subscriber_status', $subscriber->id) }}" @endif class="p-0 px-1">Active</a> @else <a style="background:red;border-color:red;" @if(!$user->is_support) href="{{ route('subscriber_status', $subscriber->id) }}" @endif class="p-0 px-1">Deactivated</a> @endif</td>
                                <td class="text-center">
                                    <a style="background:transparent;border:none;" class=" p-0 m-0 text-dark" href="{{ route('view_user', $subscriber->id)}}"><i class="fa-solid fa-eye btn text-info p-1 m-0"></i></a>
                                    @if(!$user->is_support)
                                    <i class="fa-solid fa-edit btn text-primary p-1 m-0" style="font-size:14px;" onclick="updateuser({{ $subscriber->id }})"></i>
                                    <i class="fa-solid fa-trash btn p-1 text-danger" style="font-size:14px;" onclick="deleteuser({{ $subscriber->id }})"></i>
                                    @endif
                                </td>
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
        text: 'User Deleted Successfully!'
      })
    </script>

  @endif
  @if(session()->has('status_updated'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'Subscriber Status Changed Successfully!'
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
