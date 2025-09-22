<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Class_Schedule extends Model
{
    use SoftDeletes;

    protected $table = 'class_schedules';

    protected $fillable = [
        'gym_id',
        'class_name',
        'instructor_name',
        'day',
        'time',
        'quota',
        'created_by',
        'deleted_by',
    ];

    public function gym()
    {
        return $this->belongsTo(Gym::class, 'gym_id');
    }
}
