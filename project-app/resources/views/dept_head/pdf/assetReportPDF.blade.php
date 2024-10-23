<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Asset Report PDF</title>
    <style>
        /* Global Styles */
        body {
            margin: 10px;
            font-family: Arial, sans-serif;
            font-size: 10px;
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
            font-size: 12px;
            font-weight: bold;
            color: #4A90E2;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            text-align: left;
            vertical-align: middle;
            word-wrap: break-word;
        }

        th {
            background-color: #4A90E2;
            color: white;
            font-weight: bold;
        }

        img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
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
        <h2>Asset Report</h2>
        <div class="generated-date">
            Generated on {{ now()->format('Y-m-d H:i:s') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                @foreach ($fields as $field)
                <th>
                    {{
                            $field === 'asset_key' ? 'Asset Name' : 
                            ($field === 'ctg_ID' ? 'Category' : 
                            ($field === 'dept_ID' ? 'Department' : 
                            ($field === 'manufacturer_key' ? 'Manufacturer' : 
                            ($field === 'model_key' ? 'Model' : 
                            ($field === 'loc_key' ? 'Location' : 
                            ($field === 'isDeleted' ? 'Is Deleted' : 
                            ($field === 'status' ? 'Status' : 
                            ucfirst(str_replace('_', ' ', $field)))))))))
                        }}
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($assets as $asset)
            <tr>
                @foreach ($fields as $field)
                <td>
                    @switch($field)
                    @case('asst_img')
                    <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents($asset->asst_img_url)) }}" alt="Asset Image">
                    @break
                    @case('qr_img')
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents($asset->qr_img_url)) }}" alt="QR Code">
                    @break
                    @case('ctg_ID')
                    {{ $asset->category_name ?? 'N/A' }}
                    @break
                    @case('dept_ID')
                    {{ $asset->department_name ?? 'N/A' }}
                    @break
                    @case('manufacturer_key')
                    {{ $asset->manufacturer_name ?? 'N/A' }}
                    @break
                    @case('model_key')
                    {{ $asset->model_name ?? 'N/A' }}
                    @break
                    @case('loc_key')
                    {{ $asset->location_name ?? 'N/A' }}
                    @break
                    @case('isDeleted')
                    {{ $asset->isDeleted ? 'Yes' : 'No' }}
                    @break
                    @case('status')
                    @switch($asset->status)
                    @case('active')
                    Active
                    @break
                    @case('deployed')
                    Deployed
                    @break
                    @case('under_maintenance')
                    Under Maintenance
                    @break
                    @case('disposed')
                    Disposed
                    @break
                    @default
                    N/A
                    @endswitch
                    @break
                    @default
                    {{ $asset->$field ?? 'N/A' }}
                    @endswitch
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>