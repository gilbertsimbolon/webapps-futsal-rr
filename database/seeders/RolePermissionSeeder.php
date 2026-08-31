<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    // Mengatur permission setiap role
    public function run(): void
    {
        // Bersihkan cache permission
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Ambil semua permission
        $permissions = Permission::all();

        // Admin mendapatkan semua permission
        $admin = Role::findByName('admin');
        $admin->syncPermissions($permissions);

        // Pemilik
        $pemilik = Role::findByName('pemilik');

        $pemilik->syncPermissions([
            'dashboard.lihat',

            'profil.lihat',
            'profil.ubah',
            'profil.ganti-password',

            'cabang.lihat',
            'cabang.tambah',
            'cabang.ubah',
            'cabang.hapus',

            'lapangan.lihat',
            'lapangan.tambah',
            'lapangan.ubah',
            'lapangan.hapus',

            'jadwal.lihat',
            'jadwal.tambah',
            'jadwal.ubah',
            'jadwal.hapus',

            'metode-pembayaran.lihat',
            'metode-pembayaran.tambah',
            'metode-pembayaran.ubah',
            'metode-pembayaran.hapus',

            'pelanggan.lihat',
        ]);

        // Pelanggan
        $pelanggan = Role::findByName('pelanggan');

        $pelanggan->syncPermissions([
            'dashboard.lihat',

            'profil.lihat',
            'profil.ubah',
            'profil.ganti-password',
        ]);

        // Bersihkan cache permission setelah selesai
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}