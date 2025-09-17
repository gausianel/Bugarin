<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member_Gym extends Model
{
    //1 to payment
    public function payment()
    {
        return $this->hasMany(Payment::class, 'member_gym_id');
    }
}
