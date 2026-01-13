<!DOCTYPE html>
<html>
<head>
    <title>Items Export</title>
    <style>
        body {
            font-family: sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Items Export</h1>
    <p>Date: {{ now()->format('Y-m-d H:i:s') }}</p>
    <p>By: {{ Auth::user()->name }}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Serial Number</th>
                <th>Item User</th>
                <th>Device Name</th>
                <th>Department</th>
                <th>Reference Number</th>
                <th>Value</th>
                <th>Status</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->serial_number }}</td>
                    <td>{{ $item->item_user }}</td>
                    <td>{{ $item->device_name }}</td>
                    <td>{{ $item->department }}</td>
                    <td>{{ $item->reference_number }}</td>
                    <td>{{ $item->value }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $item->category->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
