<x-app-layout>
    <x-slot name="header">
        <h2>Create User</h2>
    </x-slotx>

    <form method="POST" action="{{ route('admin.users.store') }}" class="p-6">
        @csrf

        <input name="name" placeholder="Name" required>
        <input name="email" type="email" placeholder="Email" required>

        <select name="role" required>
            <option value="admin">Admin</option>
            <option value="user">Normal User</option>
        </select>

        <input name="password" type="password" placeholder="Password" required>

        <button type="submit">Create</button>
    </form>
</x-app-layout>