<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gym_Information extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'gym_informations'; // pastikan sesuai nama tabel di DB

    protected $fillable = [
        'gym_id',
        'title',
        'content',   // penting: ini yang dipakai buat isi content
        'published_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['published_at'];

    // Relasi ke Gym
    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }

    // Relasi ke User (opsional kalau ada)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
