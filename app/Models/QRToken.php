<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Builder\Class_;

class QrToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'class_id',
        'token',
        'expires_at',
    ];

    protected $dates = ['expired_at'];

    public function class_schedule()
    {
        return $this->belongsTo(Class_Schedule::class, 'class_id');
    }
}
