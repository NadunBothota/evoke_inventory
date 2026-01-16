<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Audit Logs Export</title>
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
            font-size: 10px;
            vertical-align: top;
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
        <h1>Audit Logs Export</h1>
    </div>

    <div class="content">
        <table>
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Model</th>
                    <th>Item ID</th>
                    <th>Old Values</th>
                    <th>New Values</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($auditLogs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('Y-m-d') }}</td>
                        <td>{{ $log->user->name ?? 'N/A' }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->model }}</td>
                        <td>{{ $log->item_id }}</td>
                        <td>
                            @if(is_array($log->old_values))
                                @foreach($log->old_values as $key => $value)
                                    @if(!in_array($key, ['id', 'created_at', 'updated_at', 'photo', 'police_report']) && $value)
                                        <strong>{{ \Illuminate\Support\Str::of($key)->replace('_', ' ')->title() }}:</strong> {{ $value }}<br>
                                    @endif
                                @endforeach
                            @endif
                        </td>
                        <td>
                            @if(is_array($log->new_values))
                                @foreach($log->new_values as $key => $value)
                                    @if(!in_array($key, ['id', 'created_at', 'updated_at', 'photo', 'police_report']) && $value)
                                        <strong>{{ \Illuminate\Support\Str::of($key)->replace('_', ' ')->title() }}:</strong> {{ $value }}<br>
                                    @endif
                                @endforeach
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <span class="page-number"></span> | <span class="confidential">Confidential</span>
    </div>
</body>
</html>
