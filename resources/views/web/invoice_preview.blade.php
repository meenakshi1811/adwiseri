<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>adwiseri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('web_assets/css/style.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@200;300;400;600;700;900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400&display=swap" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
        integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://kit.fontawesome.com/b140011afa.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('web_assets/css/owl.carousel.css') }}">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
            body {
                font-family: 'Lato', sans-serif!important;
            }
        </style>
</head>
<style>
      body{
          background: #F5F5F5;
          font-family: 'Lato';
      }
      
  </style>

<body style="width: 100%;">
    {{-- <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container-fluid">
          <a class="navbar-brand" href="{{ route('/') }}">Adwiseri</a>
        </div>
      </nav> --}}
      @php
      if($invoice->user_id == null){
        $userid = 1;
      }
      else{
        $userid = $invoice->user_id;
      }
      @endphp

      <div class="col-12 p-3 column-client" style="width: 100%;">
        <div class="client-dashboard">
            <h3 class="text-primary text-center p-3">Invoice</h3>
            <div class="client-btn d-flex mb-3 px-3 d-flex justify-content-between">
                <div class="col d-flex justify-content-start align-items-end" style="width: 70px;height:70px;">
                    @if($u->user_type == "Subscriber" or $u->user_type == "admin")
                  <img class="border rounded" src="{{ asset('web_assets/users/user'.$userid.'/' . $invoice->logo) }}" style="max-width: 100%;max-height:100%;width:auto;height:auto;">
                  @else
                  <img class="border rounded" src="{{ asset('web_assets/users/user'.$u->added_by.'/' . $invoice->logo) }}" style="max-width: 100%;max-height:100%;width:auto;height:auto;">
                  @endif
                </div>
            </div>
            <div class="col px-3">
                <div class="row">
                    <div class="col-6">
                        <h4 class="d-flex align-items-center">
                            <span class="me-2" style="display:inline-flex; width:28px; height:28px;">
                                @if($u->user_type == "Subscriber" or $u->user_type == "admin")
                                    <img class="border rounded" src="{{ asset('web_assets/users/user'.$userid.'/' . $invoice->logo) }}" style="max-width: 100%;max-height:100%;width:auto;height:auto;">
                                @else
                                    <img class="border rounded" src="{{ asset('web_assets/users/user'.$u->added_by.'/' . $invoice->logo) }}" style="max-width: 100%;max-height:100%;width:auto;height:auto;">
                                @endif
                            </span>
                            <span>{{ $invoice->name }}</span>
                        </h4>
                        <p class="m-1" style="line-height: 1;">{{ $invoice->address }}</p>
                        <p class="m-1" style="line-height: 1;">{{ $invoice->city }}, {{ $invoice->state }}</p>
                        <p class="m-1" style="line-height: 1;">{{ $invoice->country }}, {{ $invoice->pincode }}</p>
                        {{-- <p class="m-1" style="line-height: 1;">{{ $invoice->email }}</p>
                        <p class="m-1" style="line-height: 1;">{{ $invoice->phone }}</p> --}}
                    </div>
                    <div class="col-6 d-flex justify-content-end">
                        <div class="row m-0" style="height: fit-content;">
                            <p class="col-6 m-0">Invoice No. :</p>
                            <p class="col-6 text-end m-0">{{ $invoice->invoice_no }}</p>
                            <p class="col-6 m-0">Date :</p>
                            <p class="col-6 text-end m-0">{{ date('d-m-Y', strtotime($invoice->created_at)) }}</p>
                        </div>
                    </div>
                </div>
                {{-- <div class="mt-3">
                  <strong>Regarding: </strong>{{ $invoice->detail }}
                </div> --}}
                <h5 class="mt-3 px-3 py-1 border">Bill To</h5>
                <div class="col px-3">
                    <p class="m-1" style="line-height: 1;">{{ $invoice->to_name }}</p>
                    <p class="m-1" style="line-height: 1;">{{ $invoice->to_address }}</p>
                    <p class="m-1" style="line-height: 1;">{{ $invoice->to_city }} {{ $invoice->to_state }}</p>
                    <p class="m-1" style="line-height: 1;">{{ $invoice->to_country }} {{ $invoice->to_pincode }}</p>
                    {{-- <p class="m-1" style="line-height: 1;">{{ $invoice->to_email }}</p>
                    <p class="m-1" style="line-height: 1;">{{ $invoice->to_phone }}</p> --}}
                </div>
                {{-- <h5 class="mt-3 py-1">Services Description</h5>
                <p class="px-1">{{ $invoice->detail }}</p> --}}
                <div class="table-wrapper">
                    <table class="fl-table table m-0 px-3" style="border: 1px solid grey;">
                        <tr>
                            <th class="p-1 text-center" class="col-8" style="border: 1px solid lightgrey;">Description</th>
                            <th class="p-1 text-center" class="col-4" style="border: 1px solid lightgrey;">Amount ({{$u->currency}})</th>
                        </tr>
                        <tr>
                            <td class="p-1 text-center" style="border: 1px solid lightgrey;">
                                <p class="m-1">Professional Fees({{ $invoice->detail }})</p>
                                @if($invoice->discount != 0)
                                <p class="m-1">Discount</p>
                                @endif
                                <p class="m-1">Tax</p>
                            </td>
                            <td class="p-1 text-center" style="border: 1px solid lightgrey;">
                                <p class="m-1">{{ $invoice->amount }}</p>
                                @if($invoice->discount != 0)
                                <p class="m-1">-{{$invoice->amount * ($invoice->discount/100)}} ({{ $invoice->discount }} %)</p>
                                @endif
                                <p class="m-1">{{($invoice->amount -($invoice->amount * ($invoice->discount/100))) * ($invoice->tax/100)}} ({{ $invoice->tax }} %)</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-1 text-center" class="col text-end" style="border: 1px solid lightgrey;">Total</td>
                            <td class="p-1 text-center" style="border: 1px solid lightgrey;">{{ $invoice->total }}</td>
                        </tr>
                    </table>
                </div>
@php
    $paymentLink = isset($invoiceSetting->payment_link) ? trim((string) $invoiceSetting->payment_link) : '';
@endphp
@if(!empty($paymentLink) && filter_var($paymentLink, FILTER_VALIDATE_URL))
                <div class="m-0 mt-3 p-3 border">
                <p><strong>Payment Link:</strong> 
                    
                        <a target="_blank" href="{{ $paymentLink }}">{{ $paymentLink }}</a>
                    
                    
                </p>

                </div>
                @endif
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
        $(document).ready(function (){
            window.print();
            window.onafterprint = function(){
                window.close();
            }
        });
    </script>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
crossorigin="anonymous"></script>
</body>

</html>
