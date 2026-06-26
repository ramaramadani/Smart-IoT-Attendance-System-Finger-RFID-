<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'Username' => ['required', 'string'],
            'Password' => ['required', 'string'],
        ]);

        $credentials = [
            'Username' => $request->Username,
            'password' => $request->Password, // Laravel Auth expects lowercase 'password' key to authenticate via getAuthPassword()
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'Username' => 'The provided credentials do not match our records.',
        ])->onlyInput('Username');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
