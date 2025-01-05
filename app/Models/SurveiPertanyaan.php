<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Survey;
use App\Models\SurveiJawaban;

class SurveiPertanyaan extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'survei_id',
        'pertanyaan',
        'deleted_at',
    ];

    public function survei()
    {
        return $this->belongsTo(Survey::class, 'survei_id');
    }

    public function jawabans()
    {
        return $this->hasMany(SurveiJawaban::class, 'survei_pertanyaan_id');
    }
}
