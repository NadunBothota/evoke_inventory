@extends('layouts.app')

@section('header_title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Inventory Value</h5>
                    <p class="card-text fs-4">${{ number_format($totalValue, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Items</h5>
                    <p class="card-text fs-4">{{ $totalItems }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Categories</h5>
                    <p class="card-text fs-4">{{ $totalCategories }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Breakdown -->
    <div class="card mt-4">
        <div class="card-header">
            Inventory Breakdown by Category
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Category Name</th>
                        <th>Item Count</th>
                        <th>Category Value</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->item->count() }}</td>
                            <td>${{ number_format($category->item_sum_value ?? 0, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No categories or items found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection