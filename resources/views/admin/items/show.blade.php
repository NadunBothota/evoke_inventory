@extends('layouts.app')

@section('header_title', 'Item Details')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>{{ $item->device_name }}</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Serial Number:</strong> {{ $item->serial_number }}</p>
                    <p><strong>User:</strong> {{ $item->item_user }}</p>
                    <p><strong>Department:</strong> {{ $item->department }}</p>
                    <p><strong>Reference Number:</strong> {{ $item->reference_number }}</p>
                    <p><strong>Value:</strong> {{ $item->value > 0 ? 'Rs.'.number_format($item->value, 2) : '-' }}</p>
                    <p><strong>Status:</strong> <span class="badge bg-{{ match($item->status) { 'working' => 'success', 'not_working' => 'danger', 'misplaced' => 'warning', default => 'secondary' } }}">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</span></p>
                    <p><strong>Category:</strong> {{ $item->category->name }}</p>
                </div>
                <div class="col-md-6">
                    @if($item->photo)
                        <img src="{{ asset('storage/' . $item->photo) }}" 
                             alt="Item Photo" 
                             class="img-fluid" 
                             style="max-width: 400px; height: auto; border-radius: 8px; border: 1px solid #dee2e6; padding: 5px;">
                    @endif
                    @if($item->police_report)
                        <p class="mt-3"><a href="{{ asset('storage/' . $item->police_report) }}" target="_blank">View Police Report</a></p>
                    @endif
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <h5>Comments</h5>
                    @forelse($item->comment as $comment)
                        <div class="card mb-3">
                            <div class="card-body">
                                <p class="card-text">{{ $comment->comment }}</p>
                                <p class="card-text"><small class="text-muted">on {{ $comment->created_at->format('M d, Y H:i') }}</small></p>
                            </div>
                        </div>
                    @empty
                        <p>No comments yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
