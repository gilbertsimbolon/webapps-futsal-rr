<div class="modal fade" id="modalDeleteBooking{{ $booking->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" action="{{ route('pemilik.bookings.destroy', $booking->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bx bx-error-circle text-danger display-3 mb-2"></i>
                <h5 class="mb-1">Yakin ingin menghapus data booking ini?</h5>
                <p class="text-muted mb-0">
                    Kode Booking <strong>{{ $booking->booking_code }}</strong> pemesan
                    <strong>{{ $booking->user?->name ?? 'Tamu' }}</strong> akan dihapus permanen.
                </p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">Ya, Hapus Data</button>
            </div>
        </form>
    </div>
</div>
