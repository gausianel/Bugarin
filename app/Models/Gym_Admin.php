<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gym_Admin extends Model
{
    protected $table = 'gym_admins'; 

    protected $fillable = [
        'gym_id',
        'user_id',
        'role_in_gym',
        'assigned_at',
    ];

    // Relasi ke gym
    public function gym()
    {
        return $this->belongsTo(Gym::class, 'gym_id');
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
