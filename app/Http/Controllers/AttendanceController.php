<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\QrToken;
use App\Models\Class_Schedule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // ADMIN: Generate QR code untuk suatu kelas

    public function index()
    {
        // ambil data absensi dengan relasi user & class
        $attendances = Attendance::with(['user', 'class'])
            ->orderByDesc('checked_in_at')
            ->paginate(10); // pake paginate biar bisa pake ->links()

        return view('admin.attendance.index', compact('attendances'));
    }

    public function generateQR($class_id)
    {
        $token = Str::uuid();
        
        QrToken::create([
            'class_id' => $class_id,
            'token' => $token,
            'expired_at' => Carbon::now()->addMinutes(10), // QR berlaku 10 menit
        ]);

        return response()->json([
            'status' => 'success',
            'token' => $token,
            'expires_at' => Carbon::now()->addMinutes(10)
        ]);
    }

    // MEMBER: Scan QR dan dapatkan info kelas/token valid
    public function scanQR($token)
    {
        $qr = QrToken::where('token', $token)
            ->where('expired_at', '>', Carbon::now())
            ->first();

        if (!$qr) {
            return response()->json(['status' => 'error', 'message' => 'QR code invalid atau kadaluarsa'], 400);
        }

        return response()->json([
            'status' => 'valid',
            'class_id' => $qr->class_id
        ]);
    }

    // MEMBER: Submit kehadiran setelah scan QR
   public function submitCheckIn(Request $request)
{
    $request->validate([
        'class_id' => 'required|exists:class_schedules,id', // Ganti cl_id jadi class_id agar konsisten
    ]);

    $userId = Auth::id();

    // Cegah double check-in
    $already = Attendance::where('user_id', $userId)
        ->where('class_id', $request->class_id)
        ->whereDate('created_at', Carbon::now()->toDateString())
        ->exists();

    if ($already) {
        return response()->json(['status' => 'error', 'message' => 'Sudah check-in hari ini'], 409); // typo: statusr -> status
    }

    Attendance::create([
        'user_id' => $userId,
        'class_id' => $request->class_id,
        'checked_in_at' => now(),
    ]);

    return response()->json(['status' => 'success', 'message' => 'Check-in berhasil']);
}


    // ADMIN: Lihat kehadiran per kelas
    public function listAttendanceByClass($class_id)
    {
        $attendances = Attendance::with('user')
            ->where('class_id', $class_id)
            ->orderByDesc('checked_in_at')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $attendances
        ]);
    }

    // MEMBER: Lihat riwayat kehadiran sendiri
    public function listMyAttendance()
    {
        $userId = Auth::id();
        $attendances = Attendance::with('class')
            ->where('user_id', $userId)
            ->orderByDesc('checked_in_at')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $attendances
        ]);
    }

    public function classHistory()
{
    return $this->listMyAttendance(); // alias aja
}

}
