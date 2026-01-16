<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Items Export</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #222;
        }
        .header .company-name {
            font-size: 32px;
            font-weight: bold;
            color: #000;
        }
        .header .company-details {
            font-size: 12px;
            color: #555;
        }
        .content {
            margin-top: 30px;
        }
        .content table {
            width: 100%;
            border-collapse: collapse;
        }
        .content th,
        .content td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
        .content th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .footer {
            position: fixed;
            bottom: -30px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #777;
        }
        .confidential {
            font-style: italic;
            color: #999;
        }
        .page-number:before {
            content: "Page " counter(page);
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">{{ $companyName }}</div>
        <div class="company-details">
            {{ $companyAddress }}<br>
            Phone: {{ $companyPhone }} | Email: {{ $companyEmail }}
        </div>
        <h1>Items Export</h1>
    </div>

    <div class="content">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Serial Number</th>
                    <th>Device Name</th>
                    <th>Department</th>
                    <th>Reference Number</th>
                    <th>Value</th>
                    <th>Status</th>
                    <th>Category</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->serial_number }}</td>
                        <td>{{ $item->device_name }}</td>
                        <td>{{ $item->department }}</td>
                        <td>{{ $item->reference_number }}</td>
                        <td>{{ number_format($item->value, 2) }}</td>
                        <td>{{ $item->status }}</td>
                        <td>{{ $item->category->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 20px; font-size: 12px;">
            <strong>Total Items:</strong> {{ $items->count() }}<br>
            <strong>Total Value:</strong> ${{ number_format($totalValue, 2) }}
        </div>
    </div>

    <div class="footer">
        <span class="page-number"></span> | <span class="confidential">Confidential</span>
    </div>
</body>
</html>
