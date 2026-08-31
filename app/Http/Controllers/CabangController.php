<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CabangController extends Controller
{
    // Daftar opsi fasilitas default
    private array $availableFacilities = [
        'Parkir Luas',
        'Toilet & Kamar Mandi',
        'Musholla',
        'Kantin / Warung',
        'Free WiFi',
        'Loker Penyimpanan',
        'Tribun Penonton',
        'Sewa Sepatu / Rompi',
    ];

    // Fungsi menampilkan daftar cabang
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Branch::with('user');

        // Admin dapat melihat seluruh data cabang
        if ($user->hasRole('admin')) {
            // Tidak perlu filter user_id
        }

        // Pemilik hanya dapat melihat cabang miliknya sendiri
        elseif ($user->hasRole('pemilik')) {
            $query->where('user_id', $user->id);
        }

        // Jika user tidak memiliki role yang sesuai
        else {
            abort(403, 'Anda tidak memiliki akses ke data cabang.');
        }

        // Fitur pencarian
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('branch_name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $branches = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Admin dapat memilih pemilik saat membuat atau mengedit cabang
        if ($user->hasRole('admin')) {
            $owners = User::role('pemilik')
                ->orderBy('name')
                ->get();
        } else {
            // Pemilik hanya mendapatkan dirinya sendiri
            $owners = collect([$user]);
        }

        $availableFacilities = $this->availableFacilities;

        return view('cabang.index', compact(
            'branches',
            'owners',
            'availableFacilities'
        ));
    }

    // Fungsi menambahkan data cabang
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'branch_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'description' => 'nullable|string',
            'facilities' => 'nullable|array',
            'facilities.*' => 'string|max:100',
            'user_id' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive',
        ]);

        // Admin dapat menentukan pemilik cabang
        if ($user->hasRole('admin')) {
            if (empty($validated['user_id'])) {
                return back()
                    ->withErrors([
                        'user_id' => 'Pemilik cabang wajib dipilih.',
                    ])
                    ->withInput();
            }

            // Pastikan user yang dipilih benar-benar memiliki role pemilik
            $owner = User::role('pemilik')
                ->where('id', $validated['user_id'])
                ->first();

            if (!$owner) {
                return back()
                    ->withErrors([
                        'user_id' => 'Pemilik yang dipilih tidak valid.',
                    ])
                    ->withInput();
            }
        }

        // Pemilik otomatis menjadi pemilik cabang yang dibuat
        elseif ($user->hasRole('pemilik')) {
            $validated['user_id'] = $user->id;
        }

        // User tanpa role
        else {
            abort(403, 'Anda tidak memiliki akses untuk menambahkan cabang.');
        }

        // Pastikan facilities selalu berupa array
        $validated['facilities'] = $request->input('facilities', []);

        Branch::create($validated);

        return $this->redirectToCabangIndex()
            ->with('success', 'Data cabang baru berhasil ditambahkan.');
    }

    // Fungsi memperbarui data cabang
    public function update(Request $request, Branch $branch)
    {
        $user = Auth::user();

        // Pemilik hanya boleh mengubah cabangnya sendiri
        if ($user->hasRole('pemilik') && $branch->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah cabang ini.');
        }

        // Hanya admin dan pemilik yang dapat mengubah cabang
        if (!$user->hasAnyRole(['admin', 'pemilik'])) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah data cabang.');
        }

        $validated = $request->validate([
            'branch_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'description' => 'nullable|string',
            'facilities' => 'nullable|array',
            'facilities.*' => 'string|max:100',
            'user_id' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive',
        ]);

        // Admin dapat mengganti pemilik cabang
        if ($user->hasRole('admin')) {
            if (empty($validated['user_id'])) {
                return back()
                    ->withErrors([
                        'user_id' => 'Pemilik cabang wajib dipilih.',
                    ])
                    ->withInput();
            }

            $owner = User::role('pemilik')
                ->where('id', $validated['user_id'])
                ->first();

            if (!$owner) {
                return back()
                    ->withErrors([
                        'user_id' => 'Pemilik yang dipilih tidak valid.',
                    ])
                    ->withInput();
            }
        }

        // Pemilik tidak boleh memindahkan kepemilikan cabang
        elseif ($user->hasRole('pemilik')) {
            $validated['user_id'] = $user->id;
        }

        // Pastikan facilities selalu berupa array
        $validated['facilities'] = $request->input('facilities', []);

        $branch->update($validated);

        return $this->redirectToCabangIndex()
            ->with('success', 'Data cabang berhasil diperbarui.');
    }

    // Fungsi memperbarui status cabang
    public function toggleStatus(Branch $branch)
    {
        $user = Auth::user();

        // Pemilik hanya boleh mengubah status cabangnya sendiri
        if ($user->hasRole('pemilik') && $branch->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah status cabang ini.');
        }

        // Pastikan hanya admin atau pemilik yang dapat mengubah status
        if (!$user->hasAnyRole(['admin', 'pemilik'])) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah status cabang.');
        }

        $newStatus = $branch->status === 'active'
            ? 'inactive'
            : 'active';

        $branch->update([
            'status' => $newStatus,
        ]);

        $statusIndo = $newStatus === 'active'
            ? 'aktif'
            : 'nonaktif';

        return $this->redirectToCabangIndex()
            ->with(
                'success',
                "Status cabang {$branch->branch_name} berhasil diubah menjadi {$statusIndo}."
            );
    }

    // Fungsi menghapus data cabang
    public function destroy(Branch $branch)
    {
        $user = Auth::user();

        // Pemilik hanya boleh menghapus cabangnya sendiri
        if ($user->hasRole('pemilik') && $branch->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus cabang ini.');
        }

        // Pastikan hanya admin atau pemilik yang dapat menghapus
        if (!$user->hasAnyRole(['admin', 'pemilik'])) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus data cabang.');
        }

        $branch->delete();

        return $this->redirectToCabangIndex()
            ->with('success', 'Data cabang berhasil dihapus.');
    }

    // Fungsi menentukan route kembali ke halaman cabang sesuai role
    private function redirectToCabangIndex()
    {
        if (Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.cabang.index');
        }

        return redirect()->route('pemilik.cabang.index');
    }
}