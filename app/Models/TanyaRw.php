<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class TanyaRw extends Model
{
    use HasFactory;
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'warga_text',
        'rw_text',
        'deleted_at',
        'is_read'
    ];

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
