<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GymController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GymInformationController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ClassScheduleController;
use App\Http\Controllers\MembershipPackageController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\ReportController;
use App\Models\Profile;

// ============================
// 🟢 Guest Routes
// ============================
Route::get('/', fn() => view('welcome'));
Route::get('/maintenance', fn() => view('maintenance'))->name('maintenance');

// 🔑 Login & Register
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/login/admin', [AuthController::class, 'showAdminLoginForm'])->name('login.admin');
Route::post('/login/admin', [AuthController::class, 'loginAdmin']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'showregister']);Route::post('/register/member', [AuthController::class, 'storeMember']);
Route::post('/register/gym', [AuthController::class, 'storeGym']);

// Pisah Member & Gym
Route::get('/register/member', [AuthController::class, 'createMember'])->name('register.member');
Route::post('/register/member', [AuthController::class, 'storeMember']);
Route::get('/register/gym', [AuthController::class, 'createGym'])->name('register.gym');
Route::post('/register/gym', [AuthController::class, 'storeGym']);

// 🚪 Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 🔐 Password reset
Route::get('/forgot-password', fn() => view('auth.forgot-password'))
    ->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email']);
    $status = Password::sendResetLink($request->only('email'));
    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', [
        'token' => $token,
        'email' => request('email'),
    ]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (Illuminate\Http\Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ])->save();
            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');

// ============================
// 🔒 Protected Routes
// ============================
Route::middleware('auth')->group(function () {

    // ============================
    // 🔹 Member Routes
    // ============================
    Route::prefix('member')->name('member.')->middleware('role:member')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'memberDashboard'])->name('dashboard');
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/choose-gym', [ProfileController::class, 'chooseGym'])->name('profile.chooseGym');
        Route::get('/classes', [ClassScheduleController::class, 'index'])->name('classes');
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance');
        Route::get('/info', [GymInformationController::class, 'index'])->name('info');
        Route::get('/qr/refresh', [DashboardController::class, 'refresh'])->name('qr.refresh');
        Route::get('/membership', [ProfileController::class, 'index'])->name('membership.index');
        


        Route::get('/gyms', [GymController::class, 'index'])->name('gyms.index');

    });

    Route::get('/gyms', [GymController::class, 'index'])->name('gyms.index');
    Route::get('/gyms/{gym}', [GymController::class, 'show'])->name('gyms.show');
    



    // ============================
    // 🔹 Admin Routes
    // ============================
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        // Dashboard admin
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

        // Gym create (pasca register pertama kali)
        Route::get('/gyms/create', [GymController::class, 'create'])->name('gyms.create');
        Route::post('/gyms', [GymController::class, 'store'])->name('gyms.store');
        Route::get('/gyms/{gym}/edit', [GymController::class, 'edit'])->name('gyms.edit');
        Route::put('/gyms/{gym}', [GymController::class, 'update'])->name('gyms.update');

        // Gym Settings (khusus admin login)
        Route::get('/settings', [GymController::class, 'edit'])->name('settings');
        Route::put('/settings', [GymController::class, 'update'])->name('settings.update');

        // Kalau mau edit gym by ID (opsional)
        Route::get('/gyms/{gym}/edit', [GymController::class, 'edit'])->name('gyms.edit');
        Route::put('/gyms/{gym}', [GymController::class, 'update'])->name('gyms.update');

        Route::post('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard.post');


        // Members
        Route::resource('members', UserController::class);

        // Announcements
        Route::resource('announcements', GymInformationController::class);

        // Attendance
        Route::resource('attendance', AttendanceController::class);

        // Classes
        Route::resource('classes', ClassScheduleController::class);

        // Membership Packages
        Route::resource('membership-packages', MembershipPackageController::class);

        // Reminders
        Route::resource('reminders', ReminderController::class);

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export/{format}', [ReportController::class, 'export'])->name('reports.export');

        Route::get('/gyms/{gym}', [GymController::class, 'show'])->name('gyms.show');

    });

    Route::post('/gyms', [GymController::class, 'store'])->name('gyms.store');
    Route::put('/gyms/{gym}', [GymController::class, 'update'])->name('gyms.update');
    // List Gym (public buat member/admin)



    // ============================
    // 🔹 Superadmin Routes
    // ============================
    Route::prefix('superadmin')->name('superadmin.')->middleware('role:superadmin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'superadminDashboard'])->name('dashboard');
        // tambahin route superadmin lain di sini
    });

        // Halaman settings gym (form edit)
    Route::get('/admin/gyms/settings', [GymController::class, 'edit'])
        ->name('admin.gym.settings');

    // Update settings gym (form submit)
    Route::put('/admin/gyms/settings', [GymController::class, 'update'])
        ->name('admin.gym.settings.update');

    
});
