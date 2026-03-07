@extends('admin.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
            <div class="client-dashboard">
                <div class="client-btn d-flex mb-2 ">
                    <form class="form-inline d-flex justify-content-between w-100">
                        <h3 class="text-primary">FAQs</h3>
                        <a href="{{ route('add_faq') }}">Add New</a>
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
                            <th>Sr.No.</th>
                            <th>Question</th>
                            <th>Answer</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($faqs as $key => $faq)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $faq->question }}</td>
                            <td><div style="max-height: 80px;overflow:auto;">{{ $faq->answer }}</div></td>
                            <td>{{ date("d-m-Y H:i:s", strtotime($faq->created_at)) }}</td>
                            <td class="text-center">
                                <a href="{{ route('update_faq', $faq->id) }}" style="background:none; border:none;" class="m-0 p-0"><i class="fa-solid fa-edit btn p-1 text-info" style="font-size:14px;"></i></a>
                                <a onclick="deletefaq({{ $faq->id }})" style="background:none; border:none;" class="m-0 p-0"><i class="fa-solid fa-trash btn p-1 text-danger" style="font-size:14px;"></i></a>
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
  <script>
      function deletefaq(id){
          var conf = confirm('Delete FAQ');
          if(conf == true){
              window.location.href = "delete_faq/"+id+"";
          }
      }
  </script>
  
  @if(session()->has('deleted'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'FAQ Deleted Successfully!'
      })
    </script>
  
  @endif
  
  @if(session()->has('faq_updated'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Congratulations',
        text: 'FAQ Updated Successfully!'
      })
    </script>
  
  @endif
  @if(session()->has('faq_added'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Congratulations',
        text: 'New FAQ Added Successfully!'
      })
    </script>
  
  @endif

@endsection()
            