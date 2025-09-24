<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Class_Schedule;
use App\Models\Gym;
use App\Models\Membership;
use App\Models\Gym_Information;
use App\Models\Membership_Package;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\QrToken;

class DashboardController extends Controller
{
    // 🔹 Dashboard untuk ADMIN gym
   public function adminDashboard()
{
    $admin = auth()->user();

    // cari gym yang dibuat sama admin ini
    $gym = Gym::where('created_by', $admin->id)->first();

    // kalau belum punya gym, redirect dulu ke create
    if (! $gym) {
        return redirect()->route('admin.gyms.create')
            ->with('error', 'Silakan buat gym dulu.');
    }

    $gymId = $gym->id;

    // Total member
    $totalMembers = User::where('gym_id', $gymId)
        ->where('role', 'member')
        ->count();

    // Total kelas
    $totalClasses = Class_Schedule::where('gym_id', $gymId)->count();

    // Absensi hari ini
    $todayAttendance = Attendance::whereHas('classSchedule', function ($q) use ($gymId) {
            $q->where('gym_id', $gymId);
        })
        ->whereDate('created_at', now()->toDateString())
        ->count();

    // Member aktif
    $activeMembers = User::where('gym_id', $gymId)
        ->where('role', 'member')
        ->whereHas('memberships', function($q) {
            $q->where('status', true);
        })
        ->count();

    // Member baru bulan ini
    $newMembersThisMonth = User::where('gym_id', $gymId)
        ->where('role', 'member')
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->count();

    // Membership expired
    $expiredMemberships = Membership_Package::whereHas('user', function($q) use ($gymId) {
        $q->where('gym_id', $gymId)
          ->where('role', 'member');
    })
    ->where('end_date', '<', now())
    ->count();

    // Pendapatan bulan ini
    $currentMonthRevenue = Membership_Package::whereHas('user', function($q) use ($gymId) {
        $q->where('gym_id', $gymId)
          ->where('role', 'member');
    })
    ->whereMonth('start_date', now()->month)
    ->whereYear('start_date', now()->year)
    ->sum('price');

    // Pendapatan bulan sebelumnya
    $previousMonthRevenue = Membership_Package::whereHas('user', function($q) use ($gymId) {
        $q->where('gym_id', $gymId)
          ->where('role', 'member');
    })
    ->whereMonth('start_date', now()->subMonth()->month)
    ->whereYear('start_date', now()->subMonth()->year)
    ->sum('price');

    // Hitung growth (%)
    $revenueGrowth = $previousMonthRevenue > 0
        ? (($currentMonthRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100
        : 0;

    // Weekly Attendance chart
    $weeklyLabels = [];
    $weeklyData = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = Carbon::today()->subDays($i);
        $weeklyLabels[] = $date->format('D');
        $weeklyData[] = Attendance::whereHas('user', function($q) use ($gymId) {
            $q->where('gym_id', $gymId)->where('role', 'member');
        })
        ->whereDate('date', $date)
        ->count();
    }
    $weeklyAttendance = [
        'labels' => $weeklyLabels,
        'data'   => $weeklyData
    ];

    // Recent Activities
    $recentActivities = Attendance::whereHas('user', function($q) use ($gymId) {
        $q->where('gym_id', $gymId)->where('role', 'member');
    })
    ->latest()
    ->take(5)
    ->get();

    return view('admin.dashboard', [
        'totalMembers'        => $totalMembers,
        'totalClasses'        => $totalClasses,
        'todayAttendance'     => $todayAttendance,
        'activeMembers'       => $activeMembers,
        'newMembersThisMonth' => $newMembersThisMonth,
        'expiredMemberships'  => $expiredMemberships,
        'monthlyRevenue'      => $currentMonthRevenue,
        'revenueGrowth'       => $revenueGrowth,
        'currentMonthRevenue' => $currentMonthRevenue,
        'previousMonthRevenue'=> $previousMonthRevenue,
        'recentActivities'    => $recentActivities,
        'weeklyAttendance'    => $weeklyAttendance,
        'gym'                 => $gym, // biar bisa ditampilin di blade
    ]);
}


    // 🔹 Dashboard untuk MEMBER
   // 🔹 Dashboard untuk MEMBER
    public function memberDashboard()
    {
        $user = auth()->user();

        // ambil membership aktif user
        $membership = Membership_Package::with('package')
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        // ambil max 3 jadwal kelas yang diikuti user
        $classes = $user->enrolledClasses()
            ->orderBy('day')
            ->orderBy('time')
            ->take(3)
            ->get();

        // ambil max 5 riwayat check-in terbaru
        $attendances = Attendance::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // ambil 3 info/pengumuman gym terbaru
        $announcements = Gym_Information::latest()
            ->take(3)
            ->get();

        // 🔹 Generate QR Token untuk member
        // Cek dulu apakah user udah punya token aktif, update atau buat baru
        $qr = QrToken::updateOrCreate(
            ['user_id' => $user->id], // cek berdasarkan user_id
            [
                'token' => Str::random(8), // generate token baru
                'expires_at' => now()->addMinutes(2), // berlaku 2 menit
            ]
        );

        $token = $qr->token; // kirim ke blade untuk generate QR

        return view('member.dashboard', compact(
            'membership',
            'classes',
            'attendances',
            'announcements',
            'token' // kirim ke blade untuk generate QR
        ));
    }



    // 🔹 Dashboard untuk SUPERADMIN
    public function superadminDashboard()
    {
        $totalGyms       = Gym::count();
        $totalUsers      = User::count();
        $totalClasses    = Class_Schedule::count();
        $totalAttendance = Attendance::count();

        return view('superadmin.dashboard', [
            'totalGyms'       => $totalGyms,
            'totalUsers'      => $totalUsers,
            'totalClasses'    => $totalClasses,
            'totalAttendance' => $totalAttendance,
        ]);
    }

    
}
