@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Add Category</h1>
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control">
            </div>
            <div class="mb-3">
                <label for="ref_group" class="form-label">Reference Group</label>
                <input type="text" name="ref_group" id="ref_group" class="form-control">
            </div>
            <div class="mb-3">
                <label for="ref_code" class="form-label">Reference Code</label>
                <input type="text" name="ref_code" id="ref_code" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
@endsection
