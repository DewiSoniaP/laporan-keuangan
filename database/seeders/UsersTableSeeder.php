<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'pmbniningkeuangan@gmail.com',
            'password' => Hash::make('adminkeuangan123'),
            'whatsapp' => '085624844745',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'User Biasa',
            'email' => 'user@example.com',
            'password' => Hash::make('secret123'),
            'whatsapp' => null,
            'role' => 'user',
        ]);
    }
}
