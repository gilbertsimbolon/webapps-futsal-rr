<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LapanganController extends Controller
{
    // fungsi index
    public function index(Request $request)
    {
        $query = Field::with('branch');

        // Filter Pencarian Nama Lapangan
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('field_name', 'like', "%{$search}%");
        }

        // Filter Berdasarkan Cabang
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        // Filter Berdasarkan Tipe Lapangan
        if ($request->filled('field_type')) {
            $query->where('field_type', $request->field_type);
        }

        $fields = $query->latest()->paginate(10)->withQueryString();
        $branches = Branch::where('status', 'active')->get();

        return view('lapangan.index', compact('fields', 'branches'));
    }

    // fungsi menambahkan data
    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id'        => 'required|exists:branches,id',
            'field_name'       => 'required|string|max:255',
            'field_type'       => 'required|in:sintetis,vinyl,interlock,matras,semen',
            'price_per_hour'   => 'required|numeric|min:0',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description'      => 'nullable|string',
            'specifications'   => 'nullable|array',
            'specifications.*' => 'string|max:150',
            'status'           => 'required|in:available,maintenance,inactive',
        ]);

        // Upload Gambar jika ada
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('fields', 'public');
        }

        // Simpan array spesifikasi atau set array kosong jika tidak ada
        $validated['specifications'] = $request->input('specifications', []);

        Field::create($validated);

        return redirect()->route('lapangan.index')->with('success', 'Data lapangan baru berhasil ditambahkan.');
    }

    // fungsi memperbarui data
    public function update(Request $request, Field $field)
    {
        $validated = $request->validate([
            'branch_id'        => 'required|exists:branches,id',
            'field_name'       => 'required|string|max:255',
            'field_type'       => 'required|in:sintetis,vinyl,interlock,matras,semen',
            'price_per_hour'   => 'required|numeric|min:0',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description'      => 'nullable|string',
            'specifications'   => 'nullable|array',
            'specifications.*' => 'string|max:150',
            'status'           => 'required|in:available,maintenance,inactive',
        ]);

        // Upload Gambar baru dan hapus file lama jika ada
        if ($request->hasFile('image')) {
            if ($field->image && Storage::disk('public')->exists($field->image)) {
                Storage::disk('public')->delete($field->image);
            }
            $validated['image'] = $request->file('image')->store('fields', 'public');
        }

        // Simpan array spesifikasi yang telah diperbarui
        $validated['specifications'] = $request->input('specifications', []);

        $field->update($validated);

        return redirect()->route('lapangan.index')->with('success', 'Data lapangan berhasil diperbarui.');
    }

    // fungsi memperbarui status lapangan
    public function toggleStatus(Field $field)
    {
        $newStatus = ($field->status === 'available') ? 'inactive' : 'available';
        $field->update(['status' => $newStatus]);

        $statusIndo = ($newStatus === 'available') ? 'tersedia' : 'nonaktif';
        return redirect()->route('lapangan.index')->with('success', "Status lapangan {$field->field_name} berhasil diubah menjadi {$statusIndo}.");
    }

    // fungsi menghapus data lapangan
    public function destroy(Field $field)
    {
        // Hapus file gambar dari storage jika ada
        if ($field->image && Storage::disk('public')->exists($field->image)) {
            Storage::disk('public')->delete($field->image);
        }

        $field->delete();

        return redirect()->route('lapangan.index')->with('success', 'Data lapangan berhasil dihapus.');
    }
}