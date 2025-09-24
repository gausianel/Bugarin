<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gym;
use Illuminate\Support\Facades\Auth;



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

        return view('member.gyms.index', compact('gyms'));
    }

    public function show($id)
    {
        $gym = Gym::findOrFail($id);
        return view('member.gyms.show', compact('gym'));
    }

    public function create()
    {
        return view('admin.gym.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'required|string',
            'logo'    => 'nullable|image|mimes:jpg,png|max:2048',
            'banner'  => 'nullable|image|mimes:jpg,png|max:5120',
        ]);

        // tambahin created_by manual
        $validated['created_by'] = auth()->id();

        $gym = Gym::create($validated);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('gyms/logos', 'public');
            $gym->update(['logo' => $path]);
        }

        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('gyms/banners', 'public');
            $gym->update(['banner' => $path]);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Gym created!');
    }


    public function edit(Gym $gym)
    {
        return view('admin.gyms.edit', compact('gym'));
    }

    public function update(Request $request, Gym $gym)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'logo' => 'nullable|image|mimes:jpg,png|max:2048',
            'banner' => 'nullable|image|mimes:jpg,png|max:5120',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('gyms/logos', 'public');
        }

        if ($request->hasFile('banner')) {
            $validated['banner'] = $request->file('banner')->store('gyms/banners', 'public');
        }

        $gym->update($validated);

        return redirect()->route('admin.dashboard')->with('success', 'Profil gym berhasil diperbarui!');
    }


    public function destroy($id)
    {
        $gym = Gym::findOrFail($id);
        $gym->delete();

        return redirect()->route('member.gyms.index')->with('success', 'Gym deleted!');
    }

    public function settings()
{
    $gym = auth()->user()->gym; // ambil gym milik admin login
    return view('admin.settings', compact('gym'));
}

}
