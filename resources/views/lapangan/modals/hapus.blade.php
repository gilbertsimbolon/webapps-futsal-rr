@php
    $isAdmin = auth()->user()->hasRole('admin');
    $isPemilik = auth()->user()->hasRole('pemilik');

    // Route berdasarkan role pengguna
    $lapanganDeleteRoute = $isAdmin
        ? 'admin.lapangan.destroy'
        : 'pemilik.lapangan.destroy';
@endphp

<div
    class="modal fade"
    id="modalDeleteField{{ $field->id }}"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">

        <form
            class="modal-content"
            action="{{ route($lapanganDeleteRoute, $field->id) }}"
            method="POST"
        >
            @csrf
            @method('DELETE')

            {{-- Header --}}
            <div class="modal-header">
                <h5 class="modal-title">
                    Konfirmasi Hapus Lapangan
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>

            {{-- Body --}}
            <div class="modal-body text-center py-4">

                <i class="bx bx-error-circle text-danger display-3 mb-2"></i>

                <h5 class="mb-1">
                    Yakin ingin menghapus lapangan ini?
                </h5>

                <p class="text-muted mb-0">
                    Lapangan
                    <strong>{{ $field->field_name }}</strong>
                    di cabang
                    <strong>{{ $field->branch?->branch_name ?? '-' }}</strong>
                    beserta foto dan jadwal terkait akan dihapus secara permanen.
                </p>

                <div class="alert alert-warning mt-3 mb-0 text-start">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bx bx-info-circle mt-1"></i>

                        <div class="small">
                            <strong>Perhatian:</strong>
                            Data yang sudah dihapus tidak dapat dikembalikan.
                        </div>
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="modal-footer justify-content-center">

                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="btn btn-danger"
                >
                    <i class="bx bx-trash me-1"></i>
                    Ya, Hapus Data
                </button>

            </div>

        </form>

    </div>
</div>