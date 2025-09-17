<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gym_Admin extends Model
{
    
    //1 to many gym
   public function gym()
{
    return $this->belongsTo(Gym::class, 'gym_id');
}

}
