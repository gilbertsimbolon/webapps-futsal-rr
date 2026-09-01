@extends('layouts.app')

@section('title', 'Registrasi | bkngftsl')

@php
    $hideLayout = true;
@endphp

@section('content')
    <div class="min-vh-100 container d-flex justify-content-center align-items-center">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register Card -->
                <div class="card px-sm-6 px-0">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="{{ route('landing') }}" class="app-brand-link gap-2">
                                <img src="{{ asset('img/logo.png') }}" alt="Logo bkngftsl" style="width: 40px; height: 40px;">
                                <span class="app-brand-text demo text-heading fw-bold fs-4">bkngftsl<span class="text-primary">.</span></span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-1 mt-3 text-center fw-bold">Buat Akun Baru</h4>
                        <p class="mb-4 text-center text-muted small">Daftar sekarang untuk mulai booking lapangan.</p>

                        <form id="formAuthentication" class="mb-4" method="POST" action="{{ route('register.store') }}">
                            @csrf
                            @if (isset($returnTo) && $returnTo)
                                <input type="hidden" name="return_to" value="{{ $returnTo }}">
                            @endif

                            <!-- Kolom Input Nama -->
                            <div class="mb-3">
                                <label for="name" class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                    value="{{ old('name') }}" placeholder="Nama Lengkap / Nama Tim" required autofocus />
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kolom Input Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label small fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                    value="{{ old('email') }}" placeholder="nama@email.com" required />
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kolom Input Nomor HP / WhatsApp -->
                            <div class="mb-3">
                                <label for="phone" class="form-label small fw-semibold">Nomor WhatsApp / HP</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
                                    value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" />
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kolom Input Password -->
                            <div class="mb-3 form-password-toggle">
                                <label class="form-label small fw-semibold" for="password">Password <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password"
                                        placeholder="Min. 8 karakter" aria-describedby="password" required />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Konfirmasi Password -->
                            <div class="mb-4 form-password-toggle">
                                <label class="form-label small fw-semibold" for="password_confirmation">Konfirmasi Password <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password_confirmation" class="form-control" name="password_confirmation"
                                        placeholder="Ulangi password" aria-describedby="password" required />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>

                            <button class="btn btn-primary d-grid w-100 py-2 fw-semibold" type="submit">Daftar Akun</button>
                        </form>

                        <p class="text-center small mb-0">
                            <span>Sudah memiliki akun?</span>
                            <a href="{{ route('login.index', isset($returnTo) && $returnTo ? ['return_to' => $returnTo] : []) }}" class="fw-semibold">
                                <span>Masuk</span>
                            </a>
                        </p>
                    </div>
                </div>
                <!-- Register Card -->
            </div>
        </div>
    </div>
@endsection
