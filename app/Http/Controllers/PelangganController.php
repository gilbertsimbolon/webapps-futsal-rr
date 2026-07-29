<?php

namespace App\Http\Controllers;

use App\Models\User;

class PelangganController extends Controller
{
    // fungsi index
    public function index()
    {
        $pelanggan = User::role('pelanggan')->get();

        return view('pelanggan.index', compact('pelanggan'));
    }

    // fungsi untuk menghapus akun
    public function updateStatus(User $pelanggan)
    {
        $pelanggan->update([
            'status' => $pelanggan->status === 'aktif'
                ? 'nonaktif'
                : 'aktif',
        ]);

        return back()->with('success', 'Status akun berhasil diperbarui.');
    }
}
