<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gym_Admin;
use App\Models\User;
use App\Models\Gym;

class GymAdminController extends Controller
{
    // 🔹 List semua admin gym
    public function index()
    {
        $gym = Gym_Admin::with(['gym', 'user'])->paginate(10);
        return view('admin.settings', compact('gym'));
    }

    // 🔹 Assign admin ke gym
    public function assignAdminToGym(Request $request)
    {
        $validated = $request->validate([
            'gym_id' => 'required|exists:gyms,id',
            'user_id' => 'required|exists:users,id',
            'role_in_gym' => 'required|string',
        ]);

        Gym_Admin::create([
            'gym_id' => $validated['gym_id'],
            'user_id' => $validated['user_id'],
            'role_in_gym' => $validated['role_in_gym'],
            'assigned_at' => now(),
        ]);

        return back()->with('success', 'Admin assigned to gym!');
    }

    // 🔹 Hapus admin dari gym
    public function removeAdminFromGym($id)
    {
        $admin = Gym_Admin::findOrFail($id);
        $admin->delete();

        return back()->with('success', 'Admin removed from gym!');
    }

    // 🔹 List admin berdasarkan gym tertentu
    public function listAdminsOfGym($gym_id)
    {
        $admins = Gym_Admin::where('gym_id', $gym_id)
            ->with('user')
            ->paginate(10);

        $gym = Gym::findOrFail($gym_id);

        return view('gym_admin.list', compact('admins', 'gym'));
    }

    // 🔹 Update role admin
    public function updateAdminInGym(Request $request, $id)
    {
        $validated = $request->validate([
            'role_in_gym' => 'required|string',
        ]);

        $admin = Gym_Admin::findOrFail($id);
        $admin->role_in_gym = $validated['role_in_gym'];
        $admin->save();

        return back()->with('success', 'Admin role updated successfully!');
    }

    // 🔹 Tambah gym baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        $gym = Gym::create($validated);

        return response()->json([
            'message' => 'Gym created successfully!',
            'gym' => $gym,
        ], 201);
    }

    // ===============================
    //   SETTINGS / PROFILE GYM
    // ===============================

    // 🔹 Edit profil gym (buat admin gym)
    public function editProfile()
    {
            $user = auth()->user();

            $gymAdmin = Gym_Admin::where('user_id', $user->id)->first();
            $gym = $gymAdmin ? Gym::find($gymAdmin->gym_id) : new Gym();

            return view('admin.settings', compact('gym'));
    }

    // 🔹 Update profil gym
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $gymAdmin = Gym_Admin::where('user_id', $user->id)->firstOrFail();
        $gym = Gym::findOrFail($gymAdmin->gym_id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'banner' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        // Upload logo
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('gyms/logo', 'public');
        }

        // Upload banner
        if ($request->hasFile('banner')) {
            $validated['banner'] = $request->file('banner')->store('gyms/banner', 'public');
        }

        $gym->update($validated);

        return redirect()->route('admin.settings')->with('success', 'Profil Gym berhasil diperbarui!');
    }
}
