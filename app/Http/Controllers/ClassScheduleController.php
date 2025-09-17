<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Class_Schedule;
use App\Models\Gym;

class ClassScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = Class_Schedule::with('gym');

        // kalau mau filter berdasarkan gym_id
        if ($request->filled('gym_id')) {
            $query->where('gym_id', $request->gym_id);
        }

        $schedules = $query->orderByDesc('created_at')->paginate(10);

        return view('admin.classes.index', compact('schedules'));
    }


    public function create($gym_id)
    {
        $gym = Gym::findOrFail($gym_id);
        return view('class_schedule.create', compact('gym'));
    }

    public function store(Request $request, $gym_id)
    {
        $validated = $request->validate([
            'class_name'      => 'required|string|max:255',
            'instructor_name' => 'nullable|string|max:255',
            'day'             => 'required|string',
            'time'            => 'required',
            'quota'           => 'required|integer|min:1',
        ]);

        $validated['gym_id'] = $gym_id;
        Class_Schedule::create($validated);

        return redirect()->route('class-schedule.index', $gym_id)
            ->with('success', 'Class schedule created!');
    }

    public function edit($gym_id, $id)
    {
        $gym = Gym::findOrFail($gym_id);
        $schedule = Class_Schedule::findOrFail($id);

        return view('class_schedule.edit', compact('schedule', 'gym'));
    }

    public function update(Request $request, $gym_id, $id)
    {
        $schedule = Class_Schedule::findOrFail($id);

        $validated = $request->validate([
            'class_name'      => 'required|string|max:255',
            'instructor_name' => 'nullable|string|max:255',
            'day'             => 'required|string',
            'time'            => 'required',
            'quota'           => 'required|integer|min:1',
        ]);

        $schedule->update($validated);

        return redirect()->route('class-schedule.index', $gym_id)
            ->with('success', 'Class schedule updated!');
    }

    public function destroy($gym_id, $id)
    {
        $schedule = Class_Schedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('class-schedule.index', $gym_id)
            ->with('success', 'Class schedule deleted!');
    }
}
