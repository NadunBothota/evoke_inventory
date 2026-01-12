<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Edit Item</h2>
    </x-slot>

    @php
        $readOnly = auth()->user()->isReadOnly();
    @endphp

    {{-- Read-only warning --}}
    @if($readOnly)
        <div class="max-w-4xl mx-auto mt-4 mb-4 bg-yellow-100 text-yellow-800 p-4 rounded border border-yellow-300">
            You have <strong>read-only access</strong>. Editing is disabled.
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

        <form action="{{ route('admin.items.update', $item) }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Serial Number -->
            <div class="mb-4">
                <label class="block">Serial Number</label>
                <input type="text"
                       name="serial_number"
                       value="{{ old('serial_number', $item->serial_number) }}"
                       class="w-full border p-2 bg-gray-100"
                       {{ $readOnly ? 'disabled' : '' }}>
            </div>

            <!-- Device Name -->
            <div class="mb-4">
                <label class="block">Device Name</label>
                <input type="text"
                       name="device_name"
                       value="{{ old('device_name', $item->device_name) }}"
                       class="w-full border p-2 bg-gray-100"
                       {{ $readOnly ? 'disabled' : '' }}>
            </div>

            <!-- Item User -->
            <div class="mb-4">
                <label class="block">Item User</label>
                <input type="text"
                       name="item_user"
                       value="{{ old('item_user', $item->item_user) }}"
                       class="w-full border p-2 bg-gray-100"
                       {{ $readOnly ? 'disabled' : '' }}>
            </div>

            <!-- Department -->
            <div class="mb-4">
                <label class="block">Department</label>
                <input type="text"
                       name="department"
                       value="{{ old('department', $item->department) }}"
                       class="w-full border p-2 bg-gray-100"
                       {{ $readOnly ? 'disabled' : '' }}>
            </div>

            <!-- Reference Number -->
            <div class="mb-4">
                <label class="block">Reference Number</label>
                <input type="text"
                       name="reference_number"
                       value="{{ old('reference_number', $item->reference_number) }}"
                       class="w-full border p-2 bg-gray-100"
                       {{ $readOnly ? 'disabled' : '' }}>
            </div>

            <!-- Value -->
            <div class="mb-4">
                <label class="block">Item Value</label>
                <input type="number"
                       step="0.01"
                       name="value"
                       value="{{ old('value', $item->value) }}"
                       class="w-full border p-2 bg-gray-100"
                       {{ $readOnly ? 'disabled' : '' }}>
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label class="block">Status</label>
                <select name="status"
                        id="status"
                        class="w-full border p-2 bg-gray-100"
                        onchange="togglePoliceReport()"
                        {{ $readOnly ? 'disabled' : '' }}>
                    <option value="working" {{ $item->status === 'working' ? 'selected' : '' }}>
                        Working
                    </option>
                    <option value="not_working" {{ $item->status === 'not_working' ? 'selected' : '' }}>
                        Not Working
                    </option>
                    <option value="misplaced" {{ $item->status === 'misplaced' ? 'selected' : '' }}>
                        Misplaced
                    </option>
                </select>
            </div>

            <!-- Police Report -->
            <div id="policeReportField"
                 class="mb-4 {{ $item->status === 'misplaced' ? '' : 'hidden' }}">
                <label class="block">Police Report</label>

                @if(!$readOnly)
                    <input type="file"
                           name="police_report"
                           accept=".pdf,.jpg,.jpeg,.png"
                           class="w-full border p-2">
                @endif

                @if ($item->police_report)
                    <p class="text-sm mt-2">
                        Existing Report:
                        <a href="{{ asset('storage/' . $item->police_report) }}"
                           target="_blank"
                           class="text-blue-600 underline">
                            View Police Report
                        </a>
                    </p>
                @endif
            </div>

            <!-- Category -->
            <div class="mb-4">
                <label class="block">Category</label>
                <select name="category_id"
                        class="w-full border p-2 bg-gray-100"
                        {{ $readOnly ? 'disabled' : '' }}>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ $item->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Photo -->
            <div class="mb-4">
                <label class="block">Item Photo</label>

                @if(!$readOnly)
                    <input type="file"
                           name="photo"
                           accept=".jpg,.jpeg,.png"
                           class="w-full border p-2">
                @endif

                @if ($item->photo)
                    <img src="{{ asset('storage/' . $item->photo) }}"
                         class="mt-2 w-24 rounded border">
                @endif
            </div>

            <!-- Comment -->
            <div class="mb-4">
                <label class="block">Comment</label>
                <textarea name="comment"
                          rows="3"
                          class="w-full border p-2 bg-gray-100"
                          {{ $readOnly ? 'disabled' : '' }}>{{ old('comment', $item->comment) }}</textarea>
            </div>

            <!-- Actions -->
            <div class="flex justify-end">
                <a href="{{ route('admin.items.index') }}"
                   class="mr-4 text-gray-600">
                    Back
                </a>

                @if(!$readOnly)
                    <button type="submit"
                            class="bg-green-600 text-white px-4 py-2 rounded">
                        Update Item
                    </button>
                @endif
            </div>

        </form>
    </div>

    <!-- JavaScript -->
    <script>
        function togglePoliceReport() {
            const status = document.getElementById('status')?.value;
            const policeField = document.getElementById('policeReportField');

            if (!policeField) return;

            if (status === 'misplaced') {
                policeField.classList.remove('hidden');
            } else {
                policeField.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
