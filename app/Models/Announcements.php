<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcements extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'GymInformationController'; // nama tabel

    /**
     * Kolom yang boleh diisi mass-assignment
     */
    protected $fillable = [
        'title',
        'content',
        'gym_id',
        'created_by',
        'deleted_by',
    ];

    /**
     * Relasi ke Gym (satu announcement untuk satu gym)
     */
    public function gym()
    {
        return $this->belongsTo(Gym::class, 'gym_id');
    }

    /**
     * Relasi ke User (siapa yang bikin announcement)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke User (siapa yang hapus announcement)
     */
    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
