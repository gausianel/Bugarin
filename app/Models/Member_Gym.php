<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member_Gym extends Model
{
    use HasFactory;

    protected $table = 'member_gyms'; // ⚡ pastikan ini
    protected $fillable = [
        'user_id',
        'gym_id',
        'package_id',
        'start_date',
        'end_date',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Membership_Package::class, 'package_id');
    }

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }

    public function payments()
{
    return $this->hasMany(Payment::class, 'member_gym_id');
}

}


