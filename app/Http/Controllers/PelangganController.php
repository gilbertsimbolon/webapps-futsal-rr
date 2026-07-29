<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Request;

class PelangganController extends Controller
{
    // fungsi index
    public function index()
    {
        $pelanggan = User::role('pelanggan')->get();

        return view('pelanggan.index', compact('pelanggan'));
    }

    // fungsi untuk update status akun
    public function updateStatus(User $pelanggan)
    {
        $pelanggan->update([
            'status' => $pelanggan->status === 'aktif'
                ? 'nonaktif'
                : 'aktif',
        ]);

        return back()->with('success', 'Status akun berhasil diperbarui.');
    }

    // fungsi menghapus akun
    public function destroy(Request $request, string $id)
    {
        $pelanggan = User::findOrFail($id);

        $pelanggan->delete();

        return redirect()->route('pelanggan.index')->with('success', 'Akun berhasil dihapus.');
    }
}
