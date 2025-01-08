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

        $ketua_rw = Role::create([
            'name'  => 'RW'
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
            'Data Warga Access|Administrator|RW',
            'Data Warga Create|Administrator|RW',
            'Data Warga Update|Administrator|RW',
            'Data Warga Delete|Administrator|RW',

            // Info RW
            'Info RW Access|Administrator|RW',
            'Info RW Create|Administrator|RW',
            'Info RW Update|Administrator|RW',
            'Info RW Delete|Administrator|RW',

            // Layanan Surat
            'Layanan Surat Access|Administrator|RW|Warga',
            'Layanan Surat Create|Administrator|RW|Warga',
            'Layanan Surat Update|Administrator|RW|Warga',
            'Layanan Surat Delete|Administrator|RW|Warga',

            // Berita RW Kategori
            'Berita RW Kategori Access|Administrator|RW',
            'Berita RW Kategori Create|Administrator|RW',
            'Berita RW Kategori Update|Administrator|RW',
            'Berita RW Kategori Delete|Administrator|RW',

            // Berita RW
            'Berita RW Access|Administrator|RW|Warga',
            'Berita RW Create|Administrator|RW',
            'Berita RW Update|Administrator|RW',
            'Berita RW Delete|Administrator|RW',

            // Lapor RW
            'Lapor RW Access|Administrator|RW|Warga',
            'Lapor RW Create|Administrator|RW|Warga',
            'Lapor RW Update|Administrator|RW|Warga',
            'Lapor RW Delete|Administrator|RW|Warga',

            // Tanya RW
            'Tanya RW Access|Administrator|RW|Warga',
            'Tanya RW Create|Administrator|RW|Warga',
            'Tanya RW Update|Administrator|RW|Warga',
            'Tanya RW Delete|Administrator|RW|Warga',

            // Survei RW
            'Survei RW Access|Administrator|RW',
            'Survei RW Create|Administrator|RW',
            'Survei RW Update|Administrator|RW',
            'Survei RW Delete|Administrator|RW',

            'Survei RW Pertanyaan Access|Administrator|RW',
            'Survei RW Pertanyaan Create|Administrator|RW',
            'Survei RW Pertanyaan Update|Administrator|RW',
            'Survei RW Pertanyaan Delete|Administrator|RW',

            'Survei RW Jawaban Access|Administrator|Warga',
            'Survei RW Jawaban Create|Administrator|Warga',

            'Loker RW Access|Administrator|RW|Warga',
            'Loker RW Create|Administrator|RW',
            'Loker RW Update|Administrator|RW',
            'Loker RW Delete|Administrator|RW',

            'Forum RW Access|Administrator|RW|Warga',
            'Forum RW Create|Administrator|RW|Warga',
            'Forum RW Update|Administrator|RW|Warga',
            'Forum RW Delete|Administrator|RW|Warga',

            'Donasi RW Access|Administrator|RW|Warga',
            'Donasi RW Create|Administrator|RW',
            'Donasi RW Update|Administrator|RW',
            'Donasi RW Delete|Administrator|RW',
            'Donasi RW Verifikasi|Administrator|RW',
            'Donasi RW Setor|Administrator|Warga',
            'Donasi RW Bukti|Administrator|Warga',

            'Tani RW Access|Administrator|RW|Warga',
            'Tani RW Create|Administrator|RW',
            'Tani RW Update|Administrator|RW',
            'Tani RW Delete|Administrator|RW',

            'Pasar RW Access|Administrator|RW|Warga',
            'Pasar RW Create|Administrator|RW',
            'Pasar RW Update|Administrator|RW',
            'Pasar RW Delete|Administrator|RW',

            'Iklan RW Access|Administrator|RW',
            'Iklan RW Create|Administrator|RW',
            'Iklan RW Update|Administrator|RW',
            'Iklan RW Delete|Administrator|RW',
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
        }

    }
}
