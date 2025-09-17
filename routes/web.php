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
Route::post('/register', [AuthController::class, 'register']);

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
    });

    // ============================
    // 🔹 Admin Routes
    // ============================
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

        // Members
        Route::get('/members', [UserController::class, 'index'])->name('members.index');
        Route::get('/members/create', [UserController::class, 'create'])->name('members.create');
        Route::post('/members', [UserController::class, 'store'])->name('members.store');
        Route::get('/members/{member}', [UserController::class, 'show'])->name('members.show');
        Route::get('/members/{member}/edit', [UserController::class, 'edit'])->name('members.edit');
        Route::put('/members/{member}', [UserController::class, 'update'])->name('members.update');
        Route::delete('/members/{member}', [UserController::class, 'destroy'])->name('members.destroy');

        // Announcements
        Route::get('/announcements', [GymInformationController::class, 'index'])->name('announcements.index');
        Route::get('/announcements/create', [GymInformationController::class, 'create'])->name('announcements.create');
        Route::post('/announcements', [GymInformationController::class, 'store'])->name('announcements.store');
        Route::get('/announcements/{announcement}', [GymInformationController::class, 'show'])->name('announcements.show');
        Route::get('/announcements/{announcement}/edit', [GymInformationController::class, 'edit'])->name('announcements.edit');
        Route::put('/announcements/{announcement}', [GymInformationController::class, 'update'])->name('announcements.update');
        Route::delete('/announcements/{announcement}', [GymInformationController::class, 'destroy'])->name('announcements.destroy');

        // Attendance
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
        Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
        Route::get('/attendance/{attendance}', [AttendanceController::class, 'show'])->name('attendance.show');
        Route::get('/attendance/{attendance}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
        Route::put('/attendance/{attendance}', [AttendanceController::class, 'update'])->name('attendance.update');
        Route::delete('/attendance/{attendance}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');

        // Classes
        Route::get('/classes', [ClassScheduleController::class, 'index'])->name('classes.index');
        Route::get('/classes/create', [ClassScheduleController::class, 'create'])->name('classes.create');
        Route::post('/classes', [ClassScheduleController::class, 'store'])->name('classes.store');
        Route::get('/classes/{class}', [ClassScheduleController::class, 'show'])->name('classes.show');
        Route::get('/classes/{class}/edit', [ClassScheduleController::class, 'edit'])->name('classes.edit');
        Route::put('/classes/{class}', [ClassScheduleController::class, 'update'])->name('classes.update');
        Route::delete('/classes/{class}', [ClassScheduleController::class, 'destroy'])->name('classes.destroy');

        // Membership Packages
        Route::get('/membership-packages', [MembershipPackageController::class, 'index'])->name('membership-packages.index');
        Route::get('/membership-packages/create', [MembershipPackageController::class, 'create'])->name('membership-packages.create');
        Route::post('/membership-packages', [MembershipPackageController::class, 'store'])->name('membership-packages.store');
        Route::get('/membership-packages/{package}', [MembershipPackageController::class, 'show'])->name('membership-packages.show');
        Route::get('/membership-packages/{package}/edit', [MembershipPackageController::class, 'edit'])->name('membership-packages.edit');
        Route::put('/membership-packages/{package}', [MembershipPackageController::class, 'update'])->name('membership-packages.update');
        Route::delete('/membership-packages/{package}', [MembershipPackageController::class, 'destroy'])->name('membership-packages.destroy');

        // Reminders
        Route::get('/reminders', [ReminderController::class, 'index'])->name('reminders.index');
        Route::get('/reminders/create', [ReminderController::class, 'create'])->name('reminders.create');
        Route::post('/reminders', [ReminderController::class, 'store'])->name('reminders.store');
        Route::get('/reminders/{reminder}', [ReminderController::class, 'show'])->name('reminders.show');
        Route::get('/reminders/{reminder}/edit', [ReminderController::class, 'edit'])->name('reminders.edit');
        Route::put('/reminders/{reminder}', [ReminderController::class, 'update'])->name('reminders.update');
        Route::delete('/reminders/{reminder}', [ReminderController::class, 'destroy'])->name('reminders.destroy');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export/{format}', [ReportController::class, 'export'])->name('reports.export');

        // Profil Gym Admin (Settings)
        Route::get('/settings', [GymController::class, 'edit'])->name('settings');
        Route::put('/settings', [GymController::class, 'update'])->name('settings.update');

        // Create Gym
        Route::get('/gym/create', [GymController::class, 'create'])->name('gym.create');
        Route::post('/gym', [GymController::class, 'store'])->name('gym.store');
    });

    // ============================
    // 🔹 Superadmin Routes
    // ============================
    Route::prefix('superadmin')->name('superadmin.')->middleware('role:superadmin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'superadminDashboard'])->name('dashboard');
        // tambahin route superadmin lain di sini
    });
});
