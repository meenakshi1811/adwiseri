<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice Notification</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f3f5fb;
            font-family: Arial, Helvetica, sans-serif;
            color: #1f2937;
        }

        .wrapper {
            width: 100%;
            padding: 28px 12px;
        }

        .card {
            max-width: 640px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .header {
            background: #1f4bb8;
            color: #ffffff;
            padding: 18px 24px;
            text-align: center;
        }

        .header img {
            width: 170px;
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }

        .body {
            padding: 24px;
            font-size: 14px;
            line-height: 1.7;
        }

        .summary {
            margin: 18px 0;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        .summary table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary td {
            padding: 10px 14px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }

        .summary tr:last-child td {
            border-bottom: none;
        }

        .label {
            color: #6b7280;
            width: 42%;
        }

        .value {
            font-weight: 600;
            text-align: right;
        }

        .footer {
            padding: 16px 24px 22px;
            color: #6b7280;
            font-size: 12px;
            border-top: 1px solid #e5e7eb;
            background: #fafafa;
            text-align: center;
        }

    </style>
</head>

<body>
    <div class="wrapper">
        <div class="card">
            <div class="header">
                <img src="{{ url('web_assets/images/Style2.png') }}" alt="Adwiseri">
                <h2>Invoice {{ $data->invoice_no ?? '' }}</h2>
            </div>
            <div class="body">
                <p>Hello <strong>{{ $data->name }}</strong>,</p>
                <p>{{ $data->message }}</p>

                <div class="summary">
                    <table>
                        <tr>
                            <td class="label">Invoice Number</td>
                            <td class="value">{{ $data->invoice_no ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Invoice Date</td>
                            <td class="value">{{ !empty($data->invoice_date) ? date('d-m-Y', strtotime($data->invoice_date)) : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Status</td>
                            <td class="value">{{ $data->status ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Total</td>
                            <td class="value">{{ $data->currency ?? 'Rs.' }} {{ isset($data->total) ? number_format($data->total, 2) : '0.00' }}</td>
                        </tr>
                        @if(($data->status ?? '') !== 'Paid')
                            <tr>
                                <td class="label">Due Date</td>
                                <td class="value">{{ !empty($data->due_date) ? date('d-m-Y', strtotime($data->due_date)) : '-' }}</td>
                            </tr>
                        @endif
                    </table>
                </div>

                <p>
                    Please find your invoice attached as a PDF in this email.
                    @if(($data->status ?? '') === 'Paid')
                        This invoice is already marked as <strong>Paid</strong>.
                    @endif
                </p>

                <p>Thank you,<br><strong>{{ $data->company_name ?? 'Adwiseri Team' }}</strong></p>
            </div>
            <div class="footer">
                &copy; {{ date('Y') }} adwiseri. All rights reserved.
            </div>
        </div>
    </div>
</body>

</html>
