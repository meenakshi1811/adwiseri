<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        h1 { margin: 0 0 4px 0; font-size: 20px; }
        h2 { margin-top: 22px; margin-bottom: 8px; font-size: 15px; }
        p { margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #ddd; padding: 6px; vertical-align: top; font-size: 11px; }
        th { background: #f4f4f4; }
        .muted { color: #666; }
    </style>
</head>
<body>
    <h1>Adwiseri Scheduled Report</h1>
    <p><strong>Generated for:</strong> {{ $generatedFor->name }} ({{ $generatedFor->email }})</p>
    <p><strong>Frequency:</strong> {{ ucfirst($frequency) }}</p>
    <p><strong>Duration:</strong> {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</p>

    @foreach ($reportData as $section)
        <h2>{{ $section['title'] }} ({{ count($section['rows']) }})</h2>

        @if (count($section['rows']) === 0)
            <p class="muted">No records found for this module and duration.</p>
        @else
            @php
                $columns = array_keys($section['rows'][0]);
            @endphp
            <table>
                <thead>
                    <tr>
                        @foreach ($columns as $column)
                            <th>{{ ucfirst(str_replace('_', ' ', $column)) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($section['rows'] as $row)
                        <tr>
                            @foreach ($columns as $column)
                                <td>{{ is_array($row[$column]) ? json_encode($row[$column]) : $row[$column] }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endforeach
</body>
</html>
