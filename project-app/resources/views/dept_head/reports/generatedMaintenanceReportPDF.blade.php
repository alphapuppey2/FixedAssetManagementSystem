<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Maintenance Report PDF</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #4A90E2;
            color: white;
        }
    </style>
</head>

<body>
    <h2 style="text-align: center;">Maintenance Report</h2>
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