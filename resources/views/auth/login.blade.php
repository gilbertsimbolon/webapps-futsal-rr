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
                            <a href="index.html" class="app-brand-link gap-2">
                                <img src="{{ asset('img/logo.png') }}" alt="Logo UNIMA" style="width: 40px; height: 40px;">
                                <span class="app-brand-text demo text-heading fw-bold">bkngftsl</span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-1 mt-3 text-center">Selamat Datang!</h4>
                        <p class="mb-6 text-center">Silahkan login terlebih dahulu.</p>

                        <form id="formAuthentication" class="mb-6" action="index.html">
                            <div class="mb-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    placeholder="Masukkan email anda." autofocus />
                            </div>
                            <div class="mb-1 form-password-toggle">
                                <label class="form-label" for="password">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i
                                            class="icon-base bx bx-hide"></i></span>
                                </div>
                            </div>
                            <div class="mb-1">
                                <div class="d-flex justify-content-end">
                                    <a href="auth-forgot-password-basic.html">
                                        <span>Lupa Password?</span>
                                    </a>
                                </div>
                            </div>
                            <div class="mb-1">
                                <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
                            </div>
                        </form>

                        <p class="text-center">
                            <span>Belum memiliki akun?</span>
                            <a href="auth-register-basic.html">
                                <span>Registrasi</span>
                            </a>
                        </p>
                    </div>
                </div>
                <!-- /Register -->
            </div>
        </div>
    </div>
@endsection
