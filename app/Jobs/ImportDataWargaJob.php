<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

use App\Models\User;
use App\Models\ExcelImportProgres;

class ImportDataWargaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $rows;

    /**
     * Create a new job instance.
     */
    public function __construct($rows)
    {
        $this->rows = $rows;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $user = User::updateOrCreate([
            'nik'           => $this->rows[2],
            'kk'            => $this->rows[1],
            'username'      => $this->rows[2],
            'email'         => $this->rows[2] . '@gmail.com',
        ], [
            'name'          => strtoupper($this->rows[3]),
            'password'      => Hash::make($this->rows[2]),
            'alamat'        => strtoupper($this->rows[8]),
            'rt'            => $this->rows[9],
            'rw'            => $this->rows[10],
            'hp'            => 0,
            'status'        => 'TETAP',
            'tempat_lahir'  => strtoupper($this->rows[4]),
            'tanggal_lahir' => \Carbon\Carbon::createFromFormat('d-m-Y', str_replace('|', '-', $this->rows[5]))->format('Y-m-d'),
            'jenis_kelamin' => strtoupper($this->rows[7]),
            'email_verified_at' => \Carbon\Carbon::now(),
            'jenis_pekerjaan'   => $this->rows[12],
            'status_pernikahan' => $this->rows[6],
        ]);

        if (!$user->hasRole('Warga')) {
            $user->assignRole('Warga');
        }

        ExcelImportProgres::where('key','DATA WARGA')->increment('processed_rows');
    }

    public function failed(\Exception $exception)
    {
        // Update job status to failed
        Cache::put("import_status_{$this->jobId}", ['status' => 'failed', 'progress' => 0]);
    }
}
