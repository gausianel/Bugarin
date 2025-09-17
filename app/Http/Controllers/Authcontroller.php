<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
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

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
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
            'role'     => 'member', // default role = member
        ]);

        Auth::login($user);

        return redirect()->route('member.profile');
    }

    public function logout(Request $request)
    {
        Log::info('User logged out: ' . Auth::user()->email);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // 🔹 Register Member
    public function createMember()
    {
        return view('auth.register'); 
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

        Auth::login($user);

        return redirect()->route('member.profile');
    }

    // 🔹 Register Gym Admin
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

        Auth::login($user);

        return redirect()->route('admin.gym.create');
    }

    // 🔹 Login khusus Admin
    public function showAdminLoginForm()
    {
        return view('auth.login-admin');
    }

    public function loginAdmin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
            'gym_name' => 'required|string',
        ]);

        $remember = $request->filled('remember');

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $remember)) {
            $user = Auth::user();

            if ($user->role !== 'admin') {
                Auth::logout();
                return back()->withErrors(['email' => 'Anda bukan admin gym.']);
            }

            if ($user->gym && $user->gym->name !== $credentials['gym_name']) {
                Auth::logout();
                return back()->withErrors(['gym_name' => 'Nama gym tidak cocok.']);
            }

            $request->session()->regenerate();
            return redirect()->route('admin.gym.create');
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    // 🔹 Helper: redirect sesuai role
    private function redirectBasedOnRole($user)
    {
        if ($user->role === 'member') {
            return redirect()->route('member.dashboard');
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.gym.create');
        } else {
            return redirect('/');
        }
    }
}
