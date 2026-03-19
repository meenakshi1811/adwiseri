@extends('admin.layout.main')

@section('main-section')

        @php
        if($invoice->user_id == null){
          $userid = 1;
        }
        else{
          $userid = $invoice->user_id;
        }
        @endphp
        <style>
    .invoice-box {
        background: #fff;
        padding: 30px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .invoice-box h3 {
        font-weight: 600;
        border-bottom: 2px solid #0061f2;
        padding-bottom: 10px;
        margin-bottom: 30px;
    }

     .invoice-header {
        display: flex;
        justify-content: flex-start; /* Align items to the left */
        align-items: center;
        margin-bottom: 20px;
    }

    .invoice-header img {
        max-height: 80px;
        object-fit: contain;
        margin-right: 20px; /* Space between logo and text if needed later */
    }

    .invoice-meta p {
        margin: 0;
        line-height: 1.5;
    }

    .table-invoice {
        width: 100%;
        border-collapse: collapse;
        margin-top: 30px;
    }

    .table-invoice th, .table-invoice td {
        border: 1px solid #ddd;
        padding: 12px 15px;
        text-align: left;
    }

    .table-invoice th {
        background-color: #f7f9fb;
        font-weight: 600;
    }

    .total-row td {
        font-weight: bold;
        background-color: #eef2f6;
    }

    .download-btn {
        background-color: #0061f2;
        color: #fff;
        padding: 8px 20px;
        border: none;
        border-radius: 4px;
        transition: 0.3s ease;
    }

    .download-btn:hover {
        background-color: #004ec2;
        cursor: pointer;
    }

    .note-box {
        background: #f8f9fa;
        padding: 15px;
        margin-top: 30px;
        border-left: 4px solid #0061f2;
        font-size: 0.95rem;
    }

    .text-right {
        text-align: right;
    }
</style>

<div class="col-lg-10 column-client">
    <div class="invoice-box">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                @if($invoice->subscriber_id)
                    <img src="{{ asset('web_assets/users/user'.$invoice->subscriber_id.'/' . $invoice->logo) }}" alt="Logo" style="max-height:70px; object-fit:contain;">
                @else
                    <img src="{{ asset('web_assets/users/user'.$invoice->user_id.'/' . $invoice->logo) }}" alt="Logo" style="max-height:70px; object-fit:contain;">
                @endif
            </div>
            <div>
                <button 
                    class="download-btn"
                        onclick="download_invoice({{ $invoice->id }})"
                >Download PDF</button>
            </div>
        </div>

        <h3 class="text-primary text-center">Invoice</h3>
        <div class="d-flex justify-content-center align-items-center mb-3">
            <span class="me-2" style="display:inline-flex; width:28px; height:28px;">
                @if($invoice->subscriber_id)
                    <img src="{{ asset('web_assets/users/user'.$invoice->subscriber_id.'/' . $invoice->logo) }}" alt="Logo" style="width:100%; height:100%; object-fit:contain;">
                @else
                    <img src="{{ asset('web_assets/users/user'.$invoice->user_id.'/' . $invoice->logo) }}" alt="Logo" style="width:100%; height:100%; object-fit:contain;">
                @endif
            </span>
            <strong>{{ $invoice->name }}</strong>
        </div>

        <!-- <div class="invoice-header mb-4">
           
            <div>
                @if($invoice->subscriber_id)
                    <img src="{{ asset('web_assets/users/user'.$invoice->subscriber_id.'/' . $invoice->logo) }}" alt="Logo">
                @else
                    <img src="{{ asset('web_assets/users/user'.$invoice->user_id.'/' . $invoice->logo) }}" alt="Logo">
                @endif
            </div>
        </div> -->

        <div class="row">
            <div class="col-6">
                <h6 class="mb-2"><strong>Bill To:</strong></h6>
                <p class="mb-1">{{ $invoice->to_name }}</p>
                <p class="mb-1">{{ $invoice->to_address }}</p>
                <p class="mb-1">{{ $invoice->to_city }}, {{ $invoice->to_state }}</p>
                <p class="mb-1">{{ $invoice->to_country }} - {{ $invoice->to_pincode }}</p>
            </div>
            <div class="col-6 text-right">
                <div class="invoice-meta">
                    <p>Invoice No.: <strong>{{ $invoice->invoice_no }}</strong></p>
                    <p>Date: <strong>{{ date('d-m-Y', strtotime($invoice->created_at)) }}</strong></p>
                </div>
            </div>
        </div>

        <table class="table-invoice">
            <thead>
                <tr>
                    <th class="p-1 text-center">Description</th>
                    <th class="p-1 text-center">Amount ({{ $user->currency }})</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="p-1 text-center">Professional Fees ({{ $invoice->detail }})</td>
                    <td class="p-1 text-center">{{ $invoice->amount }}</td>
                </tr>
                @if($invoice->discount != 0)
                <tr>
                    <td class="p-1 text-center">Discount ({{ $invoice->discount }}%)</td>
                    <td class="p-1 text-center">-{{ number_format($invoice->amount * ($invoice->discount / 100), 2) }}</td>
                </tr>
                @endif
                <tr>
                    <td class="p-1 text-center">Tax ({{ $invoice->tax }}%)</td>
                    <td class="p-1 text-center">{{ number_format(($invoice->amount - ($invoice->amount * ($invoice->discount / 100))) * ($invoice->tax / 100), 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td class="p-1 text-center" class="text-right">Total</td>
                    <td class="p-1 text-center">
                        @php
                           echo $total = $invoice->amount - ($invoice->amount * ($invoice->discount / 100)) + (($invoice->amount - ($invoice->amount * ($invoice->discount / 100))) * ($invoice->tax / 100));
                        @endphp
                    </td>
                </tr>
            </tbody>
        </table>
        @php
            $paymentLink = isset($invoiceSetting->payment_link) ? trim((string) $invoiceSetting->payment_link) : '';
        @endphp
        @if(!empty($paymentLink) && filter_var($paymentLink, FILTER_VALIDATE_URL))
            <div class="note-box">
                <p><strong>Payment Link:</strong> 
                    <a style="color: inherit !important;
    text-decoration: none !important;
    background: none !important; border: none;" target="_blank" href="{{ $paymentLink }}">{{ $paymentLink }}</a>
                </p>
            </div>
        @endif
        <div style="margin-top: 60px; text-align: center; font-size: 0.9rem; line-height: 1.6;">
            <div>
                Thanks for your business !
            </div>
            <!-- <div>
                {{ $invoice->address }}, {{ $invoice->city }}, {{ $invoice->state }}, {{ $invoice->country }} - {{ $invoice->pincode }}
            </div> -->
        </div>
    </div>
</div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
  </script>
  <script>
    function download_invoice(id){
        var a = window.open("{{ route('print_invoice_detail', $invoice->id) }}", 'Print Invoice', 'height=700, width=1440');
        // setTimeout(() => {
        //   a.print();
        //   a.window.close();
        // }, 1000);
    }
      $(document).ready(() => {
          $("#country").change(function(){
            var country = $(this).val();
            // console.log(counrty);
            $.ajax({
                url: 'get_states',
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    country: country,
                },
                cache:false,
                success: function(data){
                  console.log(data);
                    $("#state").html(data);
                }
            });
          });
          $("#subscriber").change(function(){
            var id = $(this).val();
            var name = 'subscriber';
            // console.log(counrty);
            $.ajax({
                url: 'get_job_role',
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                    name: name,
                },
                cache:false,
                success: function(data){
                  console.log(data);
                    $("#job_role").html(data);
                }
            });
          });
      });
  </script>
  <script>
      function deleteuser(id){
          var conf = confirm('Delete User');
          if(conf == true){
              window.location.href = "delete_user/"+id+"";
          }
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

@endsection()
