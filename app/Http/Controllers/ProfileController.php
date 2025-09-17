<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


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
            'city'          => 'nullable|string|max:100',
            'district'      => 'nullable|string|max:100',
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
        return redirect()->route('gyms.index')->with('success', 'Profile berhasil diperbarui!');

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



}