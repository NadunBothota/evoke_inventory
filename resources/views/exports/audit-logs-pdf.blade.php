<!DOCTYPE html>
<html>
<head>
    <title>Audit Logs</title>
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
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .changes-list {
            list-style-type: none;
            padding-left: 0;
        }
    </style>
</head>
<body>
    <h1>Audit Logs</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Action</th>
                <th>Model</th>
                <th>Item ID</th>
                <th>Changes</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            @foreach($auditLogs as $log)
                <tr>
                    <td>{{ $log->id }}</td>
                    <td>{{ $log->user->name ?? 'N/A' }}</td>
                    <td>{{ $log->action }}</td>
                    <td>{{ $log->model }}</td>
                    <td>{{ $log->item_id }}</td>
                    <td>
                        @if ($log->action === 'updated')
                            <ul class="changes-list">
                                @foreach ($log->new_values as $key => $newValue)
                                    @php
                                        $oldValue = $log->old_values[$key] ?? null;
                                    @endphp
                                    @if ($newValue != $oldValue && (isset($log->old_values[$key]) || !empty($newValue)))
                                        <li>
                                            <strong>{{ ucwords(str_replace('_', ' ', $key)) }}:</strong> 
                                            From '{{ $oldValue ?? 'N/A' }}' to '{{ $newValue }}'
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @elseif ($log->action === 'created')
                            Record created.
                        @elseif ($log->action === 'deleted')
                            Record deleted.
                        @else
                            No changes to display.
                        @endif
                    </td>
                    <td>{{ $log->created_at->toDateTimeString() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
