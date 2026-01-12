@extends('layouts.app')

@section('content')
    <h2 class="h4 font-weight-bold">Add New Item</h2>

    {{-- Read-only warning --}}
    @if(auth()->user()->isReadOnly())
        <div class="alert alert-warning" role="alert">
            You have read-only access. Creating new items is disabled.
        </div>
    @endif

    <div class="card my-4">
        <div class="card-body">

            {{-- Validation Errors --}}
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

                @php
                    $readonly = auth()->user()->isReadOnly();
                @endphp

                <div class="row">
                    <div class="col-md-6">
                        <!-- Serial Number -->
                        <div class="mb-3">
                            <label for="serial_number" class="form-label">Serial Number</label>
                            <input type="text" class="form-control" id="serial_number" name="serial_number" value="{{ old('serial_number') }}" {{ $readonly ? 'disabled' : 'required' }}>
                        </div>

                        <!-- Device Name -->
                        <div class="mb-3">
                            <label for="device_name" class="form-label">Device Name</label>
                            <input type="text" class="form-control" id="device_name" name="device_name" value="{{ old('device_name') }}" {{ $readonly ? 'disabled' : 'required' }}>
                        </div>

                        <!-- Item User -->
                        <div class="mb-3">
                            <label for="item_user" class="form-label">Item User</label>
                            <input type="text" class="form-control" id="item_user" name="item_user" value="{{ old('item_user') }}" {{ $readonly ? 'disabled' : 'required' }}>
                        </div>

                         <!-- Department -->
                        <div class="mb-3">
                            <label for="department" class="form-label">Department</label>
                            <input type="text" class="form-control" id="department" name="department" value="{{ old('department') }}" {{ $readonly ? 'disabled' : 'required' }}>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Reference Number -->
                        <div class="mb-3">
                            <label for="reference_number" class="form-label">Reference Number</label>
                            <input type="text" class="form-control" id="reference_number" name="reference_number" value="{{ old('reference_number') }}" {{ $readonly ? 'disabled' : 'required' }}>
                        </div>

                        <!-- Value -->
                        <div class="mb-3">
                            <label for="value" class="form-label">Item Value</label>
                            <input type="number" step="0.01" class="form-control" id="value" name="value" value="{{ old('value') }}" {{ $readonly ? 'disabled' : 'required' }}>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" onchange="togglePoliceReport()" {{ $readonly ? 'disabled' : 'required' }}>
                                <option value="">-- Select Status --</option>
                                <option value="working">Working</option>
                                <option value="not_working">Not Working</option>
                                <option value="misplaced">Misplaced</option>
                            </select>
                        </div>

                        <!-- Police Report -->
                        <div id="policeReportField" class="mb-3 d-none">
                            <label for="police_report" class="form-label">Police Report</label>
                            <input type="file" class="form-control" id="police_report" name="police_report" accept=".pdf,.jpg,.jpeg,.png" {{ $readonly ? 'disabled' : '' }}>
                        </div>
                    </div>
                </div>

                <div class="row">
                     <div class="col-md-6">
                        <!-- Category -->
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select" id="category_id" name="category_id" {{ $readonly ? 'disabled' : 'required' }}>
                                <option value="">-- Select Category --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Photo -->
                        <div class="mb-3">
                            <label for="photo" class="form-label">Item Photo</label>
                            <input type="file" class="form-control" id="photo" name="photo" accept=".jpg,.jpeg,.png" {{ $readonly ? 'disabled' : '' }}>
                        </div>
                    </div>
                </div>

                <!-- Comment -->
                <div class="mb-3">
                    <label for="comment" class="form-label">Comment</label>
                    <textarea class="form-control" id="comment" name="comment" rows="3" {{ $readonly ? 'disabled' : '' }}>{{ old('comment') }}</textarea>
                </div>

                <!-- Submit -->
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
