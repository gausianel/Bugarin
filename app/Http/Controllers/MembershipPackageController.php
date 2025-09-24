<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membership_Package; // atau MembershipPackage
use App\Models\Gym;
use Illuminate\Support\Facades\Auth;

class MembershipPackageController extends Controller
{
    // ADMIN: List all packages (with gym)
    public function index()
    {
        $packages = Membership_Package::with('gym')->orderByDesc('created_at')->paginate(10);
        return view('admin.membership_packages.index', compact('packages'));
    }

    // ADMIN: Show create form
    public function create()
    {
        $gyms = Gym::all();
        return view('admin.membership_packages.create', compact('gyms'));
    }

    // ADMIN: Store new package
    public function store(Request $request)
    {
        $validated = $request->validate([
       'gym_id'        => 'required|exists:gyms,id',
        'name'  => 'required|string|max:255',
        'price'         => 'required|numeric',
        'duration' => 'required|integer',
        'description'   => 'nullable|string',
        ]);

        $validated['created_by'] = Auth::id();

        Membership_Package::create($validated);


        return redirect()->route('admin.membership-packages.index')
            ->with('success', 'Package created!');
    }

    // ADMIN: Show edit form
    public function edit($id)
    {
        $package = Membership_Package::findOrFail($id);
        $gyms = Gym::all();
        return view('admin.membership_packages.edit', compact('package', 'gyms'));
    }

    // ADMIN: Update package
    public function update(Request $request, $id)
    {
        $package = Membership_Package::findOrFail($id);

        $validated = $request->validate([
            'gym_id'        => 'required|exists:gyms,id',
            'name'  => 'required|string|max:255',
            'price'         => 'required|numeric',
            'duration' => 'required|integer',
            'description'   => 'nullable|string',
        ]);

        $package->update($validated);

        return redirect()->route('admin.membership-packages.index')
            ->with('success', 'Package updated!');
    }

    // ADMIN: Delete package
    public function destroy($id)
    {
        $package = Membership_Package::findOrFail($id);
        $package->delete();

        return redirect()->route('admin.membership-packages.index')
            ->with('success', 'Package deleted!');
    }

    // ADMIN: Show detail package
    public function show($id)
    {
        $package = Membership_Package::with('gym')->findOrFail($id);
        return view('admin.membership_packages.show', compact('package'));
    }

    // MEMBER: List all packages (index)
    public function memberIndex()
    {
        $packages = Membership_Package::with('gym')->orderByDesc('created_at')->paginate(10);
        return view('member.membership_packages.index', compact('packages'));
    }

    // MEMBER: Show detail package
    public function memberShow($id)
    {
        $package = Membership_Package::with('gym')->findOrFail($id);
        return view('member.membership_packages.show', compact('package'));
    }
}