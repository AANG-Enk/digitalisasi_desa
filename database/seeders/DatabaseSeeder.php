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
        $this->call(PermissionRolesSeeder::class);

        $administrator = User::create([
            'username'          => 'admin.desa',
            'name'              => 'Admin Desa',
            'email'             => 'admin.desa@gmail.com',
            'password'          => Hash::make('#4dm1n.D3s4#'),
            'email_verified_at' => \Carbon\Carbon::now(),
        ]);

        $administrator->assignRole('Administrator');

        $rw = User::create([
            'username'          => 'admin.rw',
            'name'              => 'Admin RW',
            'email'             => 'admin.rw@gmail.com',
            'password'          => Hash::make('#4dm1n.D3s4#'),
            'email_verified_at' => \Carbon\Carbon::now(),
        ]);

        $rw->assignRole('RW');
    }
}
