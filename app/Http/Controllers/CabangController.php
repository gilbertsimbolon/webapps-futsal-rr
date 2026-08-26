<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CabangController extends Controller
{
    // Daftar opsi fasilitas default
    private $availableFacilities = [
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
        $query = Branch::with('user');

        // Fitur pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('branch_name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $branches = $query->latest()->paginate(10)->withQueryString();

        // Ambil data user yang bertindak sebagai Pemilik/Admin untuk dropdown owner
        $owners = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['Pemilik', 'Admin', 'pemilik', 'admin']);
        })->get();

        if ($owners->isEmpty()) {
            $owners = User::all();
        }

        $availableFacilities = $this->availableFacilities;

        return view('cabang.index', compact('branches', 'owners', 'availableFacilities'));
    }

    // Fungsi menambahkan data cabang
    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_name'  => 'required|string|max:255',
            'phone'        => 'required|string|max:20',
            'address'      => 'required|string',
            'description'  => 'nullable|string',
            'facilities'   => 'nullable|array',
            'facilities.*' => 'string|max:100',
            'user_id'      => 'nullable|exists:users,id',
            'status'       => 'required|in:active,inactive',
        ]);

        if (empty($validated['user_id'])) {
            $validated['user_id'] = Auth::id() ?? 1;
        }

        // Ambil array fasilitas atau jadikan array kosong jika tidak dicentang
        $validated['facilities'] = $request->input('facilities', []);

        Branch::create($validated);

        return redirect()->route('cabang.index')->with('success', 'Data cabang baru berhasil ditambahkan.');
    }

    // Fungsi memperbarui data cabang
    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'branch_name'  => 'required|string|max:255',
            'phone'        => 'required|string|max:20',
            'address'      => 'required|string',
            'description'  => 'nullable|string',
            'facilities'   => 'nullable|array',
            'facilities.*' => 'string|max:100',
            'user_id'      => 'nullable|exists:users,id',
            'status'       => 'required|in:active,inactive',
        ]);

        // Simpan array fasilitas terbaru
        $validated['facilities'] = $request->input('facilities', []);

        $branch->update($validated);

        return redirect()->route('cabang.index')->with('success', 'Data cabang berhasil diperbarui.');
    }

    // Fungsi memperbarui status cabang
    public function toggleStatus(Branch $branch)
    {
        $newStatus = ($branch->status === 'active') ? 'inactive' : 'active';
        $branch->update(['status' => $newStatus]);

        $statusIndo = ($newStatus === 'active') ? 'aktif' : 'nonaktif';
        return redirect()->route('cabang.index')->with('success', "Status cabang {$branch->branch_name} berhasil diubah menjadi {$statusIndo}.");
    }

    // Fungsi menghapus data cabang
    public function destroy(Branch $branch)
    {
        $branch->delete();

        return redirect()->route('cabang.index')->with('success', 'Data cabang berhasil dihapus.');
    }
}