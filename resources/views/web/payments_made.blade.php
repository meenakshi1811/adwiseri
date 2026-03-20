@extends('web.layout.main')

@section('main-section')
@php

use App\Models\UserRoles;
$client_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Clients')->first();
$application_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Applications')->first();
$communication_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Communication')->first();
$invoice_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Invoices')->first();
$payment_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Payments')->first();
$report_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Reports')->first();
$subscription_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Subscription')->first();
$setting_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Settings')->first();
$support_roles = UserRoles::where('user_id','=',$user->id)->where('module','=','Support')->first();
@endphp

        <div class="col-lg-10 column-client">
            <div class="client-dashboard">

                <div class="client-btn d-flex justify-content-between align-items-center mt-3 ">
                    <form class="form-inline d-flex justify-content-between w-100">
                        <h3 class="text-primary text-center flex-grow-1 text-center m-0">Payments</h3>
                        @if(!$user->is_support)
                        <p>
                            <a href="{{ route('add_ar_payments') }}" class="m-0">Add AR (Payments Received) Record</a>
                            <a href="{{ route('add_ap_payments') }}" class="m-0">Add AP (Payments Made) Record</a>
                          </p>
                    @endif

                    {{-- <form class="form-inline d-flex justify-content-between w-100">
                        <h3 class="text-primary">Payments</h3>
                        <a href="{{ route('add_ar_payments') }}" class="m-0">Add (AR)</a>
                        <a href="{{ route('payments_export') }}" class="m-0">Add (Ap)</a>
                        {{-- <div class="d-flex ">
                            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                        </div> --}}
                      {{-- </form>  --}}
                      {{-- <i class="fa-solid fa-magnifying-glass"></i> --}}
                </div>
                <div class="row m-0 pb-2">


                    <div class="col-6 border p-1 text-center    text-center tab-anchor top_modules"   onclick="window.location.href = '{{ route('my_payments') }}';">
                        Accounts Receivables &nbsp;&nbsp;  (Payments Received)
                    </div>
                    <div class="col-6 border p-1 text-center text-white bg-info">
                        Accounts Payable &nbsp;&nbsp; (Payments Made)
                      </div>

                 </div>


                @if(count($paymentAP) != 0)
                  <div class="table-wrapper">
                  <table class="fl-table table table-hover p-0 m-0" id="clientTable">
                    <thead>
                      <tr>
                        <th class="p-1 text-center">Sr No.</th>
                        <th class="p-1 text-center">InvoiceID</th>
                        <th class="p-1 text-center">Vendor Name (ID)</th>
                        <th class="p-1 text-center">Product/Service Taken</th>
                        <th class="p-1 text-center">Payment Mode</th>
                        <th class="p-1 text-center">Amount To Pay</th>
                        <th class="p-1 text-center">Paid Amount</th>
                        <th class="p-1 text-center">Outstanding</th>
                        <th class="p-1 text-center">Payment Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($paymentAP as $key => $payment)
                        <tr 
                          data-invoice-no="{{ $payment->invoice_no }}"
                          data-client-id="{{ $payment->client_id }}" 
                          data-application-id="{{ $payment->application_id }}"
                          data-created-at="{{ $payment->created_at->toDateTimeString() }}">
                          <td class="p-1 text-center">{{ $key+1 }}</td>
                          <td class="p-1 text-center">{{ $payment->invoice_no }}</td>
                          <td class="p-1 text-center">
                            {{ $payment->service_provider ? $payment->service_provider .'('.$payment->client_id.')' : ''}}
                          </td>
                          <td class="p-1 text-center">
                            {{ $payment->service_taken }}
                          </td>
                          <td class="p-1 text-center">{{ $payment->payment_mode }}</td>
                          <td class="p-1 text-center amount">{{ $payment->amount }}</td>
                          <td class="p-1 text-center paid">{{ $payment->paid_amount }}</td>
                          <td class="p-1 text-center outstanding">{{ $payment->amount - $payment->paid_amount }}</td>
                          <td class="p-1 text-center">
                            {{ \Carbon\Carbon::parse($payment->payment_date)->format('d-m-Y') }}
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>

                {{-- Add this JS after your table --}}
                <script>
                  document.addEventListener('DOMContentLoaded', function() {
                      // parse ISO datetime from data-created-at (fallback to epoch)
                      function parseIsoDate(dateStr) {
                          if (!dateStr) return new Date(0);
                          const t = Date.parse(dateStr);
                          return isNaN(t) ? new Date(0) : new Date(t);
                      }

                      let rows = Array.from(document.querySelectorAll('#clientTable tbody tr'));
                      let grouped = {};

                      rows.forEach((row, domIndex) => {
                          let invoiceNo = row.dataset.invoiceNo || '';
                          let key = invoiceNo;

                          // numeric parsing (strip commas/currency)
                          let amountText = (row.querySelector('.amount')?.textContent || '').replace(/[^0-9.\-]/g, '');
                          let paidText   = (row.querySelector('.paid')?.textContent   || '').replace(/[^0-9.\-]/g, '');

                          let amount = parseFloat(amountText) || 0;
                          let paid = parseFloat(paidText) || 0;

                          // use data-created-at attribute (full timestamp)
                          let createdAt = row.dataset.createdAt || '';
                          let dateObj = parseIsoDate(createdAt);

                          if (!grouped[key]) grouped[key] = { totalAmount: amount, rows: [] };

                          // keep maximum amount seen for that group (fallback)
                          grouped[key].totalAmount = Math.max(grouped[key].totalAmount || 0, amount);

                          grouped[key].rows.push({ row, paid, dateObj, domIndex });
                      });

                      Object.keys(grouped).forEach(key => {
                          let entry = grouped[key];

                          // stable sort by real timestamp (oldest first), then DOM index as tie-breaker
                          entry.rows.sort((a, b) => {
                              if (a.dateObj < b.dateObj) return -1;
                              if (a.dateObj > b.dateObj) return 1;
                              return a.domIndex - b.domIndex;
                          });

                          // compute cumulative and write remaining AFTER each payment
                          let cumulativePaid = 0;
                          entry.rows.forEach(item => {
                              cumulativePaid += item.paid;
                              let remaining = entry.totalAmount - cumulativePaid;
                              if (remaining < 0) remaining = 0;
                              let outEl = item.row.querySelector('.outstanding');
                              if (outEl) outEl.innerText = remaining.toFixed(2);
                          });
                      });
                  });
                  </script>
                @else
                <p class="text-secondary px-3">No Payment Records to show</p>
                @endif
            </div>
        </div>

    </div>

</div>
<script>
    function deleteinvoice(id){
      var localtime = new Date();
        var conf = confirm('Delete Invoice');
        if(conf == true){
            window.location.href = "delete_payment/"+id+"/"+localtime.toString()+"";
        }
    }
</script>

@if(session()->has('advance_payment'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: "{{ session('advance_payment') }}" // Wrap in double quotes
    });
  </script>
@endif
@if(session()->has('user_added'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: 'User Added Successfully.'
    })
  </script>

@endif
@if(session()->has('deleted'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: 'Payment Deleted Successfully!'
    })
  </script>

@endif
@if(session()->has('user_limit'))
  <script>
    Swal.fire({
      icon: 'warning',
      title: 'User Limit!',
      text: 'Upgrade membership to add more Users!'
    })
  </script>

@endif
@endsection()
