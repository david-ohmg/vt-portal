<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index() {
        return view('login');
    }

    public function login(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        auth()->attempt($validated);
        if (!auth()->check()) {
            return back()->with('error', 'Invalid Credentials');
        }
        return redirect()->route('portal.batches')
            ->with('success', 'Logged in successfully');
    }

    public function logout() {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    }
}
