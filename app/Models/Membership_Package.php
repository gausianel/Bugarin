<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership_Package extends Model
{
    protected $table = 'membership_packages'; // pastikan sama dengan nama tabel di database

    protected $fillable = [
        'gym_id',
        'name',
        'price',
        'duration',
        'description',
    ];

    // 1 package bisa dipakai banyak member
    public function memberGyms()
    {
        return $this->hasMany(Member_Gym::class, 'package_id');
    }

    // 1 package dimiliki oleh 1 gym
    public function gym()
    {
        return $this->belongsTo(Gym::class, 'gym_id');
    }

     public function package()
    {
        return $this->belongsTo(Membership_Package::class, 'package_id');
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    

}
