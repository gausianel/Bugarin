<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membership_Package;
use Illuminate\Support\Facades\Auth;

class MembershipPackageController extends Controller
{
    // ========================
    // 🔹 ADMIN: List all packages
    // ========================
    public function index()
    {
        $packages = Membership_Package::with('gym')
            ->where('gym_id', Auth::user()->gym_id) // hanya paket gym admin
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.membership_packages.index', compact('packages'));
    }

    // 🔹 ADMIN: Show create form
    public function create()
    {
        return view('admin.membership_packages.create');
    }

    // 🔹 ADMIN: Store new package
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'price'              => 'required|numeric|min:0',
            'duration_in_months' => 'required|integer|min:1',
            'description'        => 'nullable|string',
        ]);

        $user = Auth::user();

        Membership_Package::create([
            'gym_id'             => $user->gym_id, // ✅ otomatis ambil gym dari admin login
            'user_id'            => $user->id,
            'name'               => $validated['name'],
            'price'              => $validated['price'],
            'duration_in_months' => $validated['duration_in_months'],
            'description'        => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.membership-packages.index')
                        ->with('success', 'Paket membership berhasil ditambahkan!');
    }


    // 🔹 ADMIN: Show edit form
    public function edit($id)
    {
        $package = Membership_Package::where('gym_id', Auth::user()->gym_id)->findOrFail($id);
        return view('admin.membership_packages.edit', compact('package'));
    }

    // 🔹 ADMIN: Update package
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $package = Membership_Package::where('gym_id', $user->gym_id)->findOrFail($id);

        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'price'              => 'required|numeric|min:0',
            'duration_in_months' => 'required|integer|min:1',
            'description'        => 'nullable|string',
        ]);

        $package->update([
            'name'               => $validated['name'],
            'price'              => $validated['price'],
            'duration_in_months' => $validated['duration_in_months'],
            'description'        => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.membership-packages.index')
                         ->with('success', 'Paket membership berhasil diperbarui!');
    }

    // 🔹 ADMIN: Delete package
    public function destroy($id)
    {
        $package = Membership_Package::where('gym_id', Auth::user()->gym_id)->findOrFail($id);
        $package->delete();

        return redirect()->route('admin.membership-packages.index')
                         ->with('success', 'Paket membership berhasil dihapus!');
    }

    // 🔹 ADMIN: Show detail package
    public function show($id)
    {
        $package = Membership_Package::with('gym')
            ->where('gym_id', Auth::user()->gym_id)
            ->findOrFail($id);

        return view('admin.membership_packages.show', compact('package'));
    }

    // ========================
    // 🔹 MEMBER: List packages
    // ========================
    public function memberIndex()
    {
        $packages = Membership_Package::with('gym')
            ->where('gym_id', Auth::user()->profile->gym_id ?? null)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('member.membership_packages.index', compact('packages'));
    }

    // 🔹 MEMBER: Show detail package
    public function memberShow($id)
    {
        $package = Membership_Package::with('gym')->findOrFail($id);
        return view('member.membership_packages.show', compact('package'));
    }
}
