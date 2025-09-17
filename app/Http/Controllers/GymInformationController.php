<?php

namespace App\Http\Controllers;

use App\Models\Gym_Information;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class GymInformationController extends Controller
{
    // 🔹 Tampilkan semua pengumuman (dengan paginate)
    public function index(Request $request)
    {
        $query = Gym_Information::query();

        if ($request->filled('gym_id')) {
            $query->where('gym_id', $request->gym_id);
        }

        // Pake paginate biar bisa dipake sama ->links() di Blade
        $announcements = $query->orderByDesc('created_at')->paginate(10);

        return view('admin.announcements.index', compact('announcements'));
    }

    // 🔹 Form tambah pengumuman
    public function create()
    {
        return view('admin.announcements.create');
    }

    // 🔹 Simpan pengumuman baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'gym_id'  => 'required|exists:gyms,id',
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Gym_Information::create($validated);

        return redirect()->route('admin.announcements.index')
                         ->with('success', 'Pengumuman berhasil ditambahkan!');
    }

    // 🔹 Lihat detail pengumuman
    public function show($id)
    {
        $announcement = Gym_Information::findOrFail($id);

        return view('admin.announcements.show', compact('announcement'));
    }

    // 🔹 Form edit
    public function edit($id)
    {
        $announcement = Gym_Information::findOrFail($id);

        return view('admin.announcements.edit', compact('announcement'));
    }

    // 🔹 Update pengumuman
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $announcement = Gym_Information::findOrFail($id);
        $announcement->update($validated);

        return redirect()->route('admin.announcements.index')
                         ->with('success', 'Pengumuman berhasil diperbarui!');
    }

    // 🔹 Hapus pengumuman
    public function destroy($id)
    {
        $announcement = Gym_Information::findOrFail($id);
        $announcement->delete();

        return redirect()->route('admin.announcements.index')
                         ->with('success', 'Pengumuman berhasil dihapus!');
    }
}
