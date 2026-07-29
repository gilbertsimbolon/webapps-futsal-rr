@extends('layouts.app')

@section('title', 'Manajemen Pengguna | bkngftsl.')

@section('content')
    <div class="card">
        <h5 class="card-header">Manajemen Pengguna</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th style="width: 10px" class="text-center">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. HP/WhatsApp</th>
                        <th>Jenis Kelamin</th>
                        <th>Alamat</th>
                        <th>Status</th>
                        <th style="width: 20px" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <tr>
                        <!-- Nomor -->
                        <td class="text-center" style="width: 10px">
                            1
                        </td>

                        <!-- Nama Pengguna -->
                        <td>Albert Cook</td>

                        <!-- Email -->
                        <td>
                            albertcook@gmail.com
                        </td>

                        <!-- No. HP/WhatsApp -->
                        <td>
                            6285399681237
                        </td>

                        <!-- Jenis Kelamin -->
                        <td>
                            <span class="badge bg-label-primary me-1">Laki-Laki</span>
                        </td>

                        <!-- Alamat -->
                        <td>
                            Lorong Bengkel, Tataaran II
                        </td>

                        <!-- Status Akun -->
                        <td>
                            <span class="badge bg-label-primary me-1">Active</span>
                        </td>

                        <!-- Aksi -->
                        <td class="text-center">
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void(0);"><i
                                            class="icon-base bx bx-edit-alt me-1"></i> Edit</a>
                                    <a class="dropdown-item" href="javascript:void(0);"><i
                                            class="icon-base bx bx-trash me-1"></i> Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
