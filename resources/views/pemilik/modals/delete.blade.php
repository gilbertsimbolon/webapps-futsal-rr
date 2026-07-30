<!-- Modal Hapus -->
<div class="modal fade" id="hapusPemilik{{ $p->id }}" tabindex="-1" aria-hidden="true" data-bs-dismiss="modal">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('pemilik.delete', $p) }}" method="POST">
            @csrf
            @method('DELETE')

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title text-danger">
                        <i class="bx bx-trash me-1"></i>
                        Hapus Pemilik
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="alert alert-warning mb-4">
                        <i class="bx bx-error-circle me-1"></i>
                        Data pemilik yang dihapus tidak dapat dikembalikan.
                    </div>

                    <h6 class="mb-3">Detail Akun</h6>

                    <table class="table table-sm table-borderless mb-4">
                        <tbody>

                            <tr>
                                <th width="35%">Nama</th>
                                <td>{{ $p->name }}</td>
                            </tr>

                            <tr>
                                <th>Email</th>
                                <td>{{ $p->email }}</td>
                            </tr>

                            <tr>
                                <th>Role</th>
                                <td class="text-capitalize">
                                    {{ $p->getRoleNames()->first() }}
                                </td>
                            </tr>

                            <tr>
                                <th>Status</th>
                                <td>
                                    @if ($p->status == 'aktif')
                                        <span class="badge bg-label-success">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-label-danger">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>Dibuat Pada</th>
                                <td>
                                    {{ $p->created_at->translatedFormat('d F Y, H:i') }}
                                </td>
                            </tr>

                            <tr>
                                <th>Terakhir Diubah</th>
                                <td>
                                    {{ $p->updated_at->translatedFormat('d F Y, H:i') }}
                                </td>
                            </tr>

                        </tbody>
                    </table>

                    <p class="mb-0">
                        Apakah Anda yakin ingin menghapus akun pemilik
                        <strong>{{ $p->name }}</strong>?
                    </p>

                </div>

                <div class="modal-footer">

                    <button type="button"
                        class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit"
                        class="btn btn-danger">
                        <i class="bx bx-trash me-1"></i>
                        Hapus Pemilik
                    </button>

                </div>

            </div>
        </form>
    </div>
</div>
