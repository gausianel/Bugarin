<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Field yang bisa diisi mass assignment
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', 
        'gym_id', // User terhubung ke gym tertentu
    ];

    /**
     * Field yang disembunyikan ketika serialisasi
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // 🔹 Relasi ke Profile
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    // 🔹 Relasi: User → Gym (satu admin = satu gym)
    public function gym()
    {
        return $this->belongsTo(Gym::class, 'gym_id');
    }

    // 🔹 Relasi: satu user memiliki banyak GymAdmin
    public function gymAdmins()
    {
        return $this->hasMany(Gym_Admin::class, 'user_id');
    }

    // 🔹 Relasi: satu user memiliki banyak Attendance
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'user_id');
    }

    // 🔹 Relasi: satu user memiliki banyak MemberGym
    public function memberGyms()
    {
        return $this->hasMany(Member_Gym::class, 'member_id');
    }

    // 🔹 Relasi: user bisa daftar banyak kelas (via Attendance)
    public function enrolledClasses()
    {
        return $this->belongsToMany(Class_Schedule::class, 'attendances', 'user_id', 'class_id');
    }

    // 🔹 Relasi: user bisa jadi pengajar kelas
    public function teachingClasses()
    {
        return $this->hasMany(Class_Schedule::class, 'instructor_id');
    }

    // 🔹 Gabungan dua relasi kelas di atas
    public function getClassesAttribute()
    {
        $enrolled = $this->relationLoaded('enrolledClasses')
            ? $this->enrolledClasses
            : $this->enrolledClasses()->get();

        $teaching = $this->relationLoaded('teachingClasses')
            ? $this->teachingClasses
            : $this->teachingClasses()->get();

        return $enrolled->merge($teaching);
    }

    // 🔹 Relasi: user punya banyak membership
    public function memberships()
    {
        return $this->hasMany(Member_Gym::class, 'user_id');
    }

    public function gymAdmin()
    {
        return $this->hasOne(\App\Models\Gym_Admin::class, 'user_id');
    }

    // App\Models\Member_Gym.php
public function package()
{
    return $this->belongsTo(Membership_Package::class, 'package_id');
}


     


}
