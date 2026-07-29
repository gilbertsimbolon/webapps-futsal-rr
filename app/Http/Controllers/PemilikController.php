<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PemilikController extends Controller
{
    // fungsi index
    public function index()
    {
        $pemilik = User::role('pemilik')->get();

        return view('pemilik.index', compact('pemilik'));
    }

    // fungsi untuk
    public function updateStatus(User $pemilik)
    {
        $pemilik->update([
            'status' => $pemilik->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        return back()->with('success', 'Status akun berhasil diperbarui.');
    }

    // fungsi hapus akun
    public function destroy(Request $request, string $id)
    {
        $pemilik = User::findOrFail($id);

        $pemilik->delete();

        return redirect()->route('pemilik.index')->with('success', 'Akun berhasil dihapus.');
    }
}
