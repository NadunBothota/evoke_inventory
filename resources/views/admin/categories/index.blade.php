@extends('layouts.app')

@section('header_title', 'Categories')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Categories</h5>
            @if(in_array(auth()->user()->role, ['admin', 'super_admin']))
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">+ Add Category</a>
            @endif
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        @if(in_array(auth()->user()->role, ['admin', 'super_admin']))
                            <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td><a href="{{ route('admin.items.index', ['category' => $category->id]) }}" style="text-decoration: none; color: inherit;">{{ $category->name }}</a></td>
                            @if(in_array(auth()->user()->role, ['admin', 'super_admin']))
                                <td>
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ in_array(auth()->user()->role, ['admin', 'super_admin']) ? '3' : '2' }}" class="text-center">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection