@extends('layouts.app')

@section('title', 'Login | bkngftsl')
@php
    $hideLayout = true;
@endphp
@section('content')
    <div class="min-vh-100 container d-flex justify-content-center align-items-center">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register -->
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
                        <h4 class="mb-1 mt-3 text-center fw-bold">Selamat Datang!</h4>
                        <p class="mb-4 text-center text-muted small">Silakan masuk ke akun Anda untuk melanjutkan.</p>

                        @if (isset($returnTo) && $returnTo)
                            <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center mb-4 py-2 px-3 small" role="alert">
                                <i class="bx bx-info-circle fs-4 me-2 text-warning"></i>
                                <div>Silakan masuk terlebih dahulu untuk melakukan booking.</div>
                            </div>
                        @endif

                        <form id="formAuthentication" class="mb-4" action="{{ route('login.store') }}" method="POST">
                            @csrf
                            @if (isset($returnTo) && $returnTo)
                                <input type="hidden" name="return_to" value="{{ $returnTo }}">
                            @endif

                            <div class="mb-3">
                                <label for="email" class="form-label small fw-semibold">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                    value="{{ old('email') }}" placeholder="nama@email.com" required autofocus />
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label small fw-semibold" for="password">Password</label>
                                    <a href="{{ route('lupa-password.index') }}" class="small">Lupa Password?</a>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" required />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100 py-2 fw-semibold" type="submit">Masuk</button>
                            </div>
                        </form>

                        <p class="text-center small mb-0">
                            <span>Belum punya akun?</span>
                            <a href="{{ route('register.index', isset($returnTo) && $returnTo ? ['return_to' => $returnTo] : []) }}" class="fw-semibold">
                                <span>Daftar Sekarang</span>
                            </a>
                        </p>
                    </div>
                </div>
                <!-- /Register -->
            </div>
        </div>
    </div>
@endsection
