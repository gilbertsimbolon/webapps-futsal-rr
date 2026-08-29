<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Bator',
            'email' => 'batordev@gmail.com',
            'password' => Hash::make('batordev'),
            'status' => 'aktif',
        ]);

        $admin->assignRole('admin');
    }
}
