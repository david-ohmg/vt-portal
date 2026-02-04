<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function index() {
        return view('login');
    }

    public function login(Request $request, $remember = false) {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(Auth::attempt($validated, $remember)) {
            $vt_id = User::where('email', $validated['email'])->value('vt_id');
            Cookie::queue(Cookie::make(
                name: 'ohmg-vt-id',
                value: (string) $vt_id,
                minutes: 60,
                path: '/',
                domain: null,
                secure: true,     // set true if you're on HTTPS (recommended)
                httpOnly: true,   // JS can't read it
                raw: false,
                sameSite: 'Lax'
            ));
            return redirect()->intended('/portal/batches/');
        }
        return redirect()->back()->with('error', 'Invalid credentials.');
    }

    public function logout() {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
