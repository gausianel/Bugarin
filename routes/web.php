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
    use App\Http\Controllers\QrController;
    use App\Http\Controllers\GymAdminController;
    use App\Http\Controllers\RoleController;

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
    Route::post('/register', [AuthController::class, 'showregister']); // <- ini kayanya typo ya

    // Pisah Member & Gym register
    Route::prefix('register')->group(function () {
        Route::get('/member', [AuthController::class, 'createMember'])->name('register.member');
        Route::post('/member', [AuthController::class, 'storeMember']);
        Route::get('/gym', [AuthController::class, 'createGym'])->name('register.gym');
        Route::post('/gym', [AuthController::class, 'storeGym']);
    });

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
            Route::get('qr/refresh', [QrController::class, 'refresh'])->name('qr.refresh');
        
            // gym list buat member
            Route::get('/gyms', [GymController::class, 'index'])->name('gyms.index');
            Route::get('/gyms/{gym}', [GymController::class, 'show'])->name('gyms.show');

            Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit'); // form edit // form edit




            Route::post('/membership/choose/{package}', [ProfileController::class, 'choosePackage'])
            ->name('membership.choose');

           



        });

            Route::get('/membership/{gym}', [ProfileController::class, 'index'])->name('membership.index');

        // ============================
        // 🔹 Admin Routes
        // ============================
        Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

            // Gyms management
            Route::get('/gyms/create', [GymController::class, 'create'])->name('gyms.create');
            Route::post('/gyms', [GymController::class, 'store'])->name('gyms.store');
            Route::get('/gyms/{gym}/edit', [GymController::class, 'edit'])->name('gyms.edit');
            Route::put('/gyms/{gym}', [GymController::class, 'update'])->name('gyms.update');
            Route::get('/gyms/{gym}', [GymController::class, 'show'])->name('gyms.show');

            Route::get('/settings', [GymController::class, 'settings'])->name('settings');
            Route::put('/settings', [GymController::class, 'updateSettings'])->name('settings.update');

            Route::get('/members', [GymAdminController::class, 'member'])->name('members.index');


            // Resources
            Route::resource('members', UserController::class);
            Route::resource('announcements', GymInformationController::class);
            Route::resource('attendance', AttendanceController::class);
            Route::resource('classes', ClassScheduleController::class);
            Route::resource('membership-packages', MembershipPackageController::class);
            Route::resource('reminders', ReminderController::class);

            // Reports
            Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
            Route::get('/reports/export/{format}', [ReportController::class, 'export'])->name('reports.export');

            Route::put('/gym/settings', [GymController::class, 'updateSettings'])->name('gym.settings.update');

            // routes/web.php (dalam group admin)
            Route::post('/scan-qr', fn() => view('scan'))->name('scan.process');
            
            Route::post('/scan-qr/check', [AttendanceController::class, 'scanCheck'])->name('scan.qr.check');

           

             //Route::get('/members', [GymAdminController::class, 'member'])->name('members.index');


           



        });

            Route::get('/gyms', [GymController::class, 'index'])->name('gyms.index');
            Route::get('/gyms/{gym}', [GymController::class, 'show'])->name('gyms.show');



        // ============================
        // 🔹 Superadmin Routes
        // ============================
        Route::prefix('superadmin')->name('superadmin.')->middleware('role:superadmin')->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'superadminDashboard'])->name('dashboard');
        });

    });
