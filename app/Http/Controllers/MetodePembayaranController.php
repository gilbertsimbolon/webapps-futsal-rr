<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MetodePembayaranController extends Controller
{
    /**
     * Menampilkan daftar metode pembayaran milik pemilik yang sedang login.
     */
    public function index(Request $request)
    {
        $query = PaymentMethod::query()
            ->where('user_id', Auth::id());

        // Filter pencarian berdasarkan nama metode,
        // nomor rekening, atau nama pemilik rekening.
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('account_number', 'like', "%{$search}%")
                    ->orWhere('account_name', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan tipe pembayaran.
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $paymentMethods = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'metode_pembayaran.index',
            compact('paymentMethods')
        );
    }

    /**
     * Menambahkan metode pembayaran baru.
     */
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

        // Set pemilik berdasarkan user yang sedang login.
        $validated['user_id'] = Auth::id();

        // Upload gambar QR jika ada.
        if ($request->hasFile('qr_image')) {
            $validated['qr_image'] = $request
                ->file('qr_image')
                ->store('payment_qr', 'public');
        }

        PaymentMethod::create($validated);

        return redirect()
            ->route('pemilik.metode-pembayaran.index')
            ->with(
                'success',
                'Metode pembayaran baru berhasil ditambahkan.'
            );
    }

    /**
     * Memperbarui metode pembayaran milik pemilik yang sedang login.
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        // Pastikan metode pembayaran adalah milik user yang sedang login.
        abort_if(
            $paymentMethod->user_id !== Auth::id(),
            403
        );

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'type'           => 'required|in:bank_transfer,qris,cash',
            'account_number' => 'nullable|string|max:100',
            'account_name'   => 'nullable|string|max:255',
            'qr_image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'instructions'   => 'nullable|string',
            'status'         => 'required|in:active,inactive',
        ]);

        // Jika upload QR baru, hapus QR lama terlebih dahulu.
        if ($request->hasFile('qr_image')) {

            if (
                $paymentMethod->qr_image &&
                Storage::disk('public')->exists($paymentMethod->qr_image)
            ) {
                Storage::disk('public')->delete(
                    $paymentMethod->qr_image
                );
            }

            $validated['qr_image'] = $request
                ->file('qr_image')
                ->store('payment_qr', 'public');
        }

        $paymentMethod->update($validated);

        return redirect()
            ->route('pemilik.metode-pembayaran.index')
            ->with(
                'success',
                'Metode pembayaran berhasil diperbarui.'
            );
    }

    /**
     * Mengubah status metode pembayaran aktif / nonaktif.
     */
    public function toggleStatus(PaymentMethod $paymentMethod)
    {
        // Pastikan metode pembayaran milik user yang sedang login.
        abort_if(
            $paymentMethod->user_id !== Auth::id(),
            403
        );

        $newStatus = $paymentMethod->status === 'active'
            ? 'inactive'
            : 'active';

        $paymentMethod->update([
            'status' => $newStatus,
        ]);

        $statusIndo = $newStatus === 'active'
            ? 'aktif'
            : 'nonaktif';

        return redirect()
            ->route('pemilik.metode-pembayaran.index')
            ->with(
                'success',
                "Status metode pembayaran {$paymentMethod->name} berhasil diubah menjadi {$statusIndo}."
            );
    }

    /**
     * Menghapus metode pembayaran.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        // Pastikan metode pembayaran milik user yang sedang login.
        abort_if(
            $paymentMethod->user_id !== Auth::id(),
            403
        );

        // Hapus file QR jika masih ada.
        if (
            $paymentMethod->qr_image &&
            Storage::disk('public')->exists($paymentMethod->qr_image)
        ) {
            Storage::disk('public')->delete(
                $paymentMethod->qr_image
            );
        }

        $paymentMethod->delete();

        return redirect()
            ->route('pemilik.metode-pembayaran.index')
            ->with(
                'success',
                'Metode pembayaran berhasil dihapus.'
            );
    }
}