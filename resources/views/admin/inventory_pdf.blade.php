<!DOCTYPE html>
<html>
<head>
    <title>Inventory Breakdown</title>
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
        Inventory Breakdown
    </div>

    <table>
        <thead>
            <tr>
                <th>Category Name</th>
                <th>Item Count</th>
                <th>Total Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td>{{ $category->item->count() }}</td>
                <td>Rs.{{ number_format($category->item_sum_value, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
