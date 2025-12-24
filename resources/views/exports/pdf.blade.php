<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }
        .filters {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f5f5f5;
        }
        .filters h3 {
            font-size: 14px;
            margin-bottom: 10px;
        }
        .export-date {
            text-align: right;
            font-size: 10px;
            color: #666;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4e73df;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>

    <div class="export-date">
        Дата экспорта: {{ $exportDate }}
    </div>

    @if(!empty($data))
    <table>
        @if(!empty($headers))
        <thead>
            <tr>
                @foreach($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        @endif
        <tbody>
            @foreach($data as $row)
            <tr>
                @if(is_array($row))
                    @foreach(array_values($row) as $cell)
                        <td>{{ $cell ?? '' }}</td>
                    @endforeach
                @else
                    <td>{{ $row }}</td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</body>
</html>

