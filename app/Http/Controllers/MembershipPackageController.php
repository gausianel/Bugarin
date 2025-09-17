<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membership_Package; // ganti jadi MembershipPackage kalau rename model
use App\Models\Gym;

class MembershipPackageController extends Controller
{
    public function index(Request $request)
    {
        $query = Membership_Package::with('gym');

        if ($request->filled('gym_id')) {
            $query->where('gym_id', $request->gym_id);
        }

        $packages = $query->orderByDesc('created_at')->paginate(10);

        return view('admin.membership-packages.index', compact('packages'));
    }

    public function create()
    {
        $gyms = Gym::all();
        return view('admin.membership-packages.create', compact('gyms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'gym_id' => 'required|exists:gyms,id',
            'name' => 'required|string',
            'price' => 'required|numeric',
            'duration' => 'required|integer',
            'description' => 'nullable|string',
        ]);

        Membership_Package::create($validated);

        return redirect()->route('admin.membership-packages.index')
            ->with('success', 'Package created!');
    }

    public function edit($id)
    {
        $package = Membership_Package::findOrFail($id);
        $gyms = Gym::all();
        return view('admin.membership_packages.edit', compact('package', 'gyms'));
    }

    public function update(Request $request, $id)
    {
        $package = Membership_Package::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'duration' => 'required|integer',
            'description' => 'nullable|string',
        ]);

        $package->update($validated);

        return redirect()->route('admin.membership-packages.index')
            ->with('success', 'Package updated!');
    }

    public function destroy($id)
    {
        $package = Membership_Package::findOrFail($id);
        $package->delete();

        return redirect()->route('admin.membership-packages.index')
            ->with('success', 'Package deleted!');
    }

    public function showByGym(Gym $gym)
    {
        // ambil semua package milik gym ini
        $packages = $gym->packages()->latest()->get();

        return view('membership.index', compact('gym', 'packages'));
    }

}
