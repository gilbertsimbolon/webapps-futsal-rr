<div class="modal fade" id="modalStatusBooking{{ $booking->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" action="{{ route('pemilik.bookings.update-status', $booking->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="modal-header">
                <h5 class="modal-title">Ubah Status: {{ $booking->booking_code }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Pilih Status Baru <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending (Menunggu)
                        </option>
                        <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed (DP
                            50%)</option>
                        <option value="paid" {{ $booking->status == 'paid' ? 'selected' : '' }}>Paid (Lunas 100%)
                        </option>
                        <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed
                            (Selesai Main)</option>
                        <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled
                            (Batal / Hangus)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Status</button>
            </div>
        </form>
    </div>
</div>
