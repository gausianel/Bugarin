<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gym;

class GymController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $gyms = Gym::query();

        if ($search) {
            $gyms->where('name', 'like', "%$search%");
        }

        $gyms = $gyms->paginate(10);

        return view('gyms.index', compact('gyms'));
    }

    public function show($id)
    {
        $gym = Gym::findOrFail($id);
        return view('gyms.show', compact('gym'));
    }

    public function create()
    {
        return view('gyms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required',
            'address'   => 'required',
            'deskripsi' => 'nullable',
            'url_photo' => 'nullable|string',
        ]);

        $gym = Gym::create($validated);

        return redirect()->route('gyms.edit', $gym->id)->with('success', 'Gym created!');
    }

    public function edit()
    {
        $admin = auth()->user();

        // Gym diambil berdasarkan user yang login
        $gym = Gym::where('user_id', $admin->id)->first();

        return view('admin.settings', compact('gym'));
    }

    public function update(Request $request)
    {
        $admin = auth()->user();
        $gym = Gym::where('user_id', $admin->id)->firstOrFail();

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'address'   => 'required|string',
            'logo'      => 'nullable|image|mimes:jpg,png|max:2048',
            'banner'    => 'nullable|image|mimes:jpg,png|max:5120',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('gyms/logos', 'public');
        }

        if ($request->hasFile('banner')) {
            $validated['banner'] = $request->file('banner')->store('gyms/banners', 'public');
        }

        $gym->update($validated);

        return redirect()->route('admin.settings')->with('success', 'Profil gym berhasil diperbarui!');
    }


    public function destroy($id)
    {
        $gym = Gym::findOrFail($id);
        $gym->delete();

        return redirect()->route('gyms.index')->with('success', 'Gym deleted!');
    }

    
}
