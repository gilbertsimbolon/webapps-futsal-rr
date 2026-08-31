<div class="modal fade" id="modalEditUser{{ $user->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" action="{{ route('admin.pengguna.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Pengguna: {{ $user->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}"
                        required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}"
                        required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role Akses <span class="text-danger">*</span></label>
                    <select name="role" class="form-select" required>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}"
                                {{ old('role', $userRole) == $role->name ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password Baru <small class="text-muted">(Kosongkan jika tidak ingin
                            diubah)</small></label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
