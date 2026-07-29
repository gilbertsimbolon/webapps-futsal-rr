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
            'name' => 'Roy Nainggolan',
            'email' => 'roynainggolan@gmail.com',
            'password' => Hash::make('password'),
            'status' => 'aktif',
        ]);

        $admin->assignRole('admin');
    }
}
