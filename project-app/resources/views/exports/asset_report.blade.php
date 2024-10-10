<!DOCTYPE html>
<html>
    <head>
        <title>Asset Report</title>
        <style>
            body {
                font-family: DejaVu Sans, sans-serif;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                border: 1px solid #000;
                padding: 8px;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
            }
        </style>
    </head>
    
<body>
    <h1>Asset Report</h1>

    @if ($assetData->isEmpty())
        <p>No data available for the selected criteria.</p>
    @else
        <table>
            <thead>
                <tr>
                    @foreach ($selectedColumns as $column)
                        <th>{{ ucfirst(str_replace('_', ' ', $column)) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($assetData as $asset)
                    <tr>
                        @foreach ($selectedColumns as $column)
                            <td>{{ $asset->$column ?? 'N/A' }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
