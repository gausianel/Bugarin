<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gym_Information extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'gym_information'; // tabel sesuai migrasi

    protected $fillable = [
        'gym_id',
        'title',
        'description', // ✅ ikut migrasi
        'created_by',
        'deleted_by',
    ];
}
