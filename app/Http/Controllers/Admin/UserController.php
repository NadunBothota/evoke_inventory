<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'super_admin')->get();
        return view('admin.users.index', compact('users'));
    }
    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:admin, user',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email'=> $request->email,
            'role'=> $request->role,
            'password'=> Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'user created successfully');
    }
}
