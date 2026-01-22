<!DOCTYPE html>
<html>
<head>
    <title>Inventory Report</title>
    <style>
        body {
            font-family: Arial,sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 700px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #eee;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .header h1 {
            margin: 0;
            color: #222;
        }
        .content {
            padding: 20px 0;
        }
        .content p {
            margin: 0 0 15px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .table th {
            background-color: #f7f7f7;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Inventory Summary Report</h1>
        </div>
        <div class="content">
            <p>Dear Recipient,</p>
            <p>Here is the latest inventory breakdown by category. The attached file contains a more detailed report.</p>

            <table class="table">
                <thead>
                    <tr>
                        <th>Category Name</th>
                        <th>Item Count</th>
                        <th>Total Category Value</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($inventoryData) && !$inventoryData->isEmpty())
                        @foreach($inventoryData as $data)
                            <tr>
                                <td>{{ $data['name'] }}</td>
                                <td>{{ $data['item_count'] }}</td>
                                <td>Rs.{{ number_format($data['total_value'], 2) }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" style="text-align: center;">No inventory data available.</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <p>Best regards,</p>
            <p><strong>Evoke Inventory Management System</strong></p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Evoke International. All rights reserved.</p>
            <p>This is an automated report. Please do not reply.</p>
        </div>

    </div>
</body>
</html>