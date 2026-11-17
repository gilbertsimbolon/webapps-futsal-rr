<div class="modal fade" id="modalRejectBooking{{ $booking->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" action="{{ route('pemilik.bookings.reject', $booking->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="modal-header">
                <h5 class="modal-title">Tolak Booking: {{ $booking->booking_code }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="small text-muted mb-2">Anda akan menolak reservasi ini. Status booking akan diubah menjadi batal.</p>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Alasan Penolakan (Opsional)</label>
                    <textarea name="rejection_reason" class="form-control" rows="2" placeholder="Contoh: Bukti transfer tidak valid atau tidak terbaca"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">Tolak Booking</button>
            </div>
        </form>
    </div>
</div>
