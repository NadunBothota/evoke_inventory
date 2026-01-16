@extends('layouts.app')

@section('header_title', 'Items')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <a href="{{ route('admin.items.export.excel', request()->all()) }}" class="btn btn-success">Export to Excel</a>
            <a href="{{ route('admin.items.export.pdf', request()->all()) }}" class="btn btn-danger">Export to PDF</a>
        </div>
        @if(in_array(auth()->user()->role, ['admin', 'super_admin']))
            <a href="{{ route('admin.items.create') }}" class="btn btn-primary">+ Add Item</a>
        @endif
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Total Items</h5>
                    <p class="card-text display-4">{{ $totalItems }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Total Value</h5>
                    <p class="card-text display-4">Rs.{{ number_format($totalValue, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            Filters
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.items.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label for="user" class="form-label">User</label>
                        <input type="text" name="user" id="user" class="form-control" value="{{ request('user') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="department" class="form-label">Department</label>
                        <input type="text" name="department" id="department" class="form-control" value="{{ request('department') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">Remark</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All</option>
                            <option value="working" {{ request('status') == 'working' ? 'selected' : '' }}>Working</option>
                            <option value="not_working" {{ request('status') == 'not_working' ? 'selected' : '' }}>Not Working</option>
                            <option value="misplaced" {{ request('status') == 'misplaced' ? 'selected' : '' }}>Misplaced</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Value</label>
                        <div class="input-group">
                            <input type="number" name="min_value" class="form-control" placeholder="Min" value="{{ request('min_value') }}">
                            <input type="number" name="max_value" class="form-control" placeholder="Max" value="{{ request('max_value') }}">
                        </div>
                    </div>
                </div>
                <input type="hidden" name="category" value="{{ request('category') }}">
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.items.index', ['category' => request('category')]) }}" class="btn btn-secondary">Clear</a>
                </div>
            </form>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if($items->isEmpty())
        <div class="text-center p-4 card">
            <p>No items found.</p>
        </div>
    @else
        @php 
            $groupedItems = $items->groupBy('category.name');
            $itemNumber = 1; 
        @endphp
        @foreach($groupedItems as $categoryName => $itemsInCategory)
            <div class="card mb-4">
                <div class="card-header">
                    <h4>{{ $categoryName ?: 'Uncategorized' }}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Serial Number</th>
                                    <th>User</th>
                                    <th>Device/Equipment</th>
                                    <th>Department</th>
                                    <th>Reference Number</th>
                                    <th>Value</th>
                                    <th>Remarks</th>
                                    <th>Comment</th>
                                    @if(in_array(auth()->user()->role, ['admin', 'super_admin']))
                                        <th>Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($itemsInCategory as $item)
                                    <tr class="clickable-row" data-href="{{ route('admin.items.show', $item) }}" style="cursor: pointer;">
                                        <td>{{ $itemNumber++ }}</td>
                                        <td>{{ $item->serial_number }}</td>
                                        <td>{{ $item->item_user }}</td>
                                        <td>{{ $item->device_name }}</td>
                                        <td>{{ $item->department }}</td>
                                        <td>{{ $item->reference_number }}</td>
                                        <td>{{ $item->value > 0 ? 'Rs.'.number_format($item->value, 2) : '-' }}</td>
                                        <td>
                                            @php
                                                $statusClass = match($item->status) {
                                                    'working' => 'success',
                                                    'not_working' => 'danger',
                                                    'misplaced' => 'warning',
                                                    default => 'secondary',
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</span>
                                        </td>
                                        <td style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $item->comments?->sortByDesc('created_at')->first()?->body ?? '' }}</td>
                                        @if(in_array(auth()->user()->role, ['admin', 'super_admin']))
                                            <td>
                                                <a href="{{ route('admin.items.edit', $item) }}" class="btn btn-sm btn-primary">Edit</a>
                                                <form action="{{ route('admin.items.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const rows = document.querySelectorAll('.clickable-row');
        rows.forEach(row => {
            row.addEventListener('click', () => {
                window.location.href = row.dataset.href;
            });
        });
    });
</script>

@endsection
