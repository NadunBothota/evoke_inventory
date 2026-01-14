@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Audit Logs</h1>

        <!-- Filter and Search Form -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Filter Logs</h5>
                <form action="{{ route('admin.audit-logs.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="user_id" class="form-control">
                                <option value="">All Users</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="action" class="form-control">
                                <option value="">All Actions</option>
                                <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                                <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                                <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

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
                @forelse($auditLogs as $log)
                    @php
                        $ignoreKeys = ['id', 'created_at', 'updated_at'];
                    @endphp
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>{{ $log->user->name ?? 'System' }}</td>
                        <td>{{ $log->action }}</td>
                        <td>
                            {{ $log->model }}
                            @if ($log->item && $log->item->category)
                                <br>
                                <small>({{ $log->item->category->name }})</small>
                            @endif
                        </td>
                        <td>{{ $log->item_id }}</td>
                        <td>
                            @if ($log->action == 'updated')
                                <strong>Changes:</strong>
                                <ul class="list-unstyled mb-0">
                                    @foreach($log->new_values as $key => $newValue)
                                        @if (!in_array($key, $ignoreKeys))
                                            @php
                                                $oldValue = $log->old_values[$key] ?? 'N/A';
                                            @endphp
                                            @if ($newValue != $oldValue)
                                                <li>
                                                    <strong>{{ Illuminate\Support\Str::title(str_replace('_', ' ', $key)) }}:</strong>
                                                    <span class="text-danger">{{ Illuminate\Support\Str::limit($oldValue, 30) }}</span> -> <span class="text-success">{{ Illuminate\Support\Str::limit($newValue, 30) }}</span>
                                                </li>
                                            @endif
                                        @endif
                                    @endforeach
                                </ul>
                            @elseif ($log->action == 'created' && !empty($log->new_values))
                                <strong>New Record Details:</strong>
                                <ul class="list-unstyled mb-0">
                                    @foreach($log->new_values as $key => $value)
                                        @if (!in_array($key, $ignoreKeys) && !is_null($value))
                                            <li><strong>{{ Illuminate\Support\Str::title(str_replace('_', ' ', $key)) }}:</strong> {{ Illuminate\Support\Str::limit($value, 50) }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            @elseif ($log->action == 'deleted' && !empty($log->old_values))
                                <strong>Deleted Record Details:</strong>
                                <ul class="list-unstyled mb-0">
                                     @foreach($log->old_values as $key => $value)
                                        @if (!in_array($key, $ignoreKeys) && !is_null($value))
                                            <li><strong>{{ Illuminate\Support\Str::title(str_replace('_', ' ', $key)) }}:</strong> {{ Illuminate\Support\Str::limit($value, 50) }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            @else
                               No changes to display.
                            @endif
                        </td>
                        <td>{{ $log->created_at->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No audit logs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $auditLogs->links() }}
        </div>
    </div>
@endsection
