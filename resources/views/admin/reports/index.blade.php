@extends('layouts.guest')

@section('title', 'Laporan')

@section('content')
<div class="flex min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 fixed left-0 top-0 h-screen bg-white shadow-lg flex flex-col">
        <div class="flex items-center justify-center p-6 border-b">
            <span class="font-bold text-indigo-600 text-2xl">Bugarin</span>
        </div>

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
            <a href="{{ route('admin.membership-packages.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition {{ request()->routeIs('admin.membership-packages.*') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-gray-700' }}">
                <span>📦</span><span>Paket Membership</span>
            </a>
            <a href="{{ route('admin.reports.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-gray-700' }}">
                <span>📊</span><span>Laporan</span>
            </a>

            <div class="-mx-4"><hr class="border-gray-300"></div>

            <a href="{{ route('admin.settings') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition {{ request()->routeIs('admin.settings') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-gray-700' }}">
                <span>⚙️</span><span>Settings</span>
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
    <main class="ml-64 flex-1 p-6">
        <h1 class="text-2xl font-bold mb-6">📊 Laporan Gym</h1>

        <!-- Filter -->
        <form method="GET" action="{{ route('admin.reports.index') }}" class="mb-6 flex items-center space-x-4">
            <div>
                <label class="text-sm text-gray-600">Dari</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="border rounded px-3 py-2">
            </div>
            <div>
                <label class="text-sm text-gray-600">Sampai</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="border rounded px-3 py-2">
            </div>

            <!-- Tombol Filter -->
            <button type="submit" 
                class="bg-blue-600 text-white px-4 py-2 rounded-xl shadow-md 
                       transition transform hover:scale-105 hover:bg-blue-700 
                       hover:shadow-lg active:scale-95">
                🔍 Filter
            </button>

            <!-- Export PDF -->
            <a href="{{ route('admin.reports.export', ['format' => 'pdf']) }}"
                class="flex items-center gap-2 bg-red-500 text-white px-4 py-2 rounded-xl 
                       shadow-md transition transform hover:scale-105 hover:bg-red-600 
                       hover:shadow-lg active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 4v16m8-8H4" />
                </svg>
                Export PDF
            </a>

            <!-- Export Excel -->
            <a href="{{ route('admin.reports.export', ['format' => 'excel']) }}"
                class="flex items-center gap-2 bg-green-500 text-white px-4 py-2 rounded-xl 
                       shadow-md transition transform hover:scale-105 hover:bg-green-600 
                       hover:shadow-lg active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M4 6h16M4 12h8m-8 6h16" />
                </svg>
                Export Excel
            </a>
        </form>

        <!-- Statistik Ringkas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 shadow rounded-lg text-center">
                <h2 class="text-lg font-semibold">Total Member</h2>
                <p class="text-2xl font-bold text-indigo-600">{{ $totalMembers ?? 0 }}</p>
            </div>
            <div class="bg-white p-4 shadow rounded-lg text-center">
                <h2 class="text-lg font-semibold">Hadir Hari Ini</h2>
                <p class="text-2xl font-bold text-green-600">{{ $todayAttendance ?? 0 }}</p>
            </div>
            <div class="bg-white p-4 shadow rounded-lg text-center">
                <h2 class="text-lg font-semibold">Pendapatan Bulan Ini</h2>
                <p class="text-2xl font-bold text-yellow-600">Rp {{ number_format($monthlyRevenue ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Grafik Absensi -->
        <div class="bg-white p-6 shadow rounded-lg mb-6">
            <h2 class="text-lg font-semibold mb-4">Grafik Absensi Mingguan</h2>
            <canvas id="attendanceChart"></canvas>
        </div>

        <!-- Tabel Detail -->
        <div class="bg-white p-6 shadow rounded-lg">
            <h2 class="text-lg font-semibold mb-4">Detail Absensi</h2>
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-4 py-2 text-left">Nama Member</th>
                        <th class="border px-4 py-2 text-left">Tanggal</th>
                        <th class="border px-4 py-2 text-left">Kelas</th>
                        <th class="border px-4 py-2 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                        <tr>
                            <td class="border px-4 py-2">{{ $attendance->member->name }}</td>
                            <td class="border px-4 py-2">{{ $attendance->date }}</td>
                            <td class="border px-4 py-2">{{ $attendance->class->name ?? '-' }}</td>
                            <td class="border px-4 py-2">
                                <span class="px-2 py-1 rounded text-xs {{ $attendance->status == 'present' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-4 text-gray-500">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels ?? []),
            datasets: [{
                label: 'Absensi',
                data: @json($chartData ?? []),
                borderColor: '#4F46E5',
                backgroundColor: 'rgba(79,70,229,0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 4
            }]
        },
        options: { responsive: true }
    });
</script>
@endsection
