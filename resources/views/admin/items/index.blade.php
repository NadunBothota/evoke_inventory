<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold">Items</h2>

            @unless(auth()->user()->isReadOnly())
                <a href="{{ route('admin.items.create') }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded">
                    Add Item
                </a>
            @endunless
        </div>
    </x-slot>

    <div class="mt-6 bg-white shadow rounded p-4">

        <table class="w-full border-collapse border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2">Serial</th>
                    <th class="border p-2">Device</th>
                    <th class="border p-2">User</th>
                    <th class="border p-2">Status</th>
                    <th class="border p-2">Category</th>
                    <th class="border p-2">Photo</th>
                    <th class="border p-2">Police Report</th>
                    <th class="border p-2">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td class="border p-2">{{ $item->serial_number }}</td>
                        <td class="border p-2">{{ $item->device_name }}</td>
                        <td class="border p-2">{{ $item->item_user }}</td>
                        <td class="border p-2">{{ ucfirst($item->status) }}</td>
                        <td class="border p-2">{{ $item->category->name }}</td>

                        <!-- Photo -->
                        <td class="border p-2">
                            @if ($item->photo)
                                <img src="{{ asset('storage/' . $item->photo) }}"
                                     class="w-16 h-16 object-cover rounded border">
                            @else
                                <span class="text-gray-400">No Image</span>
                            @endif
                        </td>

                        <!-- Police Report -->
                        <td class="border p-2 text-center">
                            @if ($item->police_report)
                                <a href="{{ asset('storage/' . $item->police_report) }}"
                                   target="_blank"
                                   class="text-blue-600 underline">
                                    View
                                </a>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="border p-2 text-center">
                            @unless(auth()->user()->isReadOnly())
                                <a href="{{ route('admin.items.edit', $item) }}"
                                   class="text-blue-600 mr-2">
                                    Edit
                                </a>

                                <form action="{{ route('admin.items.destroy', $item) }}"
                                      method="POST"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="text-red-600"
                                            onclick="return confirm('Delete this item?')">
                                        Delete
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-400">View only</span>
                            @endunless
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8"
                            class="text-center p-4 text-gray-500">
                            No items found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</x-app-layout>
