<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>
body{
    font-family: DejaVu Sans, sans-serif;
    font-size:12px;
    color:#333;
    margin:0;
}

/* Header */
.header{
    background:#2c3e50;
    color:white;
    padding:15px 20px;
}

.header h1{
    margin:0;
    font-size:20px;
}

.header p{
    margin:3px 0;
    font-size:11px;
    opacity:0.9;
}

/* Report Info */
.report-info{
    padding:15px 20px;
    background:#f7f7f7;
    border-bottom:1px solid #ddd;
}

.info-row{
    margin:4px 0;
}

/* Section */
.section{
    padding:18px 20px;
}

.section-title{
    font-size:15px;
    font-weight:bold;
    margin-bottom:8px;
    border-left:4px solid #3498db;
    padding-left:8px;
}

/* Table */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:8px;
}

th{
    background:#3498db;
    color:white;
    padding:7px;
    font-size:11px;
    text-align:left;
}

td{
    border:1px solid #e1e1e1;
    padding:6px;
    font-size:11px;
}

tr:nth-child(even){
    background:#fafafa;
}

.no-data{
    font-style:italic;
    color:#777;
    padding:6px 0;
}

/* Footer */
.footer{
    position:fixed;
    bottom:0;
    left:0;
    right:0;
    text-align:center;
    font-size:10px;
    color:#888;
}
</style>

</head>

<body>

<!-- Header -->
<div class="header">
    <h1>Adwiseri Scheduled Report</h1>
    <p>Automated System Report</p>
</div>

<!-- Report Info -->
<div class="report-info">
    <div class="info-row"><strong>Generated For:</strong> {{ $generatedFor->name }} ({{ $generatedFor->email }})</div>
    <div class="info-row"><strong>Frequency:</strong> {{ ucfirst($frequency) }}</div>
    <div class="info-row"><strong>Duration:</strong> {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</div>
</div>

@foreach ($reportData as $section)

<div class="section">

    <div class="section-title">
        {{ $section['title'] }} ({{ count($section['rows']) }})
    </div>

    @if (count($section['rows']) === 0)

        <div class="no-data">
            No records found for this module and duration.
        </div>

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
                            <td>
                                {{ is_array($row[$column]) ? json_encode($row[$column]) : $row[$column] }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

    @endif

</div>

@endforeach

<div class="footer">
Generated automatically by Adwiseri • {{ now()->format('d M Y H:i') }}
</div>

</body>
</html>