<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Donasi;
use App\Models\User;

class BayarDonasi extends Model
{
    use HasFactory;
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'donasi_id',
        'nominal',
        'keterangan',
        'pembayaran',
        'is_verified',
        'file',
        'deleted_at',
    ];

    protected $casts = [
        'nominal' => 'decimal:2', // Cast untuk menjaga 2 desimal
    ];

    public function donasi()
    {
        return $this->belongsTo(Donasi::class, 'donasi_id');
    }

    public function bayar()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
