<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Class_Schedule extends Model
{
    protected $fillable = [
        'gym_id',
        'instructor_id',
        'class_name',
        'description',
        'day_of_week',
        'start_time',
        'end_time',
        'capacity',
    ];
    //1 to many attendances
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'class_id');
    }
}
