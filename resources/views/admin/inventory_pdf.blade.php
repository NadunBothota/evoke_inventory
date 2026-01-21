<!DOCTYPE html>
<html>
<head>
    <title>Inventory Report</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-family: 'Arial', Times, serif;
            font-size: 28px;
            margin: 0;
            font-weight: bold;
        }
        .header p {
            margin: 2px 0;
            font-size: 12px;
        }
        .title {
            text-align: center;
            font-family: 'Arial', Times, serif;
            font-size: 20px;
            font-weight: bold;
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
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Evoke International Ltd</h1>
        <p>No 123, Colombo, Sri Lanka</p>
        <p>Phone: +94 11 123 4567 | Email: info@evotech.lk</p>
    </div>

    <div class="title">
        Inventory Report
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Serial Number</th>
                <th>Device Name</th>
                <th>User</th>
                <th>Department</th>
                <th>Reference Number</th>
                <th>Status</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->serial_number }}</td>
                <td>{{ $item->device_name }}</td>
                <td>{{ $item->user->name ?? 'N/A' }}</td>
                <td>{{ $item->user->department ?? 'N/A' }}</td>
                <td>{{ $item->reference_number }}</td>
                <td>{{ $item->status }}</td>
                <td>Rs.{{ number_format($item->value, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
