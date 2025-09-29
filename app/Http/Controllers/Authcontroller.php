<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // ============================
    // 🔹 Show Login Form (Umum)
    // ============================
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // ============================
    // 🔹 Proses Login (Member/Admin)
    // ============================
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            Log::info('User logged in', [
                'id'    => $user->id,
                'email' => $user->email,
                'role'  => $user->role,
            ]);

            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    // ============================
    // 🔹 Show Register Form Member
    // ============================
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // ============================
    // 🔹 Register Member
    // ============================
    public function storeMember(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'member',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        Log::info('Member registered & logged in', [
            'id'    => $user->id,
            'email' => $user->email,
            'role'  => $user->role,
        ]);

        return redirect()->route('member.profile')
            ->with('success', 'Registrasi berhasil! Silakan pilih gym terlebih dahulu.');
    }

    // ============================
    // 🔹 Show Register Form Admin
    // ============================
    public function createGym()
    {
        return view('auth.register-gym');
    }

    // ============================
    // 🔹 Register Admin
    // ============================
    public function storeGym(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // 1. Buat user admin
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'admin',
        ]);

        // 2. Login otomatis
        Auth::login($user);
        $request->session()->regenerate();

        // 3. Buat gym default langsung setelah admin terdaftar
        $gym = \App\Models\Gym::create([
            'name'        => "Gym " . $user->name, // bisa diset default pakai nama admin
            'address'     => 'Alamat belum diisi',
            'description' => 'Deskripsi gym belum diisi',
        ]);

        // 4. Hubungkan user admin ke gym barunya
        $user->gym_id = $gym->id;
        $user->save();

        Log::info('Admin registered & gym created', [
            'admin_id' => $user->id,
            'gym_id'   => $gym->id,
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Akun admin & gym berhasil dibuat!');
    }


    // ============================
    // 🔹 Logout
    // ============================
    public function logout(Request $request)
    {
        $user = Auth::user();
        Log::info('User logged out', [
            'id'    => $user?->id,
            'email' => $user?->email,
            'role'  => $user?->role,
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // ============================
    // 🔹 Helper Redirect by Role
    // ============================
    private function redirectBasedOnRole($user)
    {
        $role = strtolower($user->role);

        if ($role === 'member') {
            if (!$user->profile || !$user->profile->gym_id) {
                return redirect()->route('member.dashboard')
                    ->with('info', 'Silakan pilih gym terlebih dahulu.');
            }
            return redirect()->route('member.dashboard');
        }

        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect('/');
    }

    
    public function createMember()
{
    return view('auth.register'); // blade form register member
}
}




