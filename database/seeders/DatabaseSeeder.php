<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $superadmin = User::create([
            'username'          => 'admin.desa',
            'name'              => 'Admin Desa',
            'email'             => 'admin.desa@gmail.com',
            'password'          => Hash::make('#4dm1n.D3s4#'),
            'email_verified_at' => \Carbon\Carbon::now(),
        ]);
    }
}
