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
use App\Models\Member_Gym;

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

   // Member aktif (pake tabel Member_Gym)
        $activeMembers = Member_Gym::whereHas('user', function($q) use ($gymId) {
                $q->where('gym_id', $gymId)->where('role', 'member');
            })
            ->where('status', 'active')
            ->whereDate('end_date', '>=', now())
            ->count();


    // Member baru bulan ini
    $newMembersThisMonth = User::where('gym_id', $gymId)
        ->where('role', 'member')
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->count();

    // Membership expired
        // Membership expired
   $expiredMemberships = Member_Gym::whereHas('user', function($q) use ($gymId) {
        $q->where('gym_id', $gymId)->where('role', 'member');
    })
    ->where('end_date', '<', now())
    ->count();


    // Pendapatan bulan ini
    $currentMonthRevenue = Member_Gym::whereHas('user', function($q) use ($gymId) {
        $q->where('gym_id', $gymId)->where('role', 'member');
    })
    ->whereMonth('start_date', now()->month)
    ->whereYear('start_date', now()->year)
    ->with('package')
    ->get()
    ->sum(fn($m) => $m->package->price);
    
$previousMonthRevenue = Member_Gym::whereHas('user', function($q) use ($gymId) {
        $q->where('gym_id', $gymId)->where('role', 'member');
    })
    ->whereMonth('start_date', now()->subMonth()->month)
    ->whereYear('start_date', now()->subMonth()->year)
    ->with('package')
    ->get()
    ->sum(fn($m) => $m->package->price);



    // Hitung growth (%)
    $revenueGrowth = $previousMonthRevenue > 0
    ? round((($currentMonthRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100, 2)
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

    // ✅ fallback kalau kosong
    if (empty($weeklyAttendance['labels']) || empty($weeklyAttendance['data'])) {
        $weeklyAttendance = [
            'labels' => [],
            'data'   => []
        ];
    }


    // Recent Activities
   $recentActivities = Attendance::whereHas('user', function($q) use ($gymId) {
        $q->where('gym_id', $gymId)->where('role', 'member');
    })
    ->with('user')
    ->latest()
    ->take(5)
    ->get()
    ->map(function ($a) {
        return [
            'message' => "<b>{$a->user->name}</b> melakukan absensi",
            'time'    => $a->created_at->diffForHumans(),
        ];
    });


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
            $membership = $user->memberships()
                ->with('package')
                ->where('status', 'active')
                ->latest('end_date')
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
        // Cek token aktif user
        $qr = QrToken::where('user_id', $user->id)
            ->where('expires_at', '>=', now())
            ->latest()
            ->first();

        if (!$qr) {
            // Kalau belum ada token aktif → generate baru
            $qr = QrToken::create([
                'user_id'    => $user->id,
                'token'      => Str::random(32), // bikin lebih panjang & unik
                'expires_at' => now()->addMinutes(2),
            ]);
        }

        $token = $qr->token;
                // kirim ke blade untuk generate QR

                // dd($membership->toArray()); // 👈 cek di sini

                return view('member.dashboard', compact(
                    'membership',
                    'classes',
                    'attendances',
                    'announcements',
                    'token' // kirim ke blade untuk generate QR
                ));
    }

    public function index()
{
    // contoh data absensi minggu ini
    $startOfWeek = Carbon::now()->startOfWeek();
    $endOfWeek = Carbon::now()->endOfWeek();

    $weeklyLabels = [];
    $weeklyData = [];

    for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
        $weeklyLabels[] = $date->format('D'); // Sen, Sel, Rabu...
        $weeklyData[] = Attendance::whereDate('date', $date)->count();
    }

    $weeklyAttendance = [
        'labels' => $weeklyLabels,
        'data'   => $weeklyData,
    ];

    return view('admin.dashboard', compact('weeklyAttendance'));
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
