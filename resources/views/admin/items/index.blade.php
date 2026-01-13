@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 font-weight-bold">
            Items
        </h2>

        <div>
            <a href="{{ route('admin.items.export.excel', request()->all()) }}" class="btn btn-success">Export to Excel</a>
            <a href="{{ route('admin.items.export.pdf', request()->all()) }}" class="btn btn-danger">Export to PDF</a>
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin')
                <a href="{{ route('admin.items.create') }}" class="btn btn-primary">+ Add Item</a>
            @endif
        </div>
    </div>

    <div class="card my-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.items.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="user">User</label>
                            <input type="text" name="user" id="user" class="form-control" value="{{ request('user') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="department">Department</label>
                            <input type="text" name="department" id="department" class="form-control" value="{{ request('department') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="status">Remark</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">All</option>
                                <option value="working" {{ request('status') == 'working' ? 'selected' : '' }}>Working</option>
                                <option value="not_working" {{ request('status') == 'not_working' ? 'selected' : '' }}>Not Working</option>
                                <option value="misplaced" {{ request('status') == 'misplaced' ? 'selected' : '' }}>Misplaced</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Value</label>
                            <div class="input-group">
                                <input type="number" name="min_value" class="form-control" placeholder="Min" value="{{ request('min_value') }}">
                                <input type="number" name="max_value" class="form-control" placeholder="Max" value="{{ request('max_value') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="category" value="{{ request('category') }}">
                <button type="submit" class="btn btn-primary mt-2">Filter</button>
                <a href="{{ route('admin.items.index', ['category' => request('category')]) }}" class="btn btn-secondary mt-2">Clear Filters</a>
            </form>
        </div>
    </div>

    <div class="card my-4">
        <div class="card-body">
            @if($items->isEmpty())
                <div class="text-center">
                    <p>No items found.</p>
                </div>
            @else
                @php 
                    $groupedItems = $items->groupBy('category.name');
                    $itemNumber = 1; 
                @endphp
                @foreach($groupedItems as $categoryName => $itemsInCategory)
                    <h4 class="mt-4">{{ $categoryName ?: 'Uncategorized' }}</h4>
                    <table class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Serial Number</th>
                                <th scope="col">User</th>
                                <th scope="col">Device/Equipment</th>
                                <th scope="col">Department</th>
                                <th scope="col">Reference Number</th>
                                <th scope="col">Value</th>
                                <th scope="col">Remarks</th>
                                <th scope="col">Comment</th>
                                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin')
                                    <th scope="col">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($itemsInCategory as $item)
                                <tr>
                                    <td>{{ $itemNumber++ }}</td>
                                    <td>{{ $item->serial_number }}</td>
                                    <td>{{ $item->item_user }}</td>
                                    <td>{{ $item->device_name }}</td>
                                    <td>{{ $item->department }}</td>
                                    <td>{{ $item->reference_number }}</td>
                                    <td>{{ $item->value > 0 ? number_format($item->value, 2) : '-' }}</td>
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
                                    <td>{{ $item->comment }}</td>
                                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin')
                                        <td>
                                            <a href="{{ route('admin.items.edit', $item) }}" class="btn btn-sm btn-primary">Edit</a>
                                            <form action="{{ route('admin.items.destroy', $item) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach
            @endif
        </div>
    </div>
@endsection
