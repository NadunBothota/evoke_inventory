@extends('layouts.app')

@section('header_title', 'Item Details')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Item Details: {{ $item->serial_number }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Serial Number:</strong> {{ $item->serial_number }}</p>
                    <p><strong>Device Name:</strong> {{ $item->device_name }}</p>
                    <p><strong>Item User:</strong> {{ $item->item_user }}</p>
                    <p><strong>Department:</strong> {{ $item->department }}</p>
                    <p><strong>Category:</strong> {{ $item->category->name }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Reference Number:</strong> {{ $item->reference_number }}</p>
                    <p><strong>Item Value:</strong> Rs.{{ number_format($item->value, 2) }}</p>
                    <p><strong>Status:</strong> <span class="badge bg-{{ match($item->status) { 'working' => 'success', 'not_working' => 'danger', 'misplaced' => 'warning', default => 'secondary' } }}">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</span></p>
                    @if ($item->police_report)
                        <p><strong>Police Report:</strong> <a href="{{ asset('storage/' . $item->police_report) }}" target="_blank">View Report</a></p>
                    @endif
                    @if ($item->photo)
                        <p><strong>Item Photo:</strong></p>
                        <img src="{{ asset('storage/' . $item->photo) }}" class="img-thumbnail mt-2" style="width: 150px;">
                    @endif
                </div>
            </div>

            <hr>

            <h5>Latest Comment</h5>
            @if($item->comments->isNotEmpty())
                @php
                    $latestComment = $item->comments->sortByDesc('created_at')->first();
                @endphp
                <div class="list-group">
                    <div class="list-group-item">
                        <p class="mb-1">{{ $latestComment->comment }}</p>
                        <small class="text-muted">By {{ $latestComment->user->name ?? 'Unknown' }} on {{ $latestComment->created_at->format('M d, Y') }}</small>
                    </div>
                </div>
            @else
                <p>No comments yet.</p>
            @endif

        </div>
        <div class="card-footer text-end">
            <a href="{{ route('admin.items.index') }}" class="btn btn-secondary">Back to List</a>
            <a href="{{ route('admin.items.edit', $item) }}" class="btn btn-primary">Edit Item</a>
        </div>
    </div>
@endsection
