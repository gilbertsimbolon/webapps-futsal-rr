<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MetodePembayaranController extends Controller
{
    // Fungsi menampilkan daftar metode pembayaran
    public function index(Request $request)
    {
        $query = PaymentMethod::query();

        // Filter pencarian nama metode / nomor rekening / atas nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('account_number', 'like', "%{$search}%")
                    ->orWhere('account_name', 'like', "%{$search}%");
            });
        }

        // Filter tipe pembayaran
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $paymentMethods = $query->latest()->paginate(10)->withQueryString();

        return view('metode_pembayaran.index', compact('paymentMethods'));
    }

    // Fungsi menambahkan metode pembayaran baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'type'           => 'required|in:bank_transfer,qris,cash',
            'account_number' => 'nullable|string|max:100',
            'account_name'   => 'nullable|string|max:255',
            'qr_image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'instructions'   => 'nullable|string',
            'status'         => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('qr_image')) {
            $validated['qr_image'] = $request->file('qr_image')->store('payment_qr', 'public');
        }

        PaymentMethod::create($validated);

        return redirect()->route('metode-pembayaran.index')->with('success', 'Metode pembayaran baru berhasil ditambahkan.');
    }

    // Fungsi memperbarui data metode pembayaran
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'type'           => 'required|in:bank_transfer,qris,cash',
            'account_number' => 'nullable|string|max:100',
            'account_name'   => 'nullable|string|max:255',
            'qr_image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'instructions'   => 'nullable|string',
            'status'         => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('qr_image')) {
            if ($paymentMethod->qr_image && Storage::disk('public')->exists($paymentMethod->qr_image)) {
                Storage::disk('public')->delete($paymentMethod->qr_image);
            }
            $validated['qr_image'] = $request->file('qr_image')->store('payment_qr', 'public');
        }

        $paymentMethod->update($validated);

        return redirect()->route('metode-pembayaran.index')->with('success', 'Metode pembayaran berhasil diperbarui.');
    }

    // Fungsi toggle switch status aktif / nonaktif
    public function toggleStatus(PaymentMethod $paymentMethod)
    {
        $newStatus = ($paymentMethod->status === 'active') ? 'inactive' : 'active';
        $paymentMethod->update(['status' => $newStatus]);

        $statusIndo = ($newStatus === 'active') ? 'aktif' : 'nonaktif';
        return redirect()->route('metode-pembayaran.index')->with('success', "Status metode pembayaran {$paymentMethod->name} berhasil diubah menjadi {$statusIndo}.");
    }

    // Fungsi menghapus metode pembayaran
    public function destroy(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->qr_image && Storage::disk('public')->exists($paymentMethod->qr_image)) {
            Storage::disk('public')->delete($paymentMethod->qr_image);
        }

        $paymentMethod->delete();

        return redirect()->route('metode-pembayaran.index')->with('success', 'Metode pembayaran berhasil dihapus.');
    }
}
