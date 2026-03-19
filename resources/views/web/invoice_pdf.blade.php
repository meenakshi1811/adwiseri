<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #1f2937;
            font-size: 12px;
            line-height: 1.5;
        }

        .header {
            width: 100%;
            border-bottom: 2px solid #1f4bb8;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }

        .company {
            font-size: 22px;
            font-weight: bold;
            color: #1f4bb8;
        }
        .company-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .company-logo {
            max-height: 50px;
            max-width: 120px;
            width: auto;
        }

        .title {
            text-align: right;
            font-size: 20px;
            font-weight: bold;
        }

        .grid {
            width: 100%;
            margin-bottom: 16px;
        }

        .grid td {
            vertical-align: top;
            width: 50%;
        }

        .section-title {
            font-size: 11px;
            letter-spacing: .5px;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 4px;
            font-weight: bold;
        }

        .box {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 10px;
            min-height: 90px;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        table.items th,
        table.items td {
            border: 1px solid #d1d5db;
            padding: 8px;
        }

        table.items th {
            background: #eff3ff;
            text-align: left;
        }

        .right {
            text-align: right;
        }

        .totals {
            width: 45%;
            margin-left: auto;
            margin-top: 10px;
            border-collapse: collapse;
        }

        .totals td {
            border: 1px solid #d1d5db;
            padding: 6px 8px;
        }

        .totals .grand td {
            font-weight: bold;
            background: #eff3ff;
        }

        .status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            color: #fff;
            background: #6b7280;
        }

        .status.Paid { background: #0f8a3d; }
        .status.UnPaid { background: #d9480f; }
        .status.PartiallyPaid { background: #9a6700; }
        .status.Cancelled { background: #6b7280; }
    </style>
</head>

<body>
    @php
        $amount = (float) ($data->amount ?? 0);
        $discountPercent = (float) ($data->discount ?? 0);
        $taxPercent = (float) ($data->tax ?? 0);
        $discountAmount = $amount * ($discountPercent / 100);
        $taxable = $amount - $discountAmount;
        $taxAmount = $taxable * ($taxPercent / 100);
        $total = (float) ($data->total ?? ($taxable + $taxAmount));
        $currency = $data->currency ?? 'Rs.';
    @endphp

    <table class="header">
        <tr>
            <td>
                <div class="company-wrap">
                    @if(!empty($data->logo_path) && file_exists($data->logo_path))
                        <img src="{{ $data->logo_path }}" alt="Logo" class="company-logo">
                    @elseif(!empty($data->logo_url))
                        <img src="{{ $data->logo_url }}" alt="Logo" class="company-logo">
                    @endif
                    <div class="company">{{ $data->company_name ?? 'Adwiseri' }}</div>
                </div>
                <div>{{ $data->from_email ?? '' }}</div>
            </td>
            <td class="title">
                INVOICE
            </td>
        </tr>
    </table>

    <table class="grid">
        <tr>
            <td style="padding-right:8px;">
                <div class="section-title">Bill To</div>
                <div class="box">
                    <strong>{{ $data->name ?? '-' }}</strong><br>
                    {{ $data->to_email ?? '' }}
                </div>
            </td>
            <td style="padding-left:8px;">
                <div class="section-title">Invoice Details</div>
                <div class="box">
                    <strong>Invoice No:</strong> {{ $data->invoice_no ?? '-' }}<br>
                    <strong>Invoice Date:</strong> {{ !empty($data->invoice_date) ? date('d-m-Y', strtotime($data->invoice_date)) : '-' }}<br>
                    @if(($data->status ?? '') !== 'Paid')
                        <strong>Due Date:</strong> {{ !empty($data->due_date) ? date('d-m-Y', strtotime($data->due_date)) : '-' }}<br>
                    @endif
                    <strong>Status:</strong>
                    <strong>{{ $data->status ?? '-' }}</strong> 
                </div>
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th style="width:72%;">Description</th>
                <th class="right" style="width:28%;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $data->detail ?? 'Professional Services' }}</td>
                <td class="right">{{ $currency }} {{ number_format($amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td>Subtotal</td>
            <td class="right">{{ $currency }} {{ number_format($amount, 2) }}</td>
        </tr>
        <tr>
            <td>Discount ({{ number_format($discountPercent, 2) }}%)</td>
            <td class="right">- {{ $currency }} {{ number_format($discountAmount, 2) }}</td>
        </tr>
        <tr>
            <td>Tax ({{ number_format($taxPercent, 2) }}%)</td>
            <td class="right">{{ $currency }} {{ number_format($taxAmount, 2) }}</td>
        </tr>
        <tr class="grand">
            <td>Total</td>
            <td class="right">{{ $currency }} {{ number_format($total, 2) }}</td>
        </tr>
    </table>
</body>

</html>
