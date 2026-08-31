<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    // Membuat permission
    public function run(): void
    {
        $permissions = [

            // Dashboard
            'dashboard.lihat',

            // Profil
            'profil.lihat',
            'profil.ubah',
            'profil.ganti-password',

            // Pelanggan
            'pelanggan.lihat',
            'pelanggan.tambah',
            'pelanggan.ubah',
            'pelanggan.hapus',
            'pelanggan.ubah-role',
            'pelanggan.ubah-status',

            // Pemilik
            'pemilik.lihat',
            'pemilik.tambah',
            'pemilik.ubah',
            'pemilik.hapus',
            'pemilik.ubah-role',
            'pemilik.ubah-status',

            // Cabang
            'cabang.lihat',
            'cabang.tambah',
            'cabang.ubah',
            'cabang.hapus',

            // Lapangan
            'lapangan.lihat',
            'lapangan.tambah',
            'lapangan.ubah',
            'lapangan.hapus',

            // Jadwal
            'jadwal.lihat',
            'jadwal.tambah',
            'jadwal.ubah',
            'jadwal.hapus',

            // Metode Pembayaran
            'metode-pembayaran.lihat',
            'metode-pembayaran.tambah',
            'metode-pembayaran.ubah',
            'metode-pembayaran.hapus',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
    }
}