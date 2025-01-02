<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $administrator = Role::create([
            'name'  => 'Administrator'
        ]);

        $kelurahan = Role::create([
            'name'  => 'Kelurahan'
        ]);

        $ketua_rw = Role::create([
            'name'  => 'Ketua RW'
        ]);

        $ketua_rt = Role::create([
            'name'  => 'Ketua RT'
        ]);

        $warga = Role::create([
            'name'  => 'Warga'
        ]);

        $list_permission = [
            // User
            'User Access|Administrator',
            'User Create|Administrator',
            'User Update|Administrator',
            'User Delete|Administrator',
            'User Banned|Administrator',
            'User Role|Administrator',

            // Role
            'Role Access|Administrator',
            'Role Create|Administrator',
            'Role Update|Administrator',
            'Role Delete|Administrator',
            'Role Permission|Administrator',

            // Permission
            'Permission Access|Administrator',
            'Permission Create|Administrator',
            'Permission Update|Administrator',
            'Permission Delete|Administrator',

            // Data Warga
            'Data Warga Access|Administrator|Kelurahan|Ketua RW|Ketua RT',
            'Data Warga Create|Administrator|Kelurahan|Ketua RW|Ketua RT',
            'Data Warga Update|Administrator|Kelurahan|Ketua RW|Ketua RT',
            'Data Warga Delete|Administrator|Kelurahan|Ketua RW|Ketua RT',

            // Berita RW Kategori
            'Berita RW Kategori Access|Administrator|Kelurahan|Ketua RW|Ketua RT',
            'Berita RW Kategori Create|Administrator|Kelurahan|Ketua RW|Ketua RT',
            'Berita RW Kategori Update|Administrator|Kelurahan|Ketua RW|Ketua RT',
            'Berita RW Kategori Delete|Administrator|Kelurahan|Ketua RW|Ketua RT',

            // Berita RW
            'Berita RW Access|Administrator|Kelurahan|Ketua RW|Ketua RT|Warga',
            'Berita RW Create|Administrator|Kelurahan|Ketua RW|Ketua RT|Warga',
            'Berita RW Update|Administrator|Kelurahan|Ketua RW|Ketua RT|Warga',
            'Berita RW Delete|Administrator|Kelurahan|Ketua RW|Ketua RT|Warga',

            // Lapor RW
            'Lapor RW Access|Administrator|Kelurahan|Ketua RW|Ketua RT|Warga',
            'Lapor RW Create|Administrator|Kelurahan|Ketua RW|Ketua RT|Warga',
            'Lapor RW Update|Administrator|Kelurahan|Ketua RW|Ketua RT|Warga',
            'Lapor RW Delete|Administrator|Kelurahan|Ketua RW|Ketua RT|Warga',

            // Tanya RW
            'Tanya RW Access|Administrator|Kelurahan|Ketua RW|Ketua RT|Warga',
            'Tanya RW Create|Administrator|Kelurahan|Ketua RW|Ketua RT|Warga',
            'Tanya RW Update|Administrator|Kelurahan|Ketua RW|Ketua RT|Warga',
            'Tanya RW Delete|Administrator|Kelurahan|Ketua RW|Ketua RT|Warga',

            // Survei RW
            'Survei RW Access|Administrator|Kelurahan|Ketua RW|Ketua RT|Warga',
            'Survei RW Create|Administrator|Kelurahan|Ketua RW|Ketua RT|Warga',
            'Survei RW Update|Administrator|Kelurahan|Ketua RW|Ketua RT|Warga',
            'Survei RW Delete|Administrator|Kelurahan|Ketua RW|Ketua RT|Warga',
        ];

        foreach($list_permission as $val){
            $val_array = explode('|',$val);
            $permission = Permission::create([
                'name'  => $val_array[0]
            ]);

            // Role
            if(isset($val_array[1])){
                $role = Role::where('name',$val_array[1])->first();
                $role->givePermissionTo($val_array[0]);
            }

            if(isset($val_array[2])){
                $role = Role::where('name',$val_array[2])->first();
                $role->givePermissionTo($val_array[0]);
            }

            if(isset($val_array[3])){
                $role = Role::where('name',$val_array[3])->first();
                $role->givePermissionTo($val_array[0]);
            }

            if(isset($val_array[4])){
                $role = Role::where('name',$val_array[4])->first();
                $role->givePermissionTo($val_array[0]);
            }

            if(isset($val_array[5])){
                $role = Role::where('name',$val_array[5])->first();
                $role->givePermissionTo($val_array[0]);
            }
        }

    }
}
