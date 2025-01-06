<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SuratTujuan;
use App\Models\User;

class LayananSurat extends Model
{
    use HasFactory;
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'surat_tujuan_id',
        'user_id',
        'nomor_surat_rt',
        'nomor_surat_rw',
        'rt',
        'rw',
        'nama_rt',
        'nama_rw',
        'tanggal_tanda_tangan',
        'deleted_at',
    ];

    public function tujuan()
    {
        return $this->belongsTo(SuratTujuan::class, 'surat_tujuan_id');
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
