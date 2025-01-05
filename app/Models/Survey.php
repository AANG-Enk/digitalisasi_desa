<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Models\SurveiPertanyaan;
use App\Models\SurveiJawaban;

class Survey extends Model
{
    use HasFactory, Sluggable;
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'judul',
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

    public function pertanyaans()
    {
        return $this->hasMany(SurveiPertanyaan::class, 'survei_id');
    }

    public function jawabans()
    {
        return $this->hasManyThrough(SurveiJawaban::class, SurveiPertanyaan::class, 'survei_id', 'survei_pertanyaan_id', 'id', 'id');
    }

    public function users()
    {
        return $this->jawabans()->with('user')->get()->pluck('user')->unique('id');
    }
}
