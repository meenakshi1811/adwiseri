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

        .header td {
            vertical-align: middle;
        }

        .company {
            font-size: 22px;
            font-weight: bold;
            color: #1f4bb8;
        }

        .title {
            text-align: right;
            font-size: 20px;
            font-weight: bold;
        }

        .logo {
            max-height: 55px;
            max-width: 200px;
            margin-bottom: 6px;
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

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #4b5563;
        }
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
        $statusRaw = (string) ($data->status ?? '-');
        $statusLabel = $statusRaw === 'PartiallyPaid' ? 'Partially Paid' : ($statusRaw === 'UnPaid' ? 'Unpaid' : $statusRaw);
        $logoPath = !empty($data->logo_path) ? public_path($data->logo_path) : null;
    @endphp

    <table class="header">
        <tr>
            <td>
                @if(!empty($logoPath) && file_exists($logoPath))
                    <img class="logo" src="{{ $logoPath }}" alt="Logo">
                @endif
                <div class="company">{{ $data->company_name ?? 'Adwiseri' }}</div>
                <div>{{ $data->display_from_email ?? ($data->from_email ?? '') }}</div>
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
                    <strong>Status:</strong> {{ $statusLabel }}
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

    <div class="footer">Thanks for your business !</div>
</body>

</html>
