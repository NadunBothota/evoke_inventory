@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Category</h1>
        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $category->name }}">
            </div>
            <div class="mb-3">
                <label for="ref_group" class="form-label">Reference Group</label>
                <input type="text" name="ref_group" id="ref_group" class="form-control" value="{{ $category->ref_group }}">
            </div>
            <div class="mb-3">
                <label for="ref_code" class="form-label">Reference Code</label>
                <input type="text" name="ref_code" id="ref_code" class="form-control" value="{{ $category->ref_code }}">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
