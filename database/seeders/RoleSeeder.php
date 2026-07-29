<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = [
            'admin',
            'pemilik',
            'pelanggan',
        ];

        foreach ($role as $r) {
            Role::firstOrCreate([
                'name' => $r,
                'guard_name' => 'web',
            ]);
        }
    }
}
