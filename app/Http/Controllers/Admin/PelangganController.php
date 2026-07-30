<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;


class PelangganController extends Controller
{
    // fungsi index
    public function index()
    {
        $pelanggan = User::role('pelanggan')->get();

        return view('admin.pelanggan.index', compact('pelanggan'));
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

        return redirect()->route('admin.pelanggan.index')->with('success', 'Akun berhasil dihapus.');
    }

    // fungsi mengupdate role akun
    public function updateRole(Request $request, string $id)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::findOrFail($id);

        $user->syncRoles($request->role);

        return redirect()->back()->with('success', "Role {$user->name} berhasil diubah menjadi {$request->role}!");
    }
}
