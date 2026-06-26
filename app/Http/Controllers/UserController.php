<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest('id_User')->paginate(20);
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'Nama' => 'required|string|max:255',
            'Username' => 'required|string|unique:users,Username',
            'Email' => 'required|string|email|unique:users,Email',
            'Password' => 'required|string|min:6',
            'Role' => 'required|in:admin,user',
            'Status' => 'required|in:Aktif,Nonaktif',
        ]);

        $data['Password'] = bcrypt($data['Password']);
        User::create($data);

        return back()->with('success', 'User created successfully.');
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'Nama' => 'required|string|max:255',
            'Username' => 'required|string|unique:users,Username,' . $user->id_User . ',id_User',
            'Email' => 'required|string|email|unique:users,Email,' . $user->id_User . ',id_User',
            'Role' => 'required|in:admin,user',
            'Status' => 'required|in:Aktif,Nonaktif',
        ]);

        if ($request->filled('Password')) {
            $request->validate([
                'Password' => 'required|string|min:6',
            ]);
            $data['Password'] = bcrypt($request->Password);
        }

        $user->update($data);

        return back()->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id_User === auth()->user()->id_User) {
            return back()->withErrors(['error' => 'You cannot delete yourself.']);
        }
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }
}
