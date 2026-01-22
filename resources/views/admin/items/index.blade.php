@extends('layouts.app')

@section('header_title', 'Items')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Items</h5>
            <div>
                <a href="{{ route('admin.items.export.excel', request()->query()) }}" class="btn btn-success">Export to Excel</a>
                <a href="{{ route('admin.items.export.pdf', request()->query()) }}" class="btn btn-danger">Export to PDF</a>
                <a href="{{ route('admin.items.create') }}" class="btn btn-primary">+ Add Item</a>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.items.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <label for="user">User</label>
                        <input type="text" class="form-control" id="user" name="user" value="{{ request('user') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="department">Department</label>
                        <input type="text" class="form-control" id="department" name="department" value="{{ request('department') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">All</option>
                            <option value="working" {{ request('status') == 'working' ? 'selected' : '' }}>Working</option>
                            <option value="not_working" {{ request('status') == 'not_working' ? 'selected' : '' }}>Not Working</option>
                            <option value="misplaced" {{ request('status') == 'misplaced' ? 'selected' : '' }}>Misplaced</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="min_value">Min Value</label>
                        <input type="number" class="form-control" id="min_value" name="min_value" value="{{ request('min_value') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="max_value">Max Value</label>
                        <input type="number" class="form-control" id="max_value" name="max_value" value="{{ request('max_value') }}">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.items.index') }}" class="btn btn-secondary">Clear</a>
                    </div>
                </div>
            </form>

            @if($items->isEmpty())
                <div class="alert alert-info">No items found.</div>
            @else
                @foreach($items->groupBy('category.name') as $categoryName => $itemsByCategory)
                    <div class="mt-4">
                        <h5>{{ $categoryName }}</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Serial Number</th>
                                        <th>User</th>
                                        <th>Device/Equipment</th>
                                        <th>Department</th>
                                        <th>Reference Number</th>
                                        <th>Value</th>
                                        <th>Status</th>
                                        <th>Comment</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($itemsByCategory as $item)
                                    <tr>
                                        <td>{{ $loop->parent->iteration }}.{{ $loop->iteration }}</td>
                                        <td><a href="{{ route('admin.items.show', $item) }}" style="text-decoration: none; color: inherit;">{{ $item->serial_number }}</a></td>
                                        <td>{{ $item->item_user }}</td>
                                        <td>{{ $item->device_name }}</td>
                                        <td>{{ $item->department }}</td>
                                        <td>{{ $item->reference_number }}</td>
                                        <td>Rs.{{ number_format($item->value, 2) }}</td>
                                        <td><span class="badge badge-outline-custom border-{{ match($item->status) { 'working' => 'success', 'not_working' => 'danger', 'misplaced' => 'warning', default => 'secondary' } }}">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</span></td>
                                        <td>{{ Str::limit($item->comments->sortByDesc('created_at')->first()->comment ?? '', 20) }}</td>
                                        <td>
                                            <a href="{{ route('admin.items.edit', $item) }}" class="btn btn-sm btn-outline-custom-edit">Edit</a>
                                            <form action="{{ route('admin.items.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-custom-delete">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
