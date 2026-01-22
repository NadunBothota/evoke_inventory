@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Send Report</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.reports.send') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label for="recipient">Recipient</label>
                <select name="recipient" id="recipient" class="form-select @error('recipient') is-invalid @enderror">
                    @foreach ($admins as $admin)
                        <option value="{{ $admin->email }}" {{ old('recipient') == $admin->email ? 'selected' : '' }}>
                            {{ $admin->name }}
                        </option>
                    @endforeach
                </select>
                @error('recipient')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="report_type">Report Type</label>
                <select name="report_type" id="report_type" class="form-select @error('report_type') is-invalid @enderror">
                    <option value="pdf" {{ old('report_type') == 'pdf' ? 'selected' : '' }}>PDF</option>
                    <option value="excel" {{ old('report_type') == 'excel' ? 'selected' : '' }}>Excel</option>
                </select>
                @error('report_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Send</button>
        </form>
    </div>
@endsection
