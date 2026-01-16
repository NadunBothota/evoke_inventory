@extends('layouts.app')

@section('header_title', 'Edit Item')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Edit Item: {{ $item->serial_number }}</h5>
        </div>
        <div class="card-body">
            @php
                $readonly = auth()->user()->isReadOnly();
            @endphp

            @if($readonly)
                <div class="alert alert-warning" role="alert">
                    You have read-only access. Editing is disabled.
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

            <form action="{{ route('admin.items.update', $item) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="serial_number" class="form-label">Serial Number</label>
                            <input type="text" class="form-control" name="serial_number" id="serial_number" value="{{ old('serial_number', $item->serial_number) }}" {{ $readonly ? 'disabled' : 'required' }}>
                        </div>
                        <div class="mb-3">
                            <label for="device_name" class="form-label">Device Name</label>
                            <input type="text" class="form-control" name="device_name" id="device_name" value="{{ old('device_name', $item->device_name) }}" {{ $readonly ? 'disabled' : 'required' }}>
                        </div>
                        <div class="mb-3">
                            <label for="item_user" class="form-label">Item User</label>
                            <input type="text" class="form-control" name="item_user" id="item_user" value="{{ old('item_user', $item->item_user) }}" {{ $readonly ? 'disabled' : '' }}>
                        </div>
                        <div class="mb-3">
                            <label for="department" class="form-label">Department</label>
                            <input type="text" class="form-control" name="department" id="department" value="{{ old('department', $item->department) }}" {{ $readonly ? 'disabled' : 'required' }}>
                        </div>
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select" name="category_id" id="category_id" {{ $readonly ? 'disabled' : 'required' }}>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="reference_number" class="form-label">Reference Number</label>
                            <input type="text" class="form-control" name="reference_number" id="reference_number" value="{{ old('reference_number', $item->reference_number) }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="value" class="form-label">Item Value ($)</label>
                            <input type="number" step="0.01" class="form-control" name="value" id="value" value="{{ old('value', $item->value) }}" {{ $readonly ? 'disabled' : '' }}>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" name="status" id="status" onchange="togglePoliceReport()" {{ $readonly ? 'disabled' : 'required' }}>
                                <option value="working" {{ old('status', $item->status) === 'working' ? 'selected' : '' }}>Working</option>
                                <option value="not_working" {{ old('status', $item->status) === 'not_working' ? 'selected' : '' }}>Not Working</option>
                                <option value="misplaced" {{ old('status', $item->status) === 'misplaced' ? 'selected' : '' }}>Misplaced</option>
                            </select>
                        </div>
                        <div id="policeReportField" class="mb-3 {{ old('status', $item->status) === 'misplaced' ? '' : 'd-none' }}">
                            <label for="police_report" class="form-label">Police Report</label>
                            @if(!$readonly)
                                <input type="file" class="form-control" name="police_report" id="police_report" accept=".pdf,.jpg,.jpeg,.png">
                            @endif
                            @if ($item->police_report)
                                <p class="mt-2"><a href="{{ asset('storage/' . $item->police_report) }}" target="_blank">View Current Report</a></p>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="photo" class="form-label">Item Photo</label>
                            @if(!$readonly)
                                <input type="file" class="form-control" name="photo" id="photo" accept=".jpg,.jpeg,.png">
                            @endif
                            @if ($item->photo)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $item->photo) }}" class="img-thumbnail" style="width: 150px;">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="remove_photo" id="remove_photo" value="1">
                                        <label class="form-check-label" for="remove_photo">Remove Photo</label>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="comment" class="form-label">Comment</label>
                    <textarea class="form-control" name="comment" id="comment" rows="3" {{ $readonly ? 'disabled' : '' }}>{{ old('comment') }}</textarea>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.items.index') }}" class="btn btn-secondary me-2">Back</a>
                    @unless($readonly)
                        <button type="submit" class="btn btn-primary">Update Item</button>
                    @endunless
                </div>
            </form>
        </div>
    </div>
    <script>
        function togglePoliceReport() {
            const status = document.getElementById('status')?.value;
            const policeField = document.getElementById('policeReportField');
            if (policeField && status === 'misplaced') {
                policeField.classList.remove('d-none');
            } else if (policeField) {
                policeField.classList.add('d-none');
            }
        }
    </script>
@endsection
