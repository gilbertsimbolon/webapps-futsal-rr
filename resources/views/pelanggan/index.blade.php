@extends('layouts.app')

@section('title', 'Manajemen Pelanggan | bkngftsl.')

@section('content')
    <div class="card">
        <h5 class="card-header">Manajemen Pelanggan</h5>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th style="width: 10px" class="text-center">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th class="text-center">Status Akun</th>
                        <th style="width: 20px" class="text-center">Aksi</th>
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
                                @if ($p->status === 'aktif')
                                    <span class="badge bg-label-success">Aktif</span>
                                @else
                                    <span class="badge bg-label-danger">Nonaktif</span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="text-center">
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>

                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#">
                                            <i class="bx bx-edit-alt me-1"></i>
                                            Edit
                                        </a>

                                        <a class="dropdown-item" href="#">
                                            <i class="bx bx-trash me-1"></i>
                                            Hapus
                                        </a>
                                    </div>
                                </div>
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
        </div>
    </div>
@endsection
