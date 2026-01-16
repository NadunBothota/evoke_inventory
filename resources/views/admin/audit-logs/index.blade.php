@extends('layouts.app')

@section('header_title', 'Audit Logs')

@section('content')
    <!-- Filter and Search Form -->
    <div class="card mb-4">
        <div class="card-header">
            Filter Logs
        </div>
        <div class="card-body">
            <form action="{{ route('admin.audit-logs.index') }}" method="GET">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" name="search" id="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                         <label for="user_id" class="form-label">User</label>
                        <select name="user_id" id="user_id" class="form-select">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="action" class="form-label">Action</label>
                        <select name="action" id="action" class="form-select">
                            <option value="">All Actions</option>
                            <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                            <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                            <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" name="date" id="date" class="form-control" value="{{ request('date') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.audit-logs.export.excel', request()->all()) }}" class="btn btn-success me-2">Export to Excel</a>
        <a href="{{ route('admin.audit-logs.export.pdf', request()->all()) }}" class="btn btn-danger">Export to PDF</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
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
                            <tr>
                                <td>{{ $log->user->name ?? 'System' }}</td>
                                <td>
                                     @php
                                        $actionClass = match($log->action) {
                                            'created' => 'success',
                                            'updated' => 'warning',
                                            'deleted' => 'danger',
                                            default => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $actionClass }}">{{ ucfirst($log->action) }}</span>
                                </td>
                                <td>
                                    {{ $log->model }}
                                    @if ($log->item && $log->item->category)
                                        <br><small class="text-muted">({{ $log->item->category->name }})</small>
                                    @endif
                                </td>
                                <td>{{ $log->item_id }}</td>
                                <td>
                                    @php $ignoreKeys = ['id', 'created_at', 'updated_at']; @endphp
                                    @if ($log->action == 'updated')
                                        <ul class="list-unstyled mb-0 small">
                                            @foreach($log->new_values as $key => $newValue)
                                                @if (!in_array($key, $ignoreKeys) && ($log->old_values[$key] ?? null) != $newValue)
                                                    <li>
                                                        <strong>{{ Illuminate\Support\Str::title(str_replace('_', ' ', $key)) }}:</strong>
                                                        <span class="text-danger">{{ Illuminate\Support\Str::limit($log->old_values[$key] ?? 'N/A', 30) }}</span> &rarr; <span class="text-success">{{ Illuminate\Support\Str::limit($newValue, 30) }}</span>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @elseif (in_array($log->action, ['created', 'deleted']))
                                        @php $values = $log->action == 'created' ? $log->new_values : $log->old_values; @endphp
                                        <ul class="list-unstyled mb-0 small">
                                            @foreach($values as $key => $value)
                                                @if (!in_array($key, $ignoreKeys) && !is_null($value))
                                                    <li><strong>{{ Illuminate\Support\Str::title(str_replace('_', ' ', $key)) }}:</strong> {{ Illuminate\Support\Str::limit($value, 50) }}</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @else
                                       <span class="text-muted small">No changes to display.</span>
                                    @endif
                                </td>
                                <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No audit logs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
             {{ $auditLogs->links() }}
        </div>
    </div>
@endsection