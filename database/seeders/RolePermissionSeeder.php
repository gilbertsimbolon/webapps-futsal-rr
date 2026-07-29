<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Admin
        $admin = Role::findByName('admin');
        $admin->syncPermissions(Permission::all());

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
        ]);

        // Pengguna
        $pelanggan = Role::findByName('pelanggan');

        $pelanggan->syncPermissions([
            'dashboard.lihat',

            'profil.lihat',
            'profil.ubah',
            'profil.ganti-password',
        ]);
    }
}