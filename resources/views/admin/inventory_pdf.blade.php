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
            font-family: 'Times New Roman', Times, serif;
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
            font-family: 'Times New Roman', Times, serif;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 30px;
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
        .category-header {
            font-family: 'Times New Roman', Times, serif;
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
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

    @foreach($itemsByCategory as $categoryName => $items)
        <div class="category-header">
            <h3>{{ $categoryName }}</h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Serial Number</th>
                    <th>User</th>
                    <th>Device/Equipment</th>
                    <th>Department</th>
                    <th>Reference Number</th>
                    <th>Value</th>
                    <th>Status</th>
                    <th>Comment</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $key => $item)
                <tr>
                    <td>{{ $loop->parent->iteration }}.{{ $loop->iteration }}</td>
                    <td>{{ $item->serial_number }}</td>
                    <td>{{ $item->item_user }}</td>
                    <td>{{ $item->device_name }}</td>
                    <td>{{ $item->department }}</td>
                    <td>{{ $item->reference_number }}</td>
                    <td>Rs.{{ number_format($item->value, 2) }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $item->comments->first()->body ?? '' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>
</html>
