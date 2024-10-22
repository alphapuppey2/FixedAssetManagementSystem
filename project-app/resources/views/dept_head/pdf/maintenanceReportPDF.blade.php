<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Report</title>
    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        h2 {
            margin: 0;
            font-size: 22px;
            color: #333;
            font-weight: bold;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .generated-date {
            font-size: 14px;
            font-weight: bold;
            color: #4A90E2;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        th {
            background-color: #4A90E2;
            color: white;
            font-weight: bold;
            padding: 12px;
            text-align: left;
        }

        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 14px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e0f7fa;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Maintenance Report</h2>
        <div class="generated-date">
            Generated on {{ now()->format('Y-m-d H:i:s') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                @foreach ($fields as $field)
                <th>
                    @switch($field)
                    @case('asset_key')
                    Asset Name
                    @break
                    @case('is_completed')
                    Completed
                    @break
                    @default
                    {{ ucfirst(str_replace('_', ' ', $field)) }}
                    @endswitch
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $record)
            <tr>
                @foreach ($fields as $field)
                <td>
                    @switch($field)
                    @case('authorized_by')
                    {{ $record->authorized_by_name ?? 'N/A' }}
                    @break
                    @case('requestor')
                    {{ $record->requestor_name ?? 'N/A' }}
                    @break
                    @case('asset_key')
                    {{ $record->asset_name ?? 'N/A' }}
                    @break
                    @case('is_completed')
                    {{ $record->is_completed ? 'Yes' : 'No' }}
                    @break
                    @default
                    {{ $record->$field ?? 'N/A' }}
                    @endswitch
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>