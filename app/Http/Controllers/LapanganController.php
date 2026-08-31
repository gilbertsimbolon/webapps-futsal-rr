<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LapanganController extends Controller
{
    /**
     * Fungsi menampilkan daftar lapangan
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Field::with('branch');

        /**
         * Admin dapat melihat seluruh data lapangan
         */
        if ($user->hasRole('admin')) {
            // Tidak perlu filter berdasarkan user_id
        }

        /**
         * Pemilik hanya dapat melihat lapangan
         * yang berada di cabang miliknya sendiri
         */
        elseif ($user->hasRole('pemilik')) {
            $query->whereHas('branch', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        /**
         * User tanpa role yang sesuai
         */
        else {
            abort(403, 'Anda tidak memiliki akses ke data lapangan.');
        }

        /**
         * Filter pencarian nama lapangan
         */
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where('field_name', 'like', "%{$search}%");
        }

        /**
         * Filter berdasarkan cabang
         */
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        /**
         * Filter berdasarkan tipe lapangan
         */
        if ($request->filled('field_type')) {
            $query->where('field_type', $request->field_type);
        }

        /**
         * Pagination
         */
        $fields = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /**
         * Daftar cabang untuk filter
         *
         * Admin   -> seluruh cabang aktif
         * Pemilik -> hanya cabang aktif miliknya
         */
        if ($user->hasRole('admin')) {
            $branches = Branch::where('status', 'active')
                ->orderBy('branch_name')
                ->get();
        } else {
            $branches = Branch::where('user_id', $user->id)
                ->where('status', 'active')
                ->orderBy('branch_name')
                ->get();
        }

        return view('lapangan.index', compact(
            'fields',
            'branches'
        ));
    }

    /**
     * Fungsi menambahkan data lapangan
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        /**
         * Pastikan hanya admin atau pemilik
         * yang dapat menambahkan lapangan
         */
        if (!$user->hasAnyRole(['admin', 'pemilik'])) {
            abort(
                403,
                'Anda tidak memiliki akses untuk menambahkan lapangan.'
            );
        }

        /**
         * Validasi input
         */
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'field_name' => 'required|string|max:255',
            'field_type' => 'required|in:sintetis,vinyl,interlock,matras,semen',
            'price_per_hour' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
            'specifications' => 'nullable|array',
            'specifications.*' => 'string|max:150',
            'status' => 'required|in:available,maintenance,inactive',
        ]);

        /**
         * Pastikan cabang yang dipilih valid
         *
         * Admin:
         * - Boleh memilih semua cabang
         *
         * Pemilik:
         * - Hanya boleh memilih cabang miliknya
         */
        $branchQuery = Branch::where('id', $validated['branch_id']);

        if ($user->hasRole('pemilik')) {
            $branchQuery->where('user_id', $user->id);
        }

        $branch = $branchQuery->first();

        if (!$branch) {
            return back()
                ->withErrors([
                    'branch_id' => 'Cabang yang dipilih tidak valid atau bukan milik Anda.',
                ])
                ->withInput();
        }

        /**
         * Upload gambar jika ada
         */
        if ($request->hasFile('image')) {
            $validated['image'] = $request
                ->file('image')
                ->store('fields', 'public');
        }

        /**
         * Pastikan specifications selalu berupa array
         */
        $validated['specifications'] = $request->input(
            'specifications',
            []
        );

        /**
         * Simpan data
         */
        Field::create($validated);

        return $this->redirectToLapanganIndex()
            ->with(
                'success',
                'Data lapangan baru berhasil ditambahkan.'
            );
    }

    /**
     * Fungsi memperbarui data lapangan
     */
    public function update(Request $request, Field $field)
    {
        $user = Auth::user();

        /**
         * Hanya admin dan pemilik
         * yang dapat mengubah data
         */
        if (!$user->hasAnyRole(['admin', 'pemilik'])) {
            abort(
                403,
                'Anda tidak memiliki akses untuk mengubah data lapangan.'
            );
        }

        /**
         * Pemilik hanya boleh mengubah lapangan
         * yang berada di cabangnya sendiri
         */
        if (
            $user->hasRole('pemilik') &&
            $field->branch?->user_id !== $user->id
        ) {
            abort(
                403,
                'Anda tidak memiliki akses untuk mengubah lapangan ini.'
            );
        }

        /**
         * Validasi input
         */
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'field_name' => 'required|string|max:255',
            'field_type' => 'required|in:sintetis,vinyl,interlock,matras,semen',
            'price_per_hour' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
            'specifications' => 'nullable|array',
            'specifications.*' => 'string|max:150',
            'status' => 'required|in:available,maintenance,inactive',
        ]);

        /**
         * Pastikan branch tujuan valid
         *
         * Admin:
         * - Boleh memindahkan ke cabang mana saja
         *
         * Pemilik:
         * - Hanya boleh memindahkan ke cabang miliknya sendiri
         */
        $branchQuery = Branch::where('id', $validated['branch_id']);

        if ($user->hasRole('pemilik')) {
            $branchQuery->where('user_id', $user->id);
        }

        $branch = $branchQuery->first();

        if (!$branch) {
            return back()
                ->withErrors([
                    'branch_id' => 'Cabang yang dipilih tidak valid atau bukan milik Anda.',
                ])
                ->withInput();
        }

        /**
         * Upload gambar baru
         * dan hapus gambar lama
         */
        if ($request->hasFile('image')) {
            if (
                $field->image &&
                Storage::disk('public')->exists($field->image)
            ) {
                Storage::disk('public')->delete($field->image);
            }

            $validated['image'] = $request
                ->file('image')
                ->store('fields', 'public');
        }

        /**
         * Pastikan specifications selalu berupa array
         */
        $validated['specifications'] = $request->input(
            'specifications',
            []
        );

        /**
         * Update data
         */
        $field->update($validated);

        return $this->redirectToLapanganIndex()
            ->with(
                'success',
                'Data lapangan berhasil diperbarui.'
            );
    }

    /**
     * Fungsi memperbarui status lapangan
     */
    public function toggleStatus(Field $field)
    {
        $user = Auth::user();

        /**
         * Hanya admin dan pemilik
         * yang dapat mengubah status
         */
        if (!$user->hasAnyRole(['admin', 'pemilik'])) {
            abort(
                403,
                'Anda tidak memiliki akses untuk mengubah status lapangan.'
            );
        }

        /**
         * Pemilik hanya boleh mengubah status
         * lapangan di cabangnya sendiri
         */
        if (
            $user->hasRole('pemilik') &&
            $field->branch?->user_id !== $user->id
        ) {
            abort(
                403,
                'Anda tidak memiliki akses untuk mengubah status lapangan ini.'
            );
        }

        /**
         * Toggle status
         */
        $newStatus = $field->status === 'available'
            ? 'inactive'
            : 'available';

        $field->update([
            'status' => $newStatus,
        ]);

        $statusIndo = $newStatus === 'available'
            ? 'tersedia'
            : 'nonaktif';

        return $this->redirectToLapanganIndex()
            ->with(
                'success',
                "Status lapangan {$field->field_name} berhasil diubah menjadi {$statusIndo}."
            );
    }

    /**
     * Fungsi menghapus data lapangan
     */
    public function destroy(Field $field)
    {
        $user = Auth::user();

        /**
         * Hanya admin dan pemilik
         * yang dapat menghapus
         */
        if (!$user->hasAnyRole(['admin', 'pemilik'])) {
            abort(
                403,
                'Anda tidak memiliki akses untuk menghapus data lapangan.'
            );
        }

        /**
         * Pemilik hanya boleh menghapus
         * lapangan miliknya sendiri
         */
        if (
            $user->hasRole('pemilik') &&
            $field->branch?->user_id !== $user->id
        ) {
            abort(
                403,
                'Anda tidak memiliki akses untuk menghapus lapangan ini.'
            );
        }

        /**
         * Hapus gambar dari storage
         */
        if (
            $field->image &&
            Storage::disk('public')->exists($field->image)
        ) {
            Storage::disk('public')->delete($field->image);
        }

        /**
         * Hapus data lapangan
         */
        $field->delete();

        return $this->redirectToLapanganIndex()
            ->with(
                'success',
                'Data lapangan berhasil dihapus.'
            );
    }

    /**
     * Fungsi menentukan route kembali
     * sesuai role pengguna
     */
    private function redirectToLapanganIndex()
    {
        if (Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.lapangan.index');
        }

        return redirect()->route('pemilik.lapangan.index');
    }
}