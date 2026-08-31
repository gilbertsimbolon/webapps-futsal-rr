<div class="modal fade" id="modalCreateSlot" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" action="{{ route('pemilik.jadwal.store') }}" method="POST">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Slot Jam Operasional</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Pilih Lapangan <span class="text-danger">*</span></label>
                    <select name="field_id" class="form-select" required>
                        <option value="">-- Pilih Lapangan --</option>
                        @foreach ($fields as $field)
                            <option value="{{ $field->id }}" {{ old('field_id') == $field->id ? 'selected' : '' }}>
                                {{ $field->branch?->branch_name }} - {{ $field->field_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Hari Berlaku <span class="text-danger">*</span></label>
                    <select name="day" class="form-select" required>
                        <option value="all" {{ old('day', 'all') == 'all' ? 'selected' : '' }}>Setiap Hari (Senin - Minggu)</option>
                        <option value="monday" {{ old('day') == 'monday' ? 'selected' : '' }}>Senin</option>
                        <option value="tuesday" {{ old('day') == 'tuesday' ? 'selected' : '' }}>Selasa</option>
                        <option value="wednesday" {{ old('day') == 'wednesday' ? 'selected' : '' }}>Rabu</option>
                        <option value="thursday" {{ old('day') == 'thursday' ? 'selected' : '' }}>Kamis</option>
                        <option value="friday" {{ old('day') == 'friday' ? 'selected' : '' }}>Jumat</option>
                        <option value="saturday" {{ old('day') == 'saturday' ? 'selected' : '' }}>Sabtu</option>
                        <option value="sunday" {{ old('day') == 'sunday' ? 'selected' : '' }}>Minggu</option>
                    </select>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                        <input type="text" name="start_time" class="form-control" placeholder="08:00" value="{{ old('start_time') }}" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                        <input type="text" name="end_time" class="form-control" placeholder="09:00" value="{{ old('end_time') }}" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Harga Khusus Slot (Opsional)</label>
                    <input type="number" name="custom_price" class="form-control" placeholder="Kosongkan jika mengikuti harga default lapangan" value="{{ old('custom_price') }}" min="0">
                </div>
                <div class="mb-3">
                    <label class="form-label">Status Slot <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif (Bisa Disewa)</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Slot</button>
            </div>
        </form>
    </div>
</div>
