@extends('layouts.app')

@section('content')
    <h2 class="text xl font-semibold">Audit Logs</h2>

    <div class="mt-6 bg-white shadow rounded p-4">
        <table class="w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2">User</th>
                    <th class="border p-2">Action</th>
                    <th class="border p-2">Model</th>
                    <th class="border p-2">Record ID</th>
                    <th class="border p-2">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $log)
                    <tr>
                        <td class="border p-2">{{ $log->user->name }}</td>
                        <td class="border p-2">{{ ucfirst($log->action) }}</td>
                        <td class="border p-2">{{ $log->model }}</td>
                        <td class="border p-2">{{ $log->model_id }}</td>
                        <td class="border p-2">{{ $log->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">{{ $logs->links() }}

        </div>
    </div>
@endsection
