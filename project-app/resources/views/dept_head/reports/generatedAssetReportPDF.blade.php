<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Asset Report PDF</title>
    <style>
        body {
            margin: 5px;
            font-size: 9px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 5px;
            border: 1px solid #ddd;
            text-align: left;
            vertical-align: middle;
            word-wrap: break-word;
        }
        th {
            background-color: #4A90E2;
            color: white;
        }
        img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Custom Asset Report</h2>
    <table>
        <thead>
            <tr>
                @foreach ($fields as $field)
                    <th>{{ ucfirst(str_replace('_', ' ', $field)) }}</th>
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
