@extends('admin.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
            <div class="client-dashboard">
                <div class="client-btn d-flex mb-2 ">
                    {{-- <form class="form-inline d-flex justify-content-between w-100">
                        <h3 class="text-primary">Invoices</h3> --}}
                        <h3 class="text-primary text-center flex-grow-1 text-center m-0">Invoices</h3>
                        <p class="mt-1">

                          {{-- <a href="{{ route('invoices_export') }}" class="m-0">Export</a> --}}
                          <a href="{{ route('admin_new_invoice') }}" class="m-0">Create New</a>
                        </p>
                        {{-- <div class="d-flex ">
                            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                        </div> --}}
                      {{-- </form> --}}
                      {{-- <i class="fa-solid fa-magnifying-glass"></i> --}}
                </div>
                <div class="table-wrapper">
                    <table class="table table-hover table-bordered fl-table" id="clientTable">
                        <thead>
                        <tr>
                            <th class="text-center">SrNo</th>
                            <th class="text-center">InvoiceID</th>
                            <th class="text-center">Sub_ID</th>
                            <th class="text-center">Sub_Name/Client(ID)</th>
                            {{-- <th>Phone</th> --}}
                            <th class="text-center">Service(s) offered</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Discount</th>
                            <th class="text-center">Tax</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Due Date</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($invoices as $key => $invoice)
                        <tr>
                            <td>{{ $key+1 }} </td>
                            <td>{{ $invoice->invoice_no }}</td>
                            <td>{{ $invoice->subscriber_id }}</td>
                            <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $invoice->to_name }}" style="position: relative;">
                              @if(strlen($invoice->to_name) > 22)
                                {{ substr($invoice->to_name, 0, 22) }}... 
                                <span onmouseover="this.style.opacity='1';" onmouseout="this.style.opacity='0';" style="display:flex;opacity:0;align-items:center;padding:5px;position: absolute;left:0px;top:25px;height:100%;background:lightgrey;min-width:100%; width:fit-content;">
                                {{$invoice->to_name}} ({{ $invoice->subscriber_id }})
                              </span> 
                              @else
                               {{$invoice->to_name}} ({{ $invoice->subscriber_id }})
                              @endif</td>
                            {{-- <td>{{ $invoice->subscriber_id }}</td> --}}
                            <td>{{ $invoice->detail }}</td>
                            {{-- <td style="position: relative;">@if(strlen($invoice->to_email) > 22){{ substr($invoice->to_email, 0, 22) }}... <span onmouseover="this.style.opacity='1';" onmouseout="this.style.opacity='0';" style="display:flex;opacity:0;align-items:center;padding:5px;position: absolute;left:0px;top:25px;height:100%;background:lightgrey;min-width:100%; width:fit-content;">{{$invoice->to_email}}</span> @else {{$invoice->to_email}} @endif</td> --}}
                            <td class="text-center">{{ $invoice->amount }}</td>
                            <td>{{ $invoice->discount }}%</td>
                            <td>{{ $invoice->tax }}%</td>
                            <td>{{ $invoice->total }}</td>
                            <td>
                              <select class="form-control" id="inv_status{{$invoice->id}}" style="font-size: 14px;">
                              <option {{($invoice->status == "PartiallyPaid") ? "selected":""}} value="PartiallyPaid">PartiallyPaid</option>    
                              <option {{($invoice->status == "Paid") ? "selected":""}} value="Paid">Paid</option>
                                  <option {{($invoice->status == "UnPaid") ? "selected":""}} value="UnPaid">UnPaid</option>
                                  <option {{($invoice->status == "Cancelled") ? "selected":""}} value="Cancelled">Cancelled</option>
                              </select>
                            </td>
                            <td>{{ date("d-m-Y", strtotime($invoice->due_date)) }}</td>
                            <td><a style="background:none; border:none;" href="{{ route('invoice_detail', $invoice->id) }}" class="m-0 p-0"><i class="fa-solid fa-eye btn p-1 text-info" style="font-size:14px;"></i></a></td>
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
  {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"> --}}
  </script>
  <script>
     document.addEventListener("DOMContentLoaded", function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
    function download_invoice(id){
      var a = window.open("{{ route('print_invoice', "+id+") }}", 'Print Invoice', 'height=700, width=1440');
      // setTimeout(() => {
      //   a.print();
      //   a.window.close();
      // }, 1000);
    }
      function deleteclient(id){
          var conf = confirm('Delete Client');
          if(conf == true){
              window.location.href = "delete_clients/"+id+"";
          }
      }

        @foreach ($invoices as $key => $invoice)
            $("#inv_status{{$invoice->id}}").on("change",function(){
                var local_time = new Date();
                var id = {{$invoice->id}};
                var status = $(this).val();
                var localtime = local_time.toString();
                $.ajax({
                    url : "{{route('invoice_status')}}",
                    method: "POST",
                    data:{
                        "_token" : "{{ csrf_token() }}",
                        "id" : id,
                        "status" : status,
                        "localtime" : localtime,
                    },
                    cache: false,
                    success: function(data){
                        if(data.status == "success"){
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Invoice Status Updated Successfully!'
                            })
                        }
                    }
                })
            });
        @endforeach
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
  @if(session()->has('nouser'))
    <script>
      Swal.fire({
        icon: 'info',
        title: 'Oops...',
        text: 'Invoice user do not exists any more.';
      })
    </script>

  @endif

@endsection()
