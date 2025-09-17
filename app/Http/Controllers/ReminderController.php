<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reminder;
use App\Models\User;
use App\Models\Class_Schedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class ReminderController extends Controller
{
    // 🔹 Buat jadwal reminder (bisa dipanggil saat user booking atau admin set kelas)
    public function scheduleReminder(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'class_id' => 'required|exists:class,id',
            'remind_at' => 'required|date',
        ]);

        $reminder = Reminder::create([
            'user_id' => $request->user_id,
            'class_id' => $request->class_id,
            'remind_at' => Carbon::parse($request->remind_at),
            'is_sent' => false,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Reminder berhasil dijadwalkan.',
            'data' => $reminder,
        ]);
    }

    // 🔹 Kirim pengingat ke user (dipanggil oleh cronjob/scheduler Laravel)
    public function sendReminder()
    {
        $now = Carbon::now();

        $reminders = Reminder::where('is_sent', false)
            ->where('remind_at', '<=', $now)
            ->with(['user', 'class'])
            ->get();

        foreach ($reminders as $reminder) {
            // Kirim notifikasi ke user (bisa disesuaikan jadi email / notifikasi internal)
            $user = $reminder->user;
            $class = $reminder->class;

            // Kamu bisa ganti ini pakai Notification::route()->notify(...) kalau pakai sistem notifikasi Laravel
            // Contoh dummy:
            Log::info("Reminder dikirim ke {$user->email} untuk kelas {$class->name}");

            $reminder->is_sent = true;
            $reminder->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Semua reminder yang waktunya sudah dikirim.',
            'count' => count($reminders)
        ]);
    }

    // 🔹 User update preferensi (misalnya aktif/nonaktif pengingat)
    public function updateReminderPreference(Request $request)
    {
        $request->validate([
            'reminder_enabled' => 'required|boolean',
        ]);

        $user = auth()->user;
        $user->reminder_enabled = $request->reminder_enabled;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Preferensi reminder diperbarui.',
            'reminder_enabled' => $user->reminder_enabled,
        ]);
    }
}
