<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Gym;
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
    // 🔹 Register Admin + Gym
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

    // 3. Redirect ke form isi profil gym
    return redirect()->route('admin.gyms.create')
        ->with('success', 'Akun admin berhasil dibuat! Silakan lengkapi profil gym Anda.');
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

    // ============================
    // 🔹 Tambahan buat view register member
    // ============================
    public function createMember()
    {
        return view('auth.register'); // blade form register member
    }
}
