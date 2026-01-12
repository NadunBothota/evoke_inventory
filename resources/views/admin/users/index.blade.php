<x-app-layout>
    <x-slot name="header">
        <h2>User Management</h2>
    </x-slot>

    <div class="p-6">
        <a href="{{ route('admin.users.create') }}" class="btn">Add User</a>

        <table class="mt-4 w-full border">
            <tr>
                <th>Name</th><th>Email</th><th>Role</th>
            </tr>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ ucfirst($user->role) }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</x-app-layoutx>