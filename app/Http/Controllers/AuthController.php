<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Users; 

class AuthController extends Controller
{
    public function loginForm() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only('username', 'password');
        $user = \App\Models\Users::where('username', $credentials['username'])->first();

        if ($user && \Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();
            $request->session()->save(); 

            return redirect()->route('dashboard');
        }
        return back()->with('error', 'Username atau Password salah!');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}