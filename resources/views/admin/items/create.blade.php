<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Add New Item</h2>
    </x-slot>

    {{-- Read-only warning --}}
    @if(auth()->user()->isReadOnly())
        <div class="max-w-4xl mx-auto mt-6 bg-yellow-100 text-yellow-800 p-4 rounded">
            You have read-only access. Creating new items is disabled.
        </div>
    @endif

    <div class="max-w-4xl mx-auto mt-6 bg-white p-6 shadow rounded">

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="mb-4 text-red-600">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.items.store') }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf

            @php
                $readonly = auth()->user()->isReadOnly();
            @endphp

            <!-- Serial Number -->
            <div class="mb-4">
                <label class="block">Serial Number</label>
                <input type="text"
                       name="serial_number"
                       value="{{ old('serial_number') }}"
                       class="w-full border p-2"
                       {{ $readonly ? 'disabled' : 'required' }}>
            </div>

            <!-- Device Name -->
            <div class="mb-4">
                <label class="block">Device Name</label>
                <input type="text"
                       name="device_name"
                       value="{{ old('device_name') }}"
                       class="w-full border p-2"
                       {{ $readonly ? 'disabled' : 'required' }}>
            </div>

            <!-- Item User -->
            <div class="mb-4">
                <label class="block">Item User</label>
                <input type="text"
                       name="item_user"
                       value="{{ old('item_user') }}"
                       class="w-full border p-2"
                       {{ $readonly ? 'disabled' : 'required' }}>
            </div>

            <!-- Department -->
            <div class="mb-4">
                <label class="block">Department</label>
                <input type="text"
                       name="department"
                       value="{{ old('department') }}"
                       class="w-full border p-2"
                       {{ $readonly ? 'disabled' : 'required' }}>
            </div>

            <!-- Reference Number -->
            <div class="mb-4">
                <label class="block">Reference Number</label>
                <input type="text"
                       name="reference_number"
                       value="{{ old('reference_number') }}"
                       class="w-full border p-2"
                       {{ $readonly ? 'disabled' : 'required' }}>
            </div>

            <!-- Value -->
            <div class="mb-4">
                <label class="block">Item Value</label>
                <input type="number"
                       step="0.01"
                       name="value"
                       value="{{ old('value') }}"
                       class="w-full border p-2"
                       {{ $readonly ? 'disabled' : 'required' }}>
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label class="block">Status</label>
                <select name="status"
                        id="status"
                        class="w-full border p-2"
                        onchange="togglePoliceReport()"
                        {{ $readonly ? 'disabled' : 'required' }}>
                    <option value="">-- Select Status --</option>
                    <option value="working">Working</option>
                    <option value="not_working">Not Working</option>
                    <option value="misplaced">Misplaced</option>
                </select>
            </div>

            <!-- Police Report -->
            <div id="policeReportField" class="mb-4 hidden">
                <label class="block">Police Report</label>
                <input type="file"
                       name="police_report"
                       accept=".pdf,.jpg,.jpeg,.png"
                       class="w-full border p-2"
                       {{ $readonly ? 'disabled' : '' }}>
            </div>

            <!-- Category -->
            <div class="mb-4">
                <label class="block">Category</label>
                <select name="category_id"
                        class="w-full border p-2"
                        {{ $readonly ? 'disabled' : 'required' }}>
                    <option value="">-- Select Category --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Photo -->
            <div class="mb-4">
                <label class="block">Item Photo</label>
                <input type="file"
                       name="photo"
                       accept=".jpg,.jpeg,.png"
                       class="w-full border p-2"
                       {{ $readonly ? 'disabled' : '' }}>
            </div>

            <!-- Comment -->
            <div class="mb-4">
                <label class="block">Comment</label>
                <textarea name="comment"
                          class="w-full border p-2"
                          rows="3"
                          {{ $readonly ? 'disabled' : '' }}>{{ old('comment') }}</textarea>
            </div>

            <!-- Submit -->
            <div class="flex justify-end">
                <a href="{{ route('admin.items.index') }}"
                   class="mr-4 text-gray-600">
                    Cancel
                </a>

                @unless($readonly)
                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded">
                        Save Item
                    </button>
                @endunless
            </div>

        </form>
    </div>

    <script>
        function togglePoliceReport() {
            const status = document.getElementById('status').value;
            const policeField = document.getElementById('policeReportField');

            if (status === 'misplaced') {
                policeField.classList.remove('hidden');
            } else {
                policeField.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
