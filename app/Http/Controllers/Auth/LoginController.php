<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Halaman login
    public function index(Request $request)
    {
        $returnTo = $request->get('return_to');
        return view('auth.login', compact('returnTo'));
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

        // Redirect berdasarkan role Spatie
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('pemilik')) {
            return redirect()->route('pemilik.dashboard');
        }

        if ($user->hasRole('pelanggan')) {
            if ($request->filled('return_to')) {
                return redirect($request->return_to);
            }
            return redirect()->intended(route('landing'));
        }

        // Jika role tidak dikenali
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return back()
            ->withErrors([
                'email' => 'Role akun tidak dikenali. Silakan hubungi admin.',
            ])
            ->onlyInput('email');
    }
}
