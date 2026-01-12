@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 font-weight-bold">
            Items
        </h2>

        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin')
            <a href="{{ route('admin.items.create') }}" class="btn btn-primary">+ Add Item</a>
        @endif
    </div>

    <div class="card my-4">
        <div class="card-body">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Serial</th>
                        <th scope="col">Device</th>
                        <th scope="col">User</th>
                        <th scope="col">Status</th>
                        <th scope="col">Category</th>
                        <th scope="col">Photo</th>
                        <th scope="col">Police Report</th>
                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin')
                            <th scope="col">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td>{{ $item->serial_number }}</td>
                            <td>{{ $item->device_name }}</td>
                            <td>{{ $item->item_user }}</td>
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
                            <td>{{ $item->category?->name ?? 'Uncategorized' }}</td>
                            <td>
                                @if ($item->photo)
                                    <img src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->device_name }}" class="img-thumbnail" style="width: 50px; height: 50px;">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>
                                @if ($item->police_report)
                                    <a href="{{ asset('storage/' . $item->police_report) }}" target="_blank" class="btn btn-sm btn-outline-info">View</a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
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
                    @empty
                        <tr>
                            <td colspan="{{ (auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin') ? '8' : '7' }}" class="text-center">No items found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
