<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            User Dashboard
        </h2>
    </x-slot>

    <div class="p-6">
        <p>Welcome, {{ auth()->user()->name }}</p>
        <p class="mt-4">You can view inventory items only.</p>
    </div>
</x-app-layoutx>