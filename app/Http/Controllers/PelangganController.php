<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PelangganController extends Controller
{
    // fungsi index
    public function index()
    {
        $pelanggan = User::role('pelanggan')->get();

        return view('pelanggan.index', compact('pelanggan'));
    }

    // fungsi update
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore(Auth::id()),
            ],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
        ]);

        Auth::user()->update($validated);

        return redirect()
            ->route('profil.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
