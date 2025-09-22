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
    // 🔹 Login Umum (Member/Admin)
    // ============================
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            Log::info('User logged in: ' . Auth::user()->email);

            return $this->redirectBasedOnRole(Auth::user());
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    // ============================
    // 🔹 Register Member
    // ============================
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function createMember()
    {
        return view('auth.register'); // blade form register member
    }

    public function showregister()
    {
        return view('member.profile');
    }


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

        // login & regenerate session
        Auth::login($user);
        $request->session()->regenerate();

        // Debug (sementara) — bisa dihapus nanti
        \Log::info('User registered and logged in: id=' . $user->id . ' auth_check=' . (Auth::check() ? 'yes' : 'no'));

        // redirect ke halaman daftar gym (public)
        return redirect()->route('member.gyms.index')
            ->with('success', 'Registrasi berhasil! Silakan pilih gym terlebih dahulu.');
    }




    // ============================
    // 🔹 Register Admin Gym
    // ============================
    public function createGym()
    {
        return view('auth.register-gym');
    }

    public function storeGym(Request $request)
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
            'role'     => 'admin',
        ]);

        // ✅ auto login admin yang baru register
        Auth::login($user);

        // ✅ langsung ke form create gym
        return redirect()->route('admin.gyms.create')
            ->with('success', 'Akun admin berhasil dibuat! Silakan daftarkan gym Anda.');
    }


    // ============================
    // 🔹 Login khusus Admin (opsional)
    // ============================
    public function showAdminLoginForm()
    {
        return view('auth.login-admin');
    }

    public function loginAdmin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();

            if ($user->role !== 'admin') {
                Auth::logout();
                return back()->withErrors(['email' => 'Anda bukan admin gym.']);
            }

            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    // ============================
    // 🔹 Logout
    // ============================
    public function logout(Request $request)
    {
        Log::info('User logged out: ' . Auth::user()->email);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // ============================
    // 🔹 Helper Redirect
    // ============================
    private function redirectBasedOnRole($user)
    {
        if ($user->role === 'member') {
            // cek apakah user sudah punya gym
            if (!$user->profile || !$user->profile->gym_id) {
                return redirect()->route('member.gyms.index')
                    ->with('info', 'Silakan pilih gym terlebih dahulu.');
            }
            return redirect()->route('member.dashboard');
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect('/');
    }

    
}
