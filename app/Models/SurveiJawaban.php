<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SurveiJawaban extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'survei_pertanyaan_id',
        'jawaban',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
