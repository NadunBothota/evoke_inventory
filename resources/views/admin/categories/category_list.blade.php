<x-app-layout>
    <x-slot name="header">
        <h2>Item Categories</h2>
    </x-slot>

    <div class="p-6">
        <a href="{{ route('admin.categories.create') }}">+ Add Category</a>

        <ul class="mt-4">
            @foreach($categories as $category)
                <li>{{ $category->name }}</li>
            @endforeach
        </ul>
    </div>
</x-app-layout>
