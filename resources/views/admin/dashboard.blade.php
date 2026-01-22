@extends('layouts.app')

@section('header_title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="card-body">
                    <h5 class="card-title">Total Inventory Value</h5>
                    <p class="card-text fs-4">Rs.{{ number_format($totalValue, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="card-body">
                    <h5 class="card-title">Total Items</h5>
                    <p class="card-text fs-4">{{ $totalItems }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="card-body">
                    <h5 class="card-title">Total Categories</h5>
                    <p class="card-text fs-4">{{ $totalCategories }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Inventory Value by Category
                </div>
                <div class="card-body">
                    <canvas id="categoryValueChart" data-category-value-names='@json($categoryValueNames)' data-category-values='@json($categoryValues)'></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Item Status by Category
                </div>
                <div class="card-body">
                    <canvas id="itemStatusChart" data-category-names='@json($categoryNames)' data-working-items='@json($workingItems)' data-not-working-items='@json($notWorkingItems)'></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Breakdown -->
    <div class="card mt-4">
        <div class="card-header">
            Inventory Breakdown by Category
            <a href="{{ route('admin.reports.create') }}" class="btn btn-sm float-right" style="background-color: #ff904f; border-color: #ff904f; color: #ffffff;">Send Report</a>
            <a href="{{ route('admin.dashboard.download.pdf') }}" class="btn btn-primary btn-sm float-right mr-2">Download PDF</a>
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
                            <td>Rs.{{ number_format($category->item_sum_value ?? 0, 2) }}</td>
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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Category Value Chart
        const categoryValueChartEl = document.getElementById('categoryValueChart');
        const categoryValueNames = JSON.parse(categoryValueChartEl.dataset.categoryValueNames);
        const categoryValues = JSON.parse(categoryValueChartEl.dataset.categoryValues);

        var ctx1 = categoryValueChartEl.getContext('2d');
        var categoryValueChart = new Chart(ctx1, {
            type: 'pie',
            data: {
                labels: categoryValueNames,
                datasets: [{
                    label: 'Total Value',
                    data: categoryValues,
                    backgroundColor: [
                        '#ff6600',
                        '#ff8533',
                        'rgb(255, 163, 102)',
                        '#ffc299',
                        '#ffe0cc',
                        '#fff',
                    ],
                    borderColor: '#fff',
                    borderWidth: 1
                }]
            }
        });

        // Item Status Chart
        const itemStatusChartEl = document.getElementById('itemStatusChart');
        const categoryNames = JSON.parse(itemStatusChartEl.dataset.categoryNames);
        const workingItems = JSON.parse(itemStatusChartEl.dataset.workingItems);
        const notWorkingItems = JSON.parse(itemStatusChartEl.dataset.notWorkingItems);

        var ctx2 = itemStatusChartEl.getContext('2d');
        var itemStatusChart = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: categoryNames,
                datasets: [
                    {
                        label: 'Working',
                        data: workingItems,
                        backgroundColor: '#ff6600',
                        borderColor: '#ff6600',
                        borderWidth: 1
                    },
                    {
                        label: 'Not Working',
                        data: notWorkingItems,
                        backgroundColor: '#000000',
                        borderColor: '#000000',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
