<?php

namespace App\Http\Controllers;

use App\Models\Membership_Package;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Member_Gym;


class ProfileController extends Controller
{
    /**
     * Menampilkan profil user login
     */
    public function show()
    {
        $user = Auth::user();
        $profile = $user->profile;

        if (!$profile) {
            // Jika profil tidak ditemukan, buat profil baru
            $profile = new Profile();
            $profile->user_id = $user->id;
            $profile->save();
        }

        return view('member.profile', compact('user','profile'));
    }

    /**
     * Update atau buat baru kalau belum ada
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone'         => 'nullable|string|max:20',
            'birth_date'    => 'nullable|date',
            'gender'        => 'nullable|in:Laki-laki,Perempuan',
            'address'          => 'nullable|string|max:100',
            'profile_photo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();

        $data = $request->only(['phone', 'birth_date', 'gender', 'city', 'district']);

        // handle upload foto
        if ($request->hasFile('profile_photo')) {
            // Hapus foto lama jika ada
            if ($user->profile && $user->profile->profile_photo) {
                Storage::delete('public/' . $user->profile->profile_photo);
            }
            $data['profile_photo'] = $request->file('profile_photo')->store('profiles', 'public');
        }

        Profile::updateOrCreate(
        ['user_id' => $user->id],
        $data
    );
        return redirect()->route('member.gyms.index')->with('success', 'Profile berhasil diperbarui!');

    }

   public function chooseGym(Request $request)
   
    {
        
        $request->validate([
            'gym_id' => 'required|exists:gyms,id',
        ]);

        $user = Auth::user();

        // Simpan gym yang dipilih
        Profile::updateOrCreate(
            ['user_id' => $user->id],
            ['gym_id' => $request->gym_id]
        );

        // Redirect ke halaman membership package gym yang dipilih
        return redirect()->route('membership.index', $request->gym_id)
            ->with('success', 'Gym berhasil dipilih!');
    }


    public function index($gym)
    {
        $gym = \App\Models\Gym::findOrFail($gym);
        $packages = $gym->packages()->latest()->get();

        return view('membership.index', compact('gym', 'packages'));
    }

   public function choosePackage($packageId)
    {
        $user = Auth::user();

        // pastikan paket ada
        $package = \App\Models\Membership_Package::findOrFail($packageId);

        // Simpan ke table member_gyms
        $membership = new Member_Gym();
        $membership->user_id = $user->id;
        $membership->gym_id = $package->gym_id;
        $membership->package_id = $package->id;
        $membership->start_date = now();
        $membership->end_date = now()->addDays($package->duration);
        $membership->status = 'active';
        $membership->save();

        return redirect()->route('member.dashboard')
            ->with('success', 'Paket membership berhasil dipilih!');
    }

    public function dashboard()
    {
        $user = Auth::user();

        // Ambil membership terbaru user
        $membership = Member_Gym::with('package')
            ->where('user_id', $user->id)
            ->latest('start_date')
            ->first();

        // Pastikan masih aktif
        if ($membership && $membership->end_date < now()) {
            $membership->status = 'expired';
            $membership->save();
        }

        // Ambil data kelas, absensi, info gym (sementara kosong kalau belum ada)
        $classes = []; 
        $attendances = [];
        $announcements = [];

        return view('member.dashboard', compact(
            'user',
            'membership',
            'classes',
            'attendances',
            'announcements'
        ));
    }

    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile;

        if (!$profile) {
            $profile = new Profile();
            $profile->user_id = $user->id;
            $profile->save();
        }

        return view('member.edit-profile', compact('user', 'profile'));
    }

}