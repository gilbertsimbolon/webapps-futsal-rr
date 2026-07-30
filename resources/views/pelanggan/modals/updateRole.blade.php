<!-- Modal Ubah Role -->
<div class="modal fade" id="ubahRoleModal{{ $p->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Ubah Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pelanggan.update-role', $p->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="bx bx-user-check text-primary display-3"></i>
                    </div>
                    <p class="text-center">
                        Apakah Anda yakin ingin mengubah role <strong>{{ $p->name }}</strong> menjadi <strong>Pemilik</strong>?
                    </p>

                    <!-- Value 'pemilik' harus persis sama dengan nama role di tabel roles Spatie -->
                    <input type="hidden" name="role" value="pemilik">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ya, Ubah Role</button>
                </div>
            </form>
        </div>
    </div>
</div>
