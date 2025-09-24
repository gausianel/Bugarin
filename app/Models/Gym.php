<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gym extends Model
{

    protected $fillable = [
        'name',
        'address',
        'phone',
        'description',
        'image',
        'user_id',
        'created_by', // ⬅️ ini yang bener

    ];
    
    //1 to many packages
    public function packages()
    {
        return $this->hasMany(Membership_Package::class, 'gym_id');
    }

    //1 to many infos
    public function infos()
    {
        return $this->hasMany(Gym_Information::class, 'gym_id');
    }

    //1 to many class
    public function classes()
    {
        return $this->hasMany(Class_Schedule::class, 'gym_id');
    }
}
