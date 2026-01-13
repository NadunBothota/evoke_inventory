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
