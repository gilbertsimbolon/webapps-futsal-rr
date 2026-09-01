<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // fungsi index
    public function index(Request $request)
    {
        $returnTo = $request->get('return_to');
        return view('auth.register', compact('returnTo'));
    }

    // fungsi registrasi, (store)
    public function store(Request $request)
    {
        // validasi data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Kolom nama wajib diisi.',
            'email.required' => 'Kolom email wajib diisi.',
            'email.email' => 'Email yang Anda masukkan tidak berbentuk email.',
            'email.unique' => 'Email yang Anda masukkan sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password setidaknya memiliki 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        // jika validasi data berhasil
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole('pelanggan');

        // Otomatis login atau kembalikan ke halaman login
        if ($request->filled('return_to')) {
            return redirect()->route('login.index', ['return_to' => $request->return_to])
                ->with('success', 'Registrasi berhasil! Silakan login untuk melanjutkan booking.');
        }

        return redirect()->route('login.index')->with('success', 'Registrasi berhasil! Silakan login.');
    }
}
