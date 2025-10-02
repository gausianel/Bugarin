@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="flex min-h-screen bg-gray-100">

    <!-- Sidebar -->
    <!-- Sidebar -->
    <aside class="w-64 fixed left-0 top-0 h-screen bg-white shadow-lg flex flex-col">
        <!-- Header Sidebar -->
        <div class="flex items-center justify-center p-6 border-b">
            <span class="font-bold text-indigo-600 text-2xl">Bugarin</span>
        </div>

        <!-- Menu Sidebar -->
        <nav class="flex-1 p-4 space-y-2 text-sm">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-gray-700' }}">
                <span>🏠</span><span>Dashboard</span>
            </a>
            <a href="{{ route('admin.announcements.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition {{ request()->routeIs('admin.announcements.*') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-gray-700' }}">
                <span>📢</span><span>Pengumuman</span>
            </a>
            <a href="{{ route('admin.attendance.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition {{ request()->routeIs('admin.attendance.*') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-gray-700' }}">
                <span>🪪</span><span>Absensi</span>
            </a>
            <a href="{{ route('admin.classes.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition {{ request()->routeIs('admin.classes.*') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-gray-700' }}">
                <span>📚</span><span>Kelas</span>
            </a>
            
            <a href="{{ route('admin.members.index') }}" 
            class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition 
            {{ request()->routeIs('admin.members.index*') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-gray-700' }}">
                <span>👥</span><span>Members</span>
            </a>

            <a href="{{ route('admin.membership-packages.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition {{ request()->routeIs('admin.membership-packages.*') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-gray-700' }}">
                <span>📦</span><span>Paket Membership</span>
            </a>
            <a href="{{ route('admin.reports.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-gray-700' }}">
                <span>📊</span><span>Laporan</span>
            </a>

            <div class="-mx-4">
                <hr class="border-gray-300">
            </div>

            <a href="{{ route('admin.settings') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition {{ request()->routeIs('admin.settings') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-gray-700' }}">
                <span>🏋️</span><span>Profil Gym</span>
            </a>
        </nav>

        <div class="p-4 border-t">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full flex items-center justify-center bg-red-500 text-white py-2 rounded-lg hover:bg-red-600 transition">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="ml-64 flex-1 transition-all duration-300">
        <section class="p-6 space-y-8">

            <!-- Welcome Header -->
            <div>
                <h1 class="text-4xl font-extrabold bg-gradient-to-r from-indigo-600 to-blue-500 bg-clip-text text-transparent">
                    Selamat Datang, Admin! 👋
                </h1>
                <p class="text-gray-600 mt-2 text-sm">
                    Pantau aktivitas gym, kelola member, dan lihat laporan terbaru langsung dari dashboard ini.
                </p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

               <!-- Member Aktif -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg p-6 transition transform hover:-translate-y-1">
                <h2 class="text-sm font-medium text-gray-500">Member Aktif</h2>
                <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $activeMembers ?? 0 }}</p>
                <span class="text-xs text-gray-400">+{{ $newMembersThisMonth ?? 0 }} bulan ini</span>
            </div>

            <!-- Membership Expired -->
          <div class="bg-white rounded-xl shadow-md hover:shadow-lg p-6 transition transform hover:-translate-y-1 block">
                <h2 class="text-sm font-medium text-gray-500">Membership Expired</h2>
                <p class="text-3xl font-bold text-red-600 mt-2">{{ $expiredMemberships ?? 0}}</p>
                <span class="text-xs text-gray-400">⚠ Perlu follow-up</span>
            </div>


            <!-- Pendapatan -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg p-6 transition transform hover:-translate-y-1">
                <h2 class="text-sm font-medium text-gray-500">Pendapatan Bulan Ini</h2>
                <p class="text-3xl font-bold text-green-600 mt-2">
                    Rp {{ number_format($monthlyRevenue ?? 0, 0, ',', '.') }}
                </p>
                <span class="text-xs text-gray-400">
                    {{ ($revenueGrowth ?? 0) > 0 ? '+' . ($revenueGrowth ?? 0) : ($revenueGrowth ?? 0) }}% dibanding bulan lalu
                </span>
            </div>

            </div>

            <!-- Extra Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
                <!-- Activity -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        📰 Aktivitas Terbaru
                    </h2>
                    <ul class="space-y-3 text-sm text-gray-700">
                        @forelse(($recentActivities ?? []) as $activity)
                            <li>{!! $activity['message'] !!} 
                                <span class="text-xs text-gray-400">{{ $activity['time'] }}</span>
                            </li>
                        @empty
                            <li class="text-gray-400 text-sm">Belum ada aktivitas terbaru.</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Chart -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        📊 Statistik Absensi Mingguan
                    </h2>
                    <canvas id="weeklyAttendanceChart" height="160"></canvas>
                </div>

                
                <!-- Scan QR Code -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-700">📷 Scan QR Code</h2>
                    
                    <!-- Tempat kamera -->
                    <div id="reader" style="width:100%; max-width:400px;" class="mx-auto mt-4"></div>

                    <!-- Form untuk auto submit -->
                    <form id="scanForm" action="{{ route('admin.scan.qr.check') }}" method="POST" class="hidden">
                        @csrf
                        <input type="hidden" id="qrToken" name="token">
                    </form>

                    <!-- Optional: tampilkan token yg berhasil discan -->
                    <div id="scan-result" class="mt-3 text-green-600 font-bold text-center"></div>
                </div>



                 <!-- Form Testing Manual Check-in -->
                <form action="{{ route('admin.scan.qr.check') }}" method="POST" class="mt-6 w-full">                
                    @csrf
                <label class="block mb-2 text-gray-700 font-medium">Masukkan Token QR (Testing)</label>
                <input type="text" name="token" placeholder="Paste token QR di sini"
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-indigo-500" required>
                <button type="submit"
                    class="mt-3 w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg font-medium shadow-md transition">
                    ✅ Simpan Kehadiran (Test)
                </button>
            </form>
            </div>
        </section>
    </main>
</div>

    <!-- Chart.js -->
    <script>
        // fallback kalau $weeklyAttendance belum didefinisikan
        const _weeklyAttendance = @json($weeklyAttendance ?? ['labels' => [], 'data' => []]);

        const ctx = document.getElementById('weeklyAttendanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: _weeklyAttendance.labels,
                datasets: [{
                    label: 'Absensi Harian',
                    data: _weeklyAttendance.data,
                    borderColor: '#4F46E5',
                    backgroundColor: 'rgba(79,70,229,0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>

    

<!-- HTML5 QR Code Scanner -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    function onScanSuccess(decodedText, decodedResult) {
        console.log("Scanned:", decodedText);

        // set token ke hidden input
        document.getElementById("qrToken").value = decodedText;

        // auto submit form
        document.getElementById("scanForm").submit(); 
    }

    function onScanError(errorMessage) {
        console.warn(`QR error = ${errorMessage}`);
    }

    // aktifin kamera
    const html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
    html5QrcodeScanner.render(onScanSuccess, onScanError);
</script>


    

@endsection
