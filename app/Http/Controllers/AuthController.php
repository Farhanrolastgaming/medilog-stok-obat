<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as RoutingController;

class AuthController extends RoutingController
{
    // 1. Menampilkan halaman form login
    public function login()
    {
        return view('auth.login');
    }

    // 2. Memproses data dari form login
    public function authenticate(Request $request)
    {
        // Validasi inputan form
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba cocokkan email dan password dengan database
        if (Auth::attempt($credentials)) {
            // Jika berhasil, perbarui sesi keamanan (mencegah session fixation)
            $request->session()->regenerate();

            // Arahkan ke halaman dashboard
            return redirect()->intended('/dashboard');
        }

        // Jika gagal, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // 3. Memproses proses logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}