@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Categories</h1>
        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin')
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Add Category</a>
        @endif
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin')
                        <th>Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td><a href="{{ route('admin.items.index', ['category' => $category->id]) }}">{{ $category->name }}</a></td>
                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin')
                            <td>
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
