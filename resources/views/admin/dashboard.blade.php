@extends('layouts.app')

@section('content')
    <h2 class="'font-semibold text-xl">
        Admin Dashboard
    </h2>

    <div class="p-6">
        <p>Welcome, {{ auth()->user()->name }}</p>

        <ul class="mt-4 space-y-2">
            <li>Manage Item Categories</li>
            <li>View Reports</li>
            <li>Manage Users (Super Admin Only)</li>
        </ul>
    </div>
@endsection
