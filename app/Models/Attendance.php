<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'class_id',
        'member_id',
        'date',
        'status',
        'check_in_time',
        'check_out_time',
        'qr_code',
        'created_by',
        'deleted_by'
        
    ];

    // Relasi ke Class_Schedule
    public function classSchedule()
    {
        return $this->belongsTo(Class_Schedule::class, 'class_id');
    }

    // Relasi ke Member
    public function member()
    {
        return $this->belongsTo(Member_Gym::class, 'user_id');
    }

    // Relasi ke User yang membuat
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke User yang menghapus
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    // Relasi ke user/member
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // pastikan kolom foreign key sesuai
    }

    // Relasi ke kelas
    


}
