<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs</title>
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
        <h2>Activity Logs</h2>
        <div class="generated-date">
            Generated on {{ now()->format('Y-m-d H:i:s') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Activity</th>
                <th>Description</th>
                <th>User Role</th>
                <th>User ID</th>
                <th>Asset ID</th>
                <th>Request ID</th>
                <th>Date & Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
                <tr>
                    <td>{{ $log->activity }}</td>
                    <td>{{ $log->description }}</td>
                    <td>{{ ucfirst($log->userType) }}</td>
                    <td>{{ $log->user_id ?? 'System' }}</td>
                    <td>{{ $log->asset_id ?? 'N/A' }}</td>
                    <td>{{ $log->request_id ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($log->created_at)->format('Y-m-d H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
