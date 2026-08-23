<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class PenggunaController extends Controller
{
    // fungsi index
    public function index(Request $request)
    {
        $query = User::with('roles');

        // Filter Pencarian (Nama / Email)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter Berdasarkan Role Spatie
        if ($request->filled('role')) {
            $query->role($request->role);
        }

        // Filter Berdasarkan Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        $roles = Role::all();

        return view('pengguna.index', compact('users', 'roles'));
    }

    // fungsi tambah data user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'role'     => 'required|string|exists:roles,name',
            'password' => 'required|string|min:8',
            'status'   => 'required|in:aktif,nonaktif',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'status'   => $validated['status'],
        ]);

        // Assign role Spatie
        $user->assignRole($validated['role']);

        return redirect()->route('pengguna.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    // fungsi memperbarui data
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role'     => 'required|string|exists:roles,name',
            'status'   => 'required|in:aktif,nonaktif',
            'password' => 'nullable|string|min:8',
        ]);

        $updateData = [
            'name'   => $validated['name'],
            'email'  => $validated['email'],
            'status' => $validated['status'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        // Sync role Spatie
        $user->syncRoles([$validated['role']]);

        return redirect()->route('pengguna.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    // fungsi memperbarui status akun
    public function toggleStatus(User $user)
    {
        // Ubah status aktif <-> nonaktif
        $newStatus = ($user->status === 'aktif') ? 'nonaktif' : 'aktif';
        $user->update(['status' => $newStatus]);

        return redirect()->route('pengguna.index')->with('success', "Status akun {$user->name} berhasil diubah menjadi {$newStatus}.");
    }

    // fungsi menghapus data
    public function destroy(User $user, string $id)
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('pengguna.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
