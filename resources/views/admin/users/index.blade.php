@extends('layouts.app')

@section('header_title', 'User Management')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Users</h5>
            @if(auth()->user()->role === 'super_admin')
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Add User</a>
            @endif
        </div>
        <div class="card-body">
             @if(session('success'))
                <div class="alert alert-success mb-4" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            @if(auth()->user()->role === 'super_admin')
                                <th>Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                     @php
                                        $roleClass = match($user->role) {
                                            'admin' => 'danger',
                                            'super_admin' => 'primary',
                                            default => 'info',
                                        };
                                    @endphp
                                    <span class="badge badge-outline-custom border-{{ $roleClass }}">{{ ucfirst($user->role) }}</span>
                                </td>
                                @if(auth()->user()->role === 'super_admin')
                                    <td>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-custom-edit">Edit</a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-custom-delete">Delete</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->role === 'super_admin' ? '5' : '4' }}" class="text-center">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
