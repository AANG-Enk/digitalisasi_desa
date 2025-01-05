<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Hash;

class DataWargaImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        for($i = 2; $i < count($rows); $i++){
            $data = $rows[$i];
            $user = User::updateOrCreate([
                'nik'           => $data[2],
                'kk'            => $data[1],
                'username'      => $data[2],
                'email'         => $data[2].'@gmail.com',
            ],[
                'name'          => strtoupper($data[3]),
                'password'      => Hash::make($data[2]),
                'alamat'        => strtoupper($data[8]),
                'rt'            => $data[9],
                'rw'            => $data[10],
                'hp'            => 0,
                'status'        => 'TETAP',
                'tempat_lahir'  => strtoupper($data[4]),
                'tanggal_lahir' => \Carbon\Carbon::createFromFormat('d-m-Y', str_replace('|','-',$data[5]))->format('Y-m-d'),
                'jenis_kelamin' => strtoupper($data[7]),
                'email_verified_at' => \Carbon\Carbon::now(),
                'jenis_pekerjaan'   => $data[12],
                'status_pernikahan' => $data[6],
            ]);
            $user->assignRole('Warga');
        }
    }
}
