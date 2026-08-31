<div class="modal fade" id="modalDeleteSlot{{ $schedule->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" action="{{ route('pemilik.jadwal.destroy', $schedule->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus Slot</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bx bx-error-circle text-danger display-3 mb-2"></i>
                <h5 class="mb-1">Yakin ingin menghapus slot ini?</h5>
                <p class="text-muted mb-0">
                    Slot jam <strong>{{ $startTimeVal }} - {{ $endTimeVal }}</strong> pada
                    <strong>{{ $schedule->field?->field_name }}</strong> akan dihapus.
                </p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">Ya, Hapus Data</button>
            </div>
        </form>
    </div>
</div>
