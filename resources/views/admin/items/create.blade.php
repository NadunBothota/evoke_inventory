@extends('layouts.app')

@section('header_title', 'Add New Item')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Add New Item</h5>
        </div>
        <div class="card-body">
            @php
                $readonly = auth()->user()->isReadOnly();
            @endphp

            @if($readonly)
                <div class="alert alert-warning" role="alert">
                    You have read-only access. Creating new items is disabled.
                </div>
            @endif
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.items.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="serial_number" class="form-label">Serial Number</label>
                            <input type="text" class="form-control" name="serial_number" id="serial_number" value="{{ old('serial_number') }}" {{ $readonly ? 'disabled' : 'required' }}>
                        </div>
                        <div class="mb-3">
                            <label for="device_name" class="form-label">Device Name</label>
                            <input type="text" class="form-control" name="device_name" id="device_name" value="{{ old('device_name') }}" {{ $readonly ? 'disabled' : 'required' }}>
                        </div>
                        <div class="mb-3">
                            <label for="item_user" class="form-label">Item User</label>
                            <input type="text" class="form-control" name="item_user" id="item_user" value="{{ old('item_user') }}" {{ $readonly ? 'disabled' : '' }}>
                        </div>
                        <div class="mb-3">
                            <label for="department" class="form-label">Department</label>
                            <input type="text" class="form-control" name="department" id="department" value="{{ old('department') }}" {{ $readonly ? 'disabled' : 'required' }}>
                        </div>
                         <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select" name="category_id" id="category_id" {{ $readonly ? 'disabled' : 'required' }}>
                                <option value="">-- Select Category --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="value" class="form-label">Item Value ($)</label>
                            <input type="number" step="0.01" class="form-control" name="value" id="value" value="{{ old('value') }}" {{ $readonly ? 'disabled' : '' }}>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" name="status" id="status" onchange="togglePoliceReport()" {{ $readonly ? 'disabled' : 'required' }}>
                                <option value="">-- Select Status --</option>
                                <option value="working" {{ old('status') == 'working' ? 'selected' : '' }}>Working</option>
                                <option value="not_working" {{ old('status') == 'not_working' ? 'selected' : '' }}>Not Working</option>
                                <option value="misplaced" {{ old('status') == 'misplaced' ? 'selected' : '' }}>Misplaced</option>
                            </select>
                        </div>
                        <div id="policeReportField" class="mb-3 {{ old('status') === 'misplaced' ? '' : 'd-none' }}">
                            <label for="police_report" class="form-label">Police Report</label>
                            <input type="file" class="form-control" name="police_report" id="police_report" accept=".pdf,.jpg,.jpeg,.png" {{ $readonly ? 'disabled' : '' }}>
                        </div>
                        <div class="mb-3">
                            <label for="photo" class="form-label">Item Photo</label>
                            <input type="file" class="form-control" name="photo" id="photo" accept=".jpg,.jpeg,.png" {{ $readonly ? 'disabled' : '' }}>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="comment" class="form-label">Comment</label>
                    <textarea class="form-control" name="comment" id="comment" rows="3" {{ $readonly ? 'disabled' : '' }}>{{ old('comment') }}</textarea>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.items.index') }}" class="btn btn-secondary me-2">Cancel</a>
                    @unless($readonly)
                        <button type="submit" class="btn btn-primary">Save Item</button>
                    @endunless
                </div>
            </form>
        </div>
    </div>
    <script>
        function togglePoliceReport() {
            const status = document.getElementById('status').value;
            const policeField = document.getElementById('policeReportField');
            if (status === 'misplaced') {
                policeField.classList.remove('d-none');
            } else {
                policeField.classList.add('d-none');
            }
        }
    </script>
@endsection