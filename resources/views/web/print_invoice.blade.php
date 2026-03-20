<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #1f2937;
            padding: 20px;
        }

        .sheet {
            max-width: 900px;
            margin: 0 auto;
        }

        .header {
            width: 100%;
            border-bottom: 2px solid #1f4bb8;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }

        .header td {
            vertical-align: middle;
        }

        .title {
            text-align: right;
            font-size: 26px;
            font-weight: 700;
            color: #1f4bb8;
        }

        .logo {
            max-height: 55px;
            max-width: 200px;
            margin-bottom: 6px;
        }

        .company {
            font-size: 22px;
            font-weight: 700;
            color: #1f4bb8;
        }

        .grid {
            width: 100%;
            margin-bottom: 16px;
        }

        .grid td {
            width: 50%;
            vertical-align: top;
        }

        .section-title {
            font-size: 11px;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 4px;
            font-weight: 700;
            letter-spacing: .5px;
        }

        .box {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 10px;
            min-height: 90px;
        }

        .items,
        .totals {
            width: 100%;
            border-collapse: collapse;
        }

        .items th,
        .items td,
        .totals td {
            border: 1px solid #d1d5db;
            padding: 8px;
        }

        .items th {
            background: #eff3ff;
            text-align: left;
        }

        .right {
            text-align: right;
        }

        .totals-wrap {
            width: 45%;
            margin-left: auto;
            margin-top: 10px;
        }

        .totals .grand td {
            font-weight: 700;
            background: #eff3ff;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>

<body>
    @php
        $userid = $invoice->user_id ?? 1;
        $logoPath = null;

        if (!empty($invoice->logo)) {
            if ($u->user_type === 'Subscriber' || $u->user_type === 'admin') {
                $logoPath = public_path('web_assets/users/user' . $userid . '/' . $invoice->logo);
            } else {
                $logoPath = public_path('web_assets/users/user' . $u->added_by . '/' . $invoice->logo);
            }
        }

        $statusRaw = (string) ($invoice->status ?? '-');
        $statusLabel = $statusRaw === 'PartiallyPaid' ? 'Partially Paid' : ($statusRaw === 'UnPaid' ? 'Unpaid' : $statusRaw);
        $subtotal = (float) $invoice->amount;
        $discountAmount = $subtotal * ((float) $invoice->discount / 100);
        $taxable = $subtotal - $discountAmount;
        $taxAmount = $taxable * ((float) $invoice->tax / 100);
        $total = $taxable + $taxAmount;
    @endphp

    <div class="sheet">
        <table class="header">
            <tr>
                <td>
                    @if(!empty($logoPath) && file_exists($logoPath))
                        <img class="logo" src="{{ $logoPath }}" alt="Logo">
                    @endif
                    <div class="company">{{ $invoice->name ?? 'Adwiseri' }}</div>
                    <div>{{ $invoice->email ?? '' }}</div>
                </td>
                <td class="title">INVOICE</td>
            </tr>
        </table>

        <table class="grid">
            <tr>
                <td style="padding-right: 8px;">
                    <div class="section-title">Bill To</div>
                    <div class="box">
                        <strong>{{ $invoice->to_name ?? '-' }}</strong><br>
                        {{ $invoice->to_email ?? '' }}
                    </div>
                </td>
                <td style="padding-left: 8px;">
                    <div class="section-title">Invoice Details</div>
                    <div class="box">
                        <strong>Invoice No:</strong> {{ $invoice->invoice_no ?? '-' }}<br>
                        <strong>Invoice Date:</strong> {{ !empty($invoice->created_at) ? date('d-m-Y', strtotime($invoice->created_at)) : '-' }}<br>
                        @if(($invoice->status ?? '') !== 'Paid')
                            <strong>Due Date:</strong> {{ !empty($invoice->due_date) ? date('d-m-Y', strtotime($invoice->due_date)) : '-' }}<br>
                        @endif
                        <strong>Status:</strong> {{ $statusLabel }}
                    </div>
                </td>
            </tr>
        </table>

        <table class="items">
            <thead>
                <tr>
                    <th style="width:72%;">Description</th>
                    <th class="right" style="width:28%;">Amount ({{ $user->currency }})</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Professional Fees ({{ $invoice->detail }})</td>
                    <td class="right">{{ number_format($subtotal, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="totals-wrap">
            <table class="totals">
                <tr>
                    <td>Subtotal</td>
                    <td class="right">{{ number_format($subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td>Discount ({{ number_format((float) $invoice->discount, 2) }}%)</td>
                    <td class="right">-{{ number_format($discountAmount, 2) }}</td>
                </tr>
                <tr>
                    <td>Tax ({{ number_format((float) $invoice->tax, 2) }}%)</td>
                    <td class="right">{{ number_format($taxAmount, 2) }}</td>
                </tr>
                <tr class="grand">
                    <td>Total</td>
                    <td class="right">{{ number_format($total, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">Thanks for your business !</div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            window.print();
            window.onafterprint = function() {
                window.close();
            }
        });
    </script>
</body>

</html>
