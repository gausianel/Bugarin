<?php

namespace App\Http\Controllers;

use App\Models\Class_Schedule;
use App\Models\Gym;
use Illuminate\Http\Request;

class ClassScheduleController extends Controller
{
    // ✅ Tampilkan daftar kelas
    public function index()
    {
        $schedules = Class_Schedule::with('gym')->paginate(10); // pake paginate biar rapi
        $gyms = Gym::all(); // buat dropdown gym

        return view('admin.classes.index', compact('schedules', 'gyms'));
    }

    // ✅ Simpan jadwal baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'gym_id'          => 'required|exists:gyms,id',
            'class_name'      => 'required|string|max:255',
            'instructor_name' => 'nullable|string|max:255',
            'day'             => 'required|string',
            'time'            => 'required',
            'quota'           => 'required|integer|min:1',
        ]);

        Class_Schedule::create($validated);

        return redirect()->route('admin.classes.index')
            ->with('success', '✅ Jadwal kelas berhasil ditambahkan.');
    }

    // ✅ Edit jadwal
    public function edit(Class_Schedule $class)
    {
        $gyms = Gym::all();
        return view('admin.classes.edit', compact('class', 'gyms'));
    }

    // ✅ Update jadwal
    public function update(Request $request, Class_Schedule $class)
    {
        $validated = $request->validate([
            'gym_id'          => 'required|exists:gyms,id',
            'class_name'      => 'required|string|max:255',
            'instructor_name' => 'nullable|string|max:255',
            'day'             => 'required|string',
            'time'            => 'required',
            'quota'           => 'required|integer|min:1',
        ]);

        $class->update($validated);

        return redirect()->route('admin.classes.index')
            ->with('success', '✅ Jadwal kelas berhasil diperbarui.');
    }

    // ✅ Hapus jadwal
    public function destroy(Class_Schedule $class)
    {
        $class->delete();

        return redirect()->route('admin.classes.index')
            ->with('success', '🗑️ Jadwal kelas berhasil dihapus.');
    }
}
