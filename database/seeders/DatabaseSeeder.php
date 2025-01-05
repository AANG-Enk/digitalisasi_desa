<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\RW;
use App\Models\RT;
use App\Models\Surat;
use App\Models\SuratTujuan;

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

        $rw_satu = RW::create([
            'nama'  => '1'
        ]);

        RT::create([
            'rw_id' => $rw_satu->id,
            'nama'  => '1'
        ]);
        RT::create([
            'rw_id' => $rw_satu->id,
            'nama'  => '2'
        ]);
        RT::create([
            'rw_id' => $rw_satu->id,
            'nama'  => '3'
        ]);
        RT::create([
            'rw_id' => $rw_satu->id,
            'nama'  => '4'
        ]);
        RT::create([
            'rw_id' => $rw_satu->id,
            'nama'  => '5'
        ]);
        RT::create([
            'rw_id' => $rw_satu->id,
            'nama'  => '6'
        ]);
        RT::create([
            'rw_id' => $rw_satu->id,
            'nama'  => '7'
        ]);

        $surat_pengantar = Surat::create([
            'nama'  => 'Surat Pengantar',
        ]);

        SuratTujuan::create([
            'surat_id'  => $surat_pengantar->id,
            'nama'      => 'Surat Keterangan Akte Kelahiran'
        ]);

        SuratTujuan::create([
            'surat_id'  => $surat_pengantar->id,
            'nama'      => 'Surat Keterangan Ahli Waris'
        ]);

        SuratTujuan::create([
            'surat_id'  => $surat_pengantar->id,
            'nama'      => 'Surat Keterangan Catatan Kepolisian'
        ]);

        SuratTujuan::create([
            'surat_id'  => $surat_pengantar->id,
            'nama'      => 'Surat Keterangan Nikah (NA)'
        ]);

        SuratTujuan::create([
            'surat_id'  => $surat_pengantar->id,
            'nama'      => 'Surat Keterangan Usaha (SKU) (NIB)'
        ]);

        SuratTujuan::create([
            'surat_id'  => $surat_pengantar->id,
            'nama'      => 'Surat Keterangan Usaha Non Warga'
        ]);

        SuratTujuan::create([
            'surat_id'  => $surat_pengantar->id,
            'nama'      => 'Surat Keterangan Kehilangan'
        ]);

        SuratTujuan::create([
            'surat_id'  => $surat_pengantar->id,
            'nama'      => 'Surat Keterangan Kematian'
        ]);

        SuratTujuan::create([
            'surat_id'  => $surat_pengantar->id,
            'nama'      => 'Surat Keterangan Pindah'
        ]);

        SuratTujuan::create([
            'surat_id'  => $surat_pengantar->id,
            'nama'      => 'Surat Keterangan Pembuatan KTP'
        ]);

        $surat_rekomendasi = Surat::create([
            'nama'  => 'Surat Rekomendasi',
        ]);

        SuratTujuan::create([
            'surat_id'  => $surat_rekomendasi->id,
            'nama'      => 'Surat Izin Keramaian'
        ]);

        $surat_lainnya = Surat::create([
            'nama'  => 'Surat Lainnya',
        ]);

        SuratTujuan::create([
            'surat_id'  => $surat_lainnya->id,
            'nama'      => 'Surat Kuasa'
        ]);

        SuratTujuan::create([
            'surat_id'  => $surat_lainnya->id,
            'nama'      => 'Surat Tugas Atau Perintah'
        ]);

        SuratTujuan::create([
            'surat_id'  => $surat_lainnya->id,
            'nama'      => 'Surat Izin'
        ]);
    }
}
