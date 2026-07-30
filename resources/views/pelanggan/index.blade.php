@extends('layouts.app')

@section('title', 'Manajemen Pelanggan | bkngftsl.')

@section('content')
    <div class="card">
        <div class="d-flex justify-content-between">
            <h5 class="card-header">Manajemen Pelanggan</h5>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th style="width: 10px" class="text-center">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th class="text-center">Status Akun</th>
                        <th style="width: 50px" class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="table-border-bottom-0">
                    @forelse ($pelanggan as $p)
                        <tr>
                            <!-- Nomor -->
                            <td class="text-center">
                                {{ $loop->iteration }}
                            </td>

                            <!-- Nama -->
                            <td>{{ $p->name }}</td>

                            <!-- Email -->
                            <td>{{ $p->email }}</td>

                            <!-- Status -->
                            <td class="text-center">
                                <form action="{{ route('pelanggan.status', $p) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <div class="form-check form-switch d-inline-flex align-items-center">
                                        <input class="form-check-input me-2" type="checkbox" name="status"
                                            onchange="this.form.submit()" {{ $p->status === 'aktif' ? 'checked' : '' }}>

                                        <span class="{{ $p->status === 'aktif' ? 'text-success' : 'text-danger' }}">
                                            {{ ucfirst($p->status) }}
                                        </span>
                                    </div>
                                </form>
                            </td>

                            <!-- Aksi -->
                            <td class="text-center">
                                <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal"
                                    data-bs-target="#hapusPelanggan{{ $p->id }}">
                                    <i class="bx bx-trash me-1"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                Belum ada data pelanggan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @include('pelanggan.modals.delete')
        </div>
    </div>
@endsection
