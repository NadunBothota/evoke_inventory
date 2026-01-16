@extends('layouts.app')

@section('header_title', 'Add Category')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Add New Category</h5>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="mb-3">
                    <label for="ref_group" class="form-label">Reference Group</label>
                    <input type="text" name="ref_group" id="ref_group" class="form-control" value="{{ old('ref_group') }}">
                </div>
                <div class="mb-3">
                    <label for="ref_code" class="form-label">Reference Code</label>
                    <input type="text" name="ref_code" id="ref_code" class="form-control" value="{{ old('ref_code') }}">
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection