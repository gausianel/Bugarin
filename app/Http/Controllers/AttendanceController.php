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
    // ========================
    // ADMIN: List Absensi
    // ========================
    public function index()
    {
        // ambil data absensi dengan relasi user & classSchedule
        $attendances = Attendance::with(['user', 'classSchedule'])
            ->orderByDesc('check_in_time') // ✅ kolom ada di migration
            ->paginate(10);

        return view('admin.attendance.index', compact('attendances'));
    }

    // ========================
    // ADMIN: Generate QR
    // ========================
    public function generateQR($class_id)
    {
        $token = Str::uuid();

        QrToken::create([
            'class_id'   => $class_id,
            'user_id'    => Auth::id(), // ✅ supaya ga null
            'token'      => $token,
            'expires_at' => now()->addMinutes(10),
        ]);

        return response()->json([
            'status'     => 'success',
            'token'      => $token,
            'expires_at' => now()->addMinutes(10)
        ]);
    }

    // ========================
    // MEMBER: Scan QR
    // ========================
    public function scanQR($token)
    {
        $qr = QrToken::where('token', $token)
            ->where('expires_at', '>', now())
            ->first();

        if (!$qr) {
            return response()->json(['status' => 'error', 'message' => 'QR code invalid atau kadaluarsa'], 400);
        }

        return response()->json([
            'status'   => 'valid',
            'class_id' => $qr->class_id
        ]);
    }

    // ========================
    // MEMBER: Submit Check-in
    // ========================
    public function submitCheckIn(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:class_schedules,id',
            'token'    => 'required|string'
        ]);

        $userId = Auth::id();

        // Cegah double check-in di hari yang sama
        $already = Attendance::where('user_id', $userId)
            ->where('class_id', $request->class_id)
            ->whereDate('created_at', now()->toDateString())
            ->exists();

        if ($already) {
            return response()->json(['status' => 'error', 'message' => 'Sudah check-in hari ini'], 409);
        }

        Attendance::create([
            'user_id'       => $userId,
            'class_id'      => $request->class_id,
            'date'          => now()->toDateString(),
            'status'        => 'Hadir',
            'check_in_time' => now()->format('H:i:s'),
            'qr_code'       => $request->token,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Check-in berhasil']);
    }

    // ========================
    // ADMIN: List Kehadiran per Kelas
    // ========================
    public function listAttendanceByClass($class_id)
    {
        $attendances = Attendance::with('user')
            ->where('class_id', $class_id)
            ->orderByDesc('check_in_time') // ✅ benerin kolom
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $attendances
        ]);
    }

    // ========================
    // MEMBER: Riwayat Kehadiran
    // ========================
    public function listMyAttendance()
    {
        $userId = Auth::id();
        $attendances = Attendance::with('classSchedule') // ✅ pake model Class_Schedule
            ->where('user_id', $userId)
            ->orderByDesc('check_in_time')
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $attendances
        ]);
    }

    public function classHistory()
    {
        return $this->listMyAttendance(); // alias
    }

    // ========================
    // MEMBER: Scan + Check-in
    // ========================
    public function scanCheck(Request $request)
    {
        $request->validate([
            'token' => 'required|string'
        ]);

        // Cari QR token valid
        $qr = QrToken::where('token', $request->token)
            ->where('expires_at', '>', now())
            ->first();

        if (!$qr) {
            return response()->json(['message' => 'QR tidak valid atau sudah kadaluarsa'], 400);
        }

        Attendance::create([
            'user_id'       => $qr->user_id,
            'class_id'      => $qr->class_id,
            'date'          => now()->toDateString(),
            'status'        => 'Hadir',
            'check_in_time' => now()->format('H:i:s'),
            'qr_code'       => $qr->token,
        ]);

        return response()->json([
            'message' => '✅ Check-in berhasil untuk user ID ' . $qr->user_id
        ]);
    }
}
