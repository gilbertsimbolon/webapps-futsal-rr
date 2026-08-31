<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Halaman login
    public function index()
    {
        return view('auth.login');
    }

    // Proses login
    public function store(Request $request)
    {
        // Validasi
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Proses autentikasi
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password yang Anda masukkan salah.',
                ])
                ->onlyInput('email');
        }

        // Regenerasi session
        $request->session()->regenerate();

        $user = Auth::user();

        // Cek status akun
        if (isset($user->status) && $user->status !== 'aktif') {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors([
                    'email' => 'Akun Anda sedang dinonaktifkan. Silakan hubungi admin.',
                ])
                ->onlyInput('email');
        }

        // Redirect berdasarkan role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'owner') {
            return redirect()->route('owner.dashboard');
        }

        // Jika role tidak dikenali
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return back()->withErrors([
            'email' => 'Role akun tidak dikenali. Silakan hubungi admin.',
        ]);
    }
}