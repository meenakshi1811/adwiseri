<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Invoice</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('web_assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

    <style>
    body {
        background: #f9f9f9;
        font-family: 'Lato', sans-serif;
        padding: 30px;
    }

    .invoice-container {
        max-width: 900px;
        margin: auto;
        background: #ffffff;
        padding: 30px 35px;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    .invoice-header {
        border-bottom: 2px solid #0061f2;
        padding-bottom: 15px;
        margin-bottom: 25px;
        page-break-inside: avoid;
    }

    .invoice-logo img {
        max-height: 60px;
        border-radius: 6px;
        margin-right: 15px; /* space from title if needed */
    }


    .table-invoice {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
        page-break-inside: avoid;
    }

    .table-invoice th,
    .table-invoice td {
        padding: 10px 14px;
        border: 1px solid #dee2e6;
        font-size: 0.95rem;
    }

    .table-invoice thead th {
        background: #f1f3f5;
        font-weight: 600;
    }

    .note-box {
        background: #f8f9fa;
        padding: 15px 20px;
        border-left: 5px solid #0061f2;
        margin-top: 20px;
        font-size: 0.95rem;
        page-break-inside: avoid;
    }

    @media print {
        body {
            background: white;
            padding: 0;
            margin: 0;
        }

        .invoice-container {
            box-shadow: none;
            padding: 20px;
            max-width: 100%;
            page-break-inside: avoid;
        }

        .invoice-header,
        .note-box,
        .table-invoice {
            page-break-inside: avoid;
        }

        .row,
        .col-md-6 {
            break-inside: avoid;
        }
    }
</style>

</head>

<body>
    @php
        $userid = $invoice->user_id ?? 1;
    @endphp

    <div class="invoice-container">
        <div class="invoice-header d-flex justify-content-between align-items-center">
            <div class="invoice-logo">
                @if($u->user_type === "Subscriber" || $u->user_type === "admin")
                    <img src="{{ asset('web_assets/users/user' . $userid . '/' . $invoice->logo) }}" alt="Logo">
                @else
                    <img src="{{ asset('web_assets/users/user' . $u->added_by . '/' . $invoice->logo) }}" alt="Logo">
                @endif
            </div>
            <div>
                <h3 class="text-primary mb-0">Invoice</h3>
            </div>
        </div>


        <div class="row mb-4 ">
            <div class="col-md-6">
                <!-- <h5 class="mb-2">{{ $invoice->name }}</h5>
                <p class="mb-1">{{ $invoice->address }}</p>
                <p class="mb-1">{{ $invoice->city }}, {{ $invoice->state }}</p>
                <p class="mb-1">{{ $invoice->country }} - {{ $invoice->pincode }}</p>
                <p class="mb-1">{{ $invoice->email }}</p>
                <p class="mb-1">{{ $invoice->phone }}</p> -->
            </div>
            <div class="col-md-6 text-end">
                <p class="mb-1"><strong>Invoice No:</strong> {{ $invoice->invoice_no }}</p>
                <p class="mb-1"><strong>Date:</strong> {{ date('d-m-Y', strtotime($invoice->created_at)) }}</p>
            </div>
        </div>

        <h5 class="py-2 border-bottom mb-3"><strong>Bill To</strong></h5>
        <div class="mb-4">
            <p class="mb-1">{{ $invoice->to_name }}</p>
            <p class="mb-1">{{ $invoice->to_address }}</p>
            <p class="mb-1">{{ $invoice->to_city }}, {{ $invoice->to_state }}</p>
            <p class="mb-1">{{ $invoice->to_country }} - {{ $invoice->to_pincode }}</p>
            <p class="mb-1">{{ $invoice->to_email }}</p>
            <p class="mb-1">{{ $invoice->to_phone }}</p>
        </div>

        <table class="table-invoice mb-4">
            <thead class="p-1 text-center"ead>
                <tr>
                    <th class="p-1 text-center">Description</th>
                    <th class="p-1 text-center">Amount ({{ $user->currency }})</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="p-1 text-center">Professional Fees ({{ $invoice->detail }})</td>
                    <td class="p-1 text-center">{{ number_format($invoice->amount, 2) }}</td>
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
                <tr>
                    <th class="p-1 text-center" class="text-end">Total</th>
                    <th class="p-1 text-center">@php
                           echo $total = $invoice->amount - ($invoice->amount * ($invoice->discount / 100)) + (($invoice->amount - ($invoice->amount * ($invoice->discount / 100))) * ($invoice->tax / 100));
                        @endphp</th>
                </tr>
            </tbody>
        </table>

@if(!empty($invoiceSetting->payment_link))
        <div class="note-box">
        <p><strong>Payment Link:</strong> 
            
                <a style="color: inherit !important;
    text-decoration: none !important;
    background: none !important; border: none;" target="_blank" href="{{ $invoiceSetting->payment_link }}">{{ $invoiceSetting->payment_link }}</a>
            
            
        </p>
    
        </div>
        @endif
        <div style="margin-top: 60px; text-align: center; font-size: 0.9rem; line-height: 1.6;">
            <div>
                Thank you for business !
            </div>
            
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            window.print();
            window.onafterprint = function () {
                window.close();
            }
        });
    </script>
</body>

</html>
