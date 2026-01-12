<x-app-layout>
    <x-slot name="header">
        <h2>Create Category</h2>
    </x-slot>

    <form method="POST" action="{{ route('admin.categories.store') }}" class="p-6">
        @csrf
        <input name="name" placeholder="Category Name" required>
        <textarea name="description"></textarea>
        <button>Create</button>
    </form>
</x-app-layout>
