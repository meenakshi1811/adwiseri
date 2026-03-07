
@extends('admin.layout.main')

@section('main-section')

        <div class="col-lg-10 column-client">
            <div class="client-dashboard">
                <div class="client-btn d-flex mb-2 ">
                    {{-- <form class="form-inline d-flex justify-content-between w-100"> --}}
                        {{-- <h3 class="text-primary">Payments</h3> --}}
                        <h3 class="text-primary text-center flex-grow-1 text-center m-0">Payments</h3>
                        {{-- <a href="{{ route('payments_export') }}" class="m-0">Export</a> --}}
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
                            <th class="text-center" >Sr No.</th>
                            <th class="text-center" >InvoiceID</th>
                            <th class="text-center" >Subscriber</th>
                            <th class="text-center" >Phone</th>
                            <th class="text-center" >Email</th>
                            <th class="text-center" >Amount</th>
                            <th class="text-center" >MOP</th>
                            <th class="text-center" >Discount</th>
                            <th class="text-center" >Tax</th>
                            <th class="text-center" >Total</th>
                            <th class="text-center" >Date</th>
                            <th class="text-center" >Download</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($payments as $key => $pay)
                        <tr>
                            <td class="text-center">{{ $key+1 }}</td>
                            <td class="text-center">{{ $pay->invoice }}</td>
                            <td  data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $pay->to_name }}" class="text-center" style="position: relative;">@if(strlen($pay->to_name) > 22){{ substr($pay->to_name, 0, 22) }}... <span onmouseover="this.style.opacity='1';" onmouseout="this.style.opacity='0';" style="display:flex;opacity:0;align-items:center;padding:5px;position: absolute;left:0px;top:25px;height:100%;background:lightgrey;min-width:100%; width:fit-content;">{{$pay->to_name}} ({{$pay->user_id}})</span> @else {{$pay->to_name}}  ({{$pay->user_id}})@endif</td>
                            <td class="text-center">{{ $pay->to_phone }}</td>
                            <td  data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $pay->to_email }}" class="text-center" style="position: relative;">@if(strlen($pay->to_email) > 22){{ substr($pay->to_email, 0, 22) }}... <span onmouseover="this.style.opacity='1';" onmouseout="this.style.opacity='0';" style="display:flex;opacity:0;align-items:center;padding:5px;position: absolute;left:0px;top:25px;height:100%;background:lightgrey;min-width:100%; width:fit-content;">{{$pay->to_email}}</span> @else {{$pay->to_email}} @endif</td>
                            <td class="text-center">{{ $pay->service_fee }}</td>
                            <td class="text-center">{{ $pay->payment_mode }}</td>
                            <td class="text-center">{{ $pay->discount }}</td>
                            <td class="text-center">{{ $pay->tax }}</td>
                            <td class="text-center">{{ $pay->total }}</td>
                            <td class="text-center">{{ date("d-m-Y", strtotime($pay->created_at)) }}</td>
                            <td class="text-center"><a style="background:none; border:none;" onclick="window.open('{{ route('print_payment', $pay->id) }}', 'Print Invoice', 'height=700, width=1440')" class="m-0 p-0"><i class="fa-solid fa-download btn p-1 text-info" style="font-size:14px;"></i></a></td>
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
     document.addEventListener("DOMContentLoaded", function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
    function download_invoice(id){
      var a = window.open("{{ route('print_invoice', $pay->id) }}", 'Print Invoice', 'height=700, width=1440');
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

