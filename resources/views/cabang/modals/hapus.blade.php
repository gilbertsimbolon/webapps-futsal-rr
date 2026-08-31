<div class="modal fade"
    id="modalDeleteBranch{{ $branch->id }}"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <form class="modal-content"
            action="{{ route(auth()->user()->hasRole('admin') ? 'admin.cabang.destroy' : 'pemilik.cabang.destroy', $branch->id) }}"
            method="POST">

            @csrf
            @method('DELETE')

            <div class="modal-header">

                <h5 class="modal-title">
                    Konfirmasi Hapus Cabang
                </h5>

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close">
                </button>

            </div>

            <div class="modal-body text-center py-4">

                <div class="avatar avatar-lg bg-label-danger rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">

                    <i class="bx bx-trash fs-2 text-danger"></i>

                </div>

                <h5 class="mb-2">
                    Yakin ingin menghapus cabang ini?
                </h5>

                <p class="text-muted mb-0">

                    Cabang

                    <strong>
                        {{ $branch->branch_name }}
                    </strong>

                    beserta data terkait di dalamnya akan dihapus secara permanen.

                </p>

            </div>

            <div class="modal-footer justify-content-center">

                <button type="button"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal">

                    Batal

                </button>

                <button type="submit"
                    class="btn btn-danger">

                    <i class="bx bx-trash me-1"></i>
                    Ya, Hapus Data

                </button>

            </div>

        </form>

    </div>

</div>