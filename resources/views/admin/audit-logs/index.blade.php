@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Audit Logs</h1>

        <div class="mb-3">
            <a href="{{ route('admin.audit-logs.export.excel') }}" class="btn btn-success">Export to Excel</a>
            <a href="{{ route('admin.audit-logs.export.pdf') }}" class="btn btn-danger">Export to PDF</a>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Model</th>
                    <th>Item ID</th>
                    <th style="width: 50%;">Changes</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @foreach($auditLogs as $log)
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>{{ $log->user->name }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->model }}</td>
                        <td>{{ $log->item_id }}</td>
                        <td>
                            @if ($log->action == 'updated')
                                <ul class="list-unstyled">
                                    @foreach($log->new_values as $key => $newValue)
                                        @php
                                            $oldValue = $log->old_values[$key] ?? null;
                                        @endphp
                                        
                                        @if ($newValue != $oldValue && (isset($log->old_values[$key]) || !empty($newValue)))
                                            <li>
                                                <strong>{{ Illuminate\Support\Str::title(str_replace('_', ' ', $key)) }}:</strong>
                                                <div>
                                                    <span class="text-danger"><strong>Old:</strong> {{ $oldValue ?? 'N/A' }}</span>
                                                    <br>
                                                    <span class="text-success"><strong>New:</strong> {{ $newValue }}</span>
                                                </div>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @elseif ($log->action == 'created' && !empty($log->new_values))
                                <strong>New Record Details:</strong>
                                <ul>
                                    @foreach($log->new_values as $key => $value)
                                        <li><strong>{{ Illuminate\Support\Str::title(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}</li>
                                    @endforeach
                                </ul>
                            @elseif ($log->action == 'deleted' && !empty($log->old_values))
                                <strong>Deleted Record Details:</strong>
                                <ul>
                                     @foreach($log->old_values as $key => $value)
                                        <li><strong>{{ Illuminate\Support\Str::title(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}</li>
                                    @endforeach
                                </ul>
                            @else
                               No changes to display.
                            @endif
                        </td>
                        <td>{{ $log->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $auditLogs->links() }}
    </div>
@endsection
