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
                <div class="client-btn d-flex justify-content-between mb-4">
                    <h3 class="text-primary px-3">Payment</h3>
                    <div><a @if($payment_roles->read_only == 1 or $payment_roles->read_write_only == 1) onclick="download_payment({{ $invoice->id }})" @endif class="m-0">Download</a></div>
                </div>
                <div class="col p-3">
                  <div class="row">
                    <div class="col-6">
                      <h4>{{ $invoice->company_name }}</h4>
                      <p class="m-1" style="line-height: 1;">{{ $invoice->city }}, {{ $invoice->state }}</p>
                      <p class="m-1" style="line-height: 1;">{{ $invoice->country }}, {{ $invoice->pincode }}</p>
                      <p class="m-1" style="line-height: 1;">{{ $invoice->phone }}</p>
                    </div>
                    <div class="col-6 d-flex justify-content-end">
                        <div class="row m-0" style="height: fit-content;">
                                <p class="col-6 m-0">Payment No :</p>
                                <p class="col-6 m-0">{{ $invoice->invoice }}</p>
                                <p class="col-6 m-0">Date :</p>
                                <p class="col-6 m-0">{{ date("d-m-Y", strtotime($invoice->created_at)) }}</p>
                        </div>
                    </div>
                  </div>
                  <h5 class="mt-5 px-3 py-1 border">Bill To</h5>
                  <div class="col px-3">
                    <p class="m-1">{{ $invoice->to_name }}</p>
                    <p class="m-1">{{ $invoice->to_company }}</p>
                    <p class="m-1">{{ $invoice->to_city }}, {{ $invoice->to_state }}</p>
                    <p class="m-1">{{ $invoice->to_country }}, {{ $invoice->to_pincode }}</p>
                    <p class="m-1">{{ $invoice->to_email }}</p>
                    <p class="m-1">{{ $invoice->to_phone }}</p>
                  </div>
                  <table class="fl-table table mt-5" style="border: 1px solid grey;">
                    <tr>
                      <th class="col-8">Description</th>
                      <th class="col-4">Amount ({{$user->currency}})</th>
                    </tr>
                    <tr>
                      <td>
                        <p class="m-1">Service Fee</p>
                        @if($invoice->payment_mode == "Wallet")
                        <p class="m-1">Wallet Credit Used</p>
                        @else
                        <p class="m-1">Discount</p>
                        @endif
                        <p class="m-1">Tax</p>
                      </td>
                      <td>
                        <p class="m-1">{{ $invoice->service_fee }}</p>
                        <p class="m-1">{{ $invoice->discount }}</p>
                        <p class="m-1">{{ $invoice->tax }}</p>
                      </td>
                    </tr>
                    <tr>
                      <td class="col text-end">Total</td>
                      <td>{{ $invoice->total }}</td>
                    </tr>
                  </table>
                  
                </div>
            </div>
        </div>
        
    </div>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
  function download_payment(id){
    var a = window.open("{{ route('print_payment', $invoice->id) }}", 'Print Payment', 'height=700, width=1440');
    // setTimeout(() => {
    //   a.print();
    //   a.window.close();
    // }, 1000);
  }
</script>
<script>
    function deleteuser(id){
        var conf = confirm('Delete User');
        if(conf == true){
            window.location.href = "delete_siteuser/"+id+"";
        }
    }
</script>
@if(session()->has('user_added'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Congratulations',
      text: 'User Added Successfully.'
    })
  </script>

@endif
@if(session()->has('deleted'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: 'User Deleted Successfully!'
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
