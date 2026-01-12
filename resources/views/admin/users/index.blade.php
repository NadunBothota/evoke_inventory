@extends('layouts.app')

@section('content')
    <h2 class="h4 font-weight-bold">
        User Management
    </h2>

    <div class="card my-4">
        @if(auth()->user()->role === 'super_admin')
            <div class="card-header">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Add User</a>
            </div>
        @endif
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Role</th>
                        @if(auth()->user()->role === 'super_admin')
                            <th scope="col">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge bg-{{ $user->role == 'admin' ? 'danger' : 'info' }}">{{ ucfirst($user->role) }}</span></td>
                            @if(auth()->user()->role === 'super_admin')
                                <td>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
