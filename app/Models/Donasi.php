<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Models\User;
use App\Models\BayarDonasi;

class Donasi extends Model
{
    use HasFactory, Sluggable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'judul',
        'slug',
        'deskripsi',
        'target',
        'alamat',
        'berakhir',
        'deleted_at',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'judul'
            ]
        ];
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bayars()
    {
        return $this->hasMany(BayarDonasi::class, 'donasi_id');
    }
}
