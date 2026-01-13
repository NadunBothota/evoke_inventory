@extends('layouts.app')

@section('content')
    <h2 class="h4 font-weight-bold">Edit Item</h2>

    @php
        $readOnly = auth()->user()->isReadOnly();
    @endphp

    {{-- Read-only warning --}}
    @if($readOnly)
        <div class="alert alert-warning" role="alert">
            You have <strong>read-only access</strong>. Editing is disabled.
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

            <form action="{{ route('admin.items.update', $item) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <!-- Serial Number -->
                        <div class="mb-3">
                            <label for="serial_number" class="form-label">Serial Number</label>
                            <input type="text" class="form-control" id="serial_number" name="serial_number" value="{{ old('serial_number', $item->serial_number) }}" {{ $readOnly ? 'disabled' : '' }}>
                        </div>

                        <!-- Device Name -->
                        <div class="mb-3">
                            <label for="device_name" class="form-label">Device Name</label>
                            <input type="text" class="form-control" id="device_name" name="device_name" value="{{ old('device_name', $item->device_name) }}" {{ $readOnly ? 'disabled' : '' }}>
                        </div>

                        <!-- Item User -->
                        <div class="mb-3">
                            <label for="item_user" class="form-label">Item User</label>
                            <input type="text" class="form-control" id="item_user" name="item_user" value="{{ old('item_user', $item->item_user) }}" {{ $readOnly ? 'disabled' : '' }}>
                        </div>

                         <!-- Department -->
                        <div class="mb-3">
                            <label for="department" class="form-label">Department</label>
                            <input type="text" class="form-control" id="department" name="department" value="{{ old('department', $item->department) }}" {{ $readOnly ? 'disabled' : '' }}>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Reference Number -->
                        <div class="mb-3">
                            <label for="reference_number" class="form-label">Reference Number</label>
                            <input type="text" class="form-control" id="reference_number" name="reference_number" value="{{ old('reference_number', $item->reference_number) }}" readonly>
                        </div>

                        <!-- Value -->
                        <div class="mb-3">
                            <label for="value" class="form-label">Item Value</label>
                            <input type="number" step="0.01" class="form-control" id="value" name="value" value="{{ old('value', $item->value) }}" {{ $readOnly ? 'disabled' : '' }}>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" onchange="togglePoliceReport()" {{ $readOnly ? 'disabled' : '' }}>
                                <option value="working" {{ $item->status === 'working' ? 'selected' : '' }}>Working</option>
                                <option value="not_working" {{ $item->status === 'not_working' ? 'selected' : '' }}>Not Working</option>
                                <option value="misplaced" {{ $item->status === 'misplaced' ? 'selected' : '' }}>Misplaced</option>
                            </select>
                        </div>

                        <!-- Police Report -->
                        <div id="policeReportField" class="mb-3 {{ $item->status === 'misplaced' ? '' : 'd-none' }}">
                            <label for="police_report" class="form-label">Police Report</label>
                            @if(!$readOnly)
                                <input type="file" class="form-control" id="police_report" name="police_report" accept=".pdf,.jpg,.jpeg,.png">
                            @endif
                            @if ($item->police_report)
                                <p class="mt-2"><a href="{{ asset('storage/' . $item->police_report) }}" target="_blank">View Police Report</a></p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                     <div class="col-md-6">
                        <!-- Category -->
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select" id="category_id" name="category_id" {{ $readOnly ? 'disabled' : '' }}>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ $item->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Photo -->
                        <div class="mb-3">
                            <label for="photo" class="form-label">Item Photo</label>
                            @if(!$readOnly)
                                <input type="file" class="form-control" id="photo" name="photo" accept=".jpg,.jpeg,.png">
                            @endif
                            @if ($item->photo)
                                <img src="{{ asset('storage/' . $item->photo) }}" class="img-thumbnail mt-2" style="width: 150px;">
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Comment -->
                <div class="mb-3">
                    <label for="comment" class="form-label">Comment</label>
                    <textarea class="form-control" id="comment" name="comment" rows="3" {{ $readOnly ? 'disabled' : '' }}>{{ old('comment', $item->comment) }}</textarea>
                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.items.index') }}" class="btn btn-secondary me-2">Back</a>
                    @if(!$readOnly)
                        <button type="submit" class="btn btn-success">Update Item</button>
                    @endif
                </div>

            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function togglePoliceReport() {
            const status = document.getElementById('status')?.value;
            const policeField = document.getElementById('policeReportField');

            if (!policeField) return;

            if (status === 'misplaced') {
                policeField.classList.remove('d-none');
            } else {
                policeField.classList.add('d-none');
            }
        }
    </script>
@endsection
