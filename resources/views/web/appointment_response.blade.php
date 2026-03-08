<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Appointment Response' }}</title>
    <style>
        body {
            margin: 0;
            font-family: 'Lato', Arial, sans-serif;
            background: #f4f6fb;
            color: #1f2937;
        }
        .wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .card {
            width: 100%;
            max-width: 560px;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(17, 24, 39, 0.08);
            padding: 30px;
            text-align: center;
        }
        .badge {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 34px;
            font-weight: 700;
            color: #fff;
        }
        .accepted { background: #16a34a; }
        .declined { background: #dc2626; }
        .neutral { background: #6b7280; }
        h1 {
            margin: 0 0 10px;
            font-size: 28px;
        }
        p {
            margin: 0;
            font-size: 16px;
            line-height: 1.6;
            color: #4b5563;
        }
        .cta {
            margin-top: 24px;
        }
        .cta a {
            display: inline-block;
            background: #695EEE;
            color: #fff;
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 8px;
            font-weight: 600;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="card">
        <div class="badge {{ $status ?? 'neutral' }}">
            @if(($status ?? 'neutral') === 'accepted')
                ✓
            @elseif(($status ?? 'neutral') === 'declined')
                ×
            @else
                !
            @endif
        </div>

        <h1>{{ $title ?? 'Appointment Response' }}</h1>
        <p>{{ $subtitle ?? '' }}</p>

        @if(!empty($calendlyUrl))
            <div class="cta">
                <p style="margin-bottom:14px;">To enable Calendly reminders and notifications for both you and the consultant, please confirm via Calendly.</p>
                <a href="{{ $calendlyUrl }}" target="_blank" rel="noopener">Confirm in Calendly</a>
            </div>
        @endif
    </div>
</div>
</body>
</html>
