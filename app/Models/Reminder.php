<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PhpParser\Builder\Class_;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'class_id',
        'remind_at',
        'is_sent',
    ];

    protected $casts = [
        'remind_at' => 'datetime',
        'is_sent' => 'boolean',
    ];

    // 🔗 Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔗 Relasi ke Classroom
   

    public function classSchedule()
{
    return $this->belongsTo(Class_Schedule::class, 'class_id');
}

}
