<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    // fungsi index
    public function index()
    {
        return view('profile.index');
    }

    // fungsi update data
    public function update(Request $request)
    {
        // validasi data
        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        // update data user
        $user = Auth::user();
        $user->name = $request->name ?? $user->name;
        $user->email = $request->filled('email') ? $request->email : $user->email;

        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }   
}
