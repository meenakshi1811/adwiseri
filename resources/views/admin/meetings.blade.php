@extends('admin.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
            <div class="col mb-3 d-flex justify-content-between">
                <h3 class="text-primary text-center flex-grow-1 text-center m-0">Meeting Notes (Clients)</h3>
                {{-- @if(count($clients) > 0)
                <button type="button" id="add_new" class="btn btn-info text-white">Add New</button>
                @else
                <button type="button" id="add_new_zero" class="btn btn-info text-white">Add New</button>
                @endif --}}
                <button type="button" id="back" class="btn btn-info text-white" style="display: none;">Back</button>
              </div>
            <div class="row m-0 pb-2">
                <div class="col-4 border p-1 text-center top_modules tab-anchor" style="cursor: pointer;" onclick="window.location.href = '{{ route('admin_messaging') }}';">
                    Messaging
                  </div>
                <div class="col-4 border p-1 text-center bg-info text-white top_modules tab-anchor">
                  Meeting Notes (Clients)
                </div>

                <div class="col-4 border p-1 text-center top_modules tab-anchor" style="cursor: pointer;" onclick="window.location.href = '{{ route('communication') }}';">
                    Communication
                  </div>
              </div>


              <div class="table-wrapper">
                <table id="userTable" class="table table-hover fl-table">
                    <thead>
                    <tr>
                        <th class="text-center">Sr No.</th>
                        <th class="text-center">Subscriber Name</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">No. of Meeting Notes</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($subscribers as $key => $subs)
                        <tr>
                            <td class="text-center">{{ $key+1 }}</td>
                            <td class="text-center">{{ $subs->name }}</td>
                            <td class="text-center">{{ $subs->email }}</td>
                            <td class="text-center">

                                @php
                                $discussionCounts = $subs->clientDiscussionsBySubscriber; // Access the relationship
                                $aggregatedCounts = [];
                                foreach ($discussionCounts as $discussion) {
                                    if (isset($aggregatedCounts[$discussion->communication_type])) {
                                        $aggregatedCounts[$discussion->communication_type] += $discussion->count;
                                    } else {
                                        $aggregatedCounts[$discussion->communication_type] = $discussion->count;
                                    }
                                }
                            @endphp

                            @if (!empty($aggregatedCounts))
                                @foreach ($aggregatedCounts as $type => $count)
                                    <p>{{ $type }} ({{ $count }})</p>
                                @endforeach

                            @endif
                            </td>
                            <td class="text-center"><a href="{{ route('notes', $subs->id) }}" class="btn btn-primary m-0 p-0 px-2">View Notes</td>
                        </tr>
                    @endforeach
                    <tbody>
                </table>
            </div>

        </div>

    </div>

  </div>


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
  </script>
  <script>
      $(document).ready(() => {

        $("#add_new_zero").click(function(){
            Swal.fire({
            icon: 'info',
            title: 'Oops...',
            text: 'There is no client created.'
            });
        });

        $("#add_new").click(function(){
            $("#add_new").css('display','none');
            $("#back").css('display','block');
            $("#new_discussion").css('display','block');
            $("#discussions").css('display','none');
        });
        $("#back").click(function(){
            $("#add_new").css('display','block');
            $("#back").css('display','none');
            $("#new_discussion").css('display','none');
            $("#discussions").css('display','block');
        });

          $("#client").change(function(){
            var id = $(this).val();
            var comm = "communication";
            // console.log(counrty);
            $.ajax({
                url: 'get_application',
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                    comm: comm,
                },
                cache:false,
                success: function(data){
                  console.log(data);
                    $("#application").html(data);
                }
            });
          });
      });
  </script>
@if(session()->has('success'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Congratulations',
      text: 'User Added Successfully.'
    })
  </script>

@endif

@endsection()
