<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DataWargaImport implements ToModel, WithChunkReading
{
    // Variabel statis untuk melacak chunk
    private static $chunkCounter = 0;
    private static $rowCounter = 0;

    public function model(array $row)
    {
        // Skip 2 baris pertama (index 0 dan 1)
        static $skipCount = 0;

        if ($skipCount < 2) {
            $skipCount++;
            return null; // Melewati baris pertama dan kedua
        }

        // Hitung jumlah baris yang diproses
        self::$rowCounter++;

        // Cek apakah ini adalah baris pertama dalam chunk baru
        if ((self::$rowCounter - 1) % $this->chunkSize() === 0) {
            self::$chunkCounter++;
            Log::info("Processing chunk #" . self::$chunkCounter);
        }

        Log::info('Processing row in chunk #' . self::$chunkCounter . ': ', $row);

        $user = User::updateOrCreate([
            'nik'           => $row[2],
            'kk'            => $row[1],
            'username'      => $row[2],
            'email'         => $row[2] . '@gmail.com',
        ], [
            'name'          => strtoupper($row[3]),
            'password'      => Hash::make($row[2]),
            'alamat'        => strtoupper($row[8]),
            'rt'            => $row[9],
            'rw'            => $row[10],
            'hp'            => 0,
            'status'        => 'TETAP',
            'tempat_lahir'  => strtoupper($row[4]),
            'tanggal_lahir' => Carbon::createFromFormat('d-m-Y', str_replace('|', '-', $row[5]))->format('Y-m-d'),
            'jenis_kelamin' => strtoupper($row[7]),
            'email_verified_at' => Carbon::now(),
            'jenis_pekerjaan'   => $row[12],
            'status_pernikahan' => $row[6],
        ]);

        if (!$user->hasRole('Warga')) {
            $user->assignRole('Warga');
        }

        return $user;
    }
    // public function collection(Collection $rows)
    // {
    //     $memoryUsage = memory_get_usage(true) / 1024 / 1024; // Dalam MB
    //     Log::info('Processing chunk with ' . $rows->count() . ' rows');
    //     Log::info('Memory usage: ' . $memoryUsage . ' MB');

    //     // Skip header row
    //     foreach ($rows->skip(2) as $data) {
    //         $user = User::updateOrCreate([
    //             'nik'           => $data[2],
    //             'kk'            => $data[1],
    //             'username'      => $data[2],
    //             'email'         => $data[2] . '@gmail.com',
    //         ], [
    //             'name'          => strtoupper($data[3]),
    //             'password'      => Hash::make($data[2]),
    //             'alamat'        => strtoupper($data[8]),
    //             'rt'            => $data[9],
    //             'rw'            => $data[10],
    //             'hp'            => 0,
    //             'status'        => 'TETAP',
    //             'tempat_lahir'  => strtoupper($data[4]),
    //             'tanggal_lahir' => Carbon::createFromFormat('d-m-Y', str_replace('|', '-', $data[5]))->format('Y-m-d'),
    //             'jenis_kelamin' => strtoupper($data[7]),
    //             'email_verified_at' => Carbon::now(),
    //             'jenis_pekerjaan'   => $data[12],
    //             'status_pernikahan' => $data[6],
    //         ]);

    //         $user->assignRole('Warga');
    //     }

    //     // for($i = 2; $i < count($rows); $i++){
    //     //     $data = $rows[$i];
    //     //     $user = User::updateOrCreate([
    //     //         'nik'           => $data[2],
    //     //         'kk'            => $data[1],
    //     //         'username'      => $data[2],
    //     //         'email'         => $data[2].'@gmail.com',
    //     //     ],[
    //     //         'name'          => strtoupper($data[3]),
    //     //         'password'      => Hash::make($data[2]),
    //     //         'alamat'        => strtoupper($data[8]),
    //     //         'rt'            => $data[9],
    //     //         'rw'            => $data[10],
    //     //         'hp'            => 0,
    //     //         'status'        => 'TETAP',
    //     //         'tempat_lahir'  => strtoupper($data[4]),
    //     //         'tanggal_lahir' => \Carbon\Carbon::createFromFormat('d-m-Y', str_replace('|','-',$data[5]))->format('Y-m-d'),
    //     //         'jenis_kelamin' => strtoupper($data[7]),
    //     //         'email_verified_at' => \Carbon\Carbon::now(),
    //     //         'jenis_pekerjaan'   => $data[12],
    //     //         'status_pernikahan' => $data[6],
    //     //     ]);
    //     //     $user->assignRole('Warga');
    //     // }
    // }

    /**
     * Ukuran chunk untuk membaca file.
     *
     * @return int
     */
    public function chunkSize(): int
    {
        return 500; // Baca 500 baris sekaligus
    }

    /**
     * Menambahkan log untuk setiap chunk yang diproses.
     *
     * @param $rows
     */
    public function onChunk($rows)
    {
        // Mencatat informasi setiap kali chunk diproses
        Log::info('Chunk processed with ' . count($rows) . ' rows');
    }
}
