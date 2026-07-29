@extends('layouts.app')

@section('title', 'Profil | bkngftsl.')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6 h-100 py-2">
            <div class="col-xl">
                <div class="card">
                    <div
                        class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                        <h5 class="mb-1 mb-md-0">Informasi Akun</h5>

                        <small class="text-body">
                            Detail informasi akun Anda.
                        </small>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profil.update') }}" method="POST">
                            @csrf
                            <div class="mb-6">
                                <label class="form-label" for="basic-icon-default-fullname">Nama Lengkap</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                            class="icon-base bx bx-user"></i></span>
                                    <input type="text" class="form-control" id="basic-icon-default-fullname"
                                        placeholder="{{ old('name', auth()->user()->name) }}"
                                        value="{{ old('name', auth()->user()->name) }}" name="name" />
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="form-label" for="basic-icon-default-company">Role</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-company2" class="input-group-text bg-body-secondary"><i
                                            class="icon-base bx bx-key"></i></span>
                                    <input type="text" id="basic-icon-default-company"
                                        class="form-control text-capitalize bg-body-secondary" placeholder="ACME Inc."
                                        aria-label="ACME Inc." aria-describedby="basic-icon-default-company2"
                                        value='{{ auth()->user()->getRoleNames()->first() }}' disabled />
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="form-label" for="basic-icon-default-email">Email</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="icon-base bx bx-envelope"></i></span>
                                    <input type="text" id="basic-icon-default-email" class="form-control"
                                        placeholder="john.doe" aria-label="john.doe"
                                        aria-describedby="basic-icon-default-email2" value={{ auth()->user()->email }}
                                        name="email" />
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Perbarui</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 h-100 py-2">
            <div class="col-xl">
                <div class="card">
                    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                        <h5 class="mb-1 mb-md-0">Keamanan</h5>

                        <small class="text-body">
                            Detail keamanan akun Anda.
                        </small>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profil.update.password') }}" method="POST">
                            @csrf

                            <div class="mb-6 form-password-toggle">
                                <label class="form-label">Password Lama</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="current_password"
                                        placeholder="Masukkan password lama">

                                    <span class="input-group-text cursor-pointer">
                                        <i class="icon-base bx bx-hide"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="mb-6 form-password-toggle">
                                <label class="form-label">Password Baru</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password"
                                        placeholder="Masukkan password baru">

                                    <span class="input-group-text cursor-pointer">
                                        <i class="icon-base bx bx-hide"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="mb-6 form-password-toggle">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password_confirmation"
                                        placeholder="Masukkan ulang password baru">

                                    <span class="input-group-text cursor-pointer">
                                        <i class="icon-base bx bx-hide"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button class="btn btn-primary" type="submit">
                                    Ganti Password
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
