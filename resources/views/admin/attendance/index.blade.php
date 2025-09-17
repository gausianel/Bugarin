@extends('layouts.guest')

@section('title', 'Absensi')

@section('content')
<div class="flex min-h-screen bg-gray-100">

    <!-- Sidebar -->
    <aside class="w-64 fixed left-0 top-0 h-screen bg-white shadow-lg flex flex-col">
        <!-- Header Sidebar -->
        <div class="flex items-center justify-center p-6 border-b">
            <span class="font-bold text-indigo-600 text-2xl">Bugarin</span>
        </div>

        <!-- Menu Sidebar -->
        <nav class="flex-1 p-4 space-y-2 text-sm">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center space-x-3 p-3 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-gray-700 hover:bg-indigo-50' }}">
                <span>🏠</span><span>Dashboard</span>
            </a>
            <a href="{{ route('admin.announcements.index') }}"
               class="flex items-center space-x-3 p-3 rounded-lg transition {{ request()->routeIs('admin.announcements.*') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-gray-700 hover:bg-indigo-50' }}">
                <span>📢</span><span>Pengumuman</span>
            </a>
            <a href="{{ route('admin.attendance.index') }}"
               class="flex items-center space-x-3 p-3 rounded-lg transition {{ request()->routeIs('admin.attendance.*') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-gray-700 hover:bg-indigo-50' }}">
                <span>🪪</span><span>Absensi</span>
            </a>
            <a href="{{ route('admin.classes.index') }}"
               class="flex items-center space-x-3 p-3 rounded-lg transition {{ request()->routeIs('admin.classes.*') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-gray-700 hover:bg-indigo-50' }}">
                <span>📚</span><span>Kelas</span>
            </a>
            <a href="{{ route('admin.membership-packages.index') }}"
               class="flex items-center space-x-3 p-3 rounded-lg transition {{ request()->routeIs('admin.membership-packages.*') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-gray-700 hover:bg-indigo-50' }}">
                <span>📦</span><span>Paket Membership</span>
            </a>
            <a href="{{ route('admin.reports.index') }}"
               class="flex items-center space-x-3 p-3 rounded-lg transition {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-gray-700 hover:bg-indigo-50' }}">
                <span>📊</span><span>Laporan</span>
            </a>

            <!-- Garis pemisah -->
            <div class="-mx-4 my-2">
                <hr class="border-gray-300">
            </div>

            <!-- ⚙️ Settings -->
            <a href="{{ route('admin.settings') }}"
               class="flex items-center space-x-3 p-3 rounded-lg transition {{ request()->routeIs('admin.settings') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-gray-700 hover:bg-indigo-50' }}">
                <span>⚙️</span><span>Settings</span>
            </a>
        </nav>

        <!-- Logout -->
        <div class="p-4 border-t">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full flex items-center justify-center gap-2 bg-red-500 text-white py-2 rounded-lg hover:bg-red-600 transition font-medium">
                    🚪 Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="ml-64 flex-1 p-8">
        <h1 class="text-2xl font-bold mb-6 mt-20 text-gray-800">📋 Daftar Absensi Gym</h1>

        

        <!-- Filter Tanggal -->
        <div class="mb-6 bg-white shadow rounded-xl p-5 w-full">
            <form method="GET" action="{{ route('admin.attendance.index') }}" class="flex flex-wrap items-center gap-3">
                <label for="date" class="font-medium text-gray-700">Tanggal:</label>
                <input type="date" 
                       id="date" 
                       name="date"
                       value="{{ request('date', now()->toDateString()) }}"
                       class="w-60 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg font-medium shadow-md transition">
                    Filter
                </button>
            </form>
        </div>

        <!-- Tabel Absensi -->
        <div class="bg-white shadow-lg rounded-xl overflow-hidden w-full">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white">
                            <th class="p-4 text-left font-semibold">Nama Member</th>
                            <th class="p-4 text-left font-semibold">Jam Absen</th>
                            <th class="p-4 text-left font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                            <tr class="hover:bg-indigo-50 transition">
                                <td class="p-4 text-gray-700 font-medium">{{ $attendance->user->name }}</td>
                                <td class="p-4 text-gray-600">{{ $attendance->check_in_time->format('H:i') }}</td>
                                <td class="p-4">
                                    <span class="px-3 py-1 inline-flex items-center text-sm font-medium rounded-full bg-green-100 text-green-700">
                                        ✅ Hadir
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-6 text-center">
                                    <div class="flex flex-col items-center text-gray-500">
                                        <span class="text-3xl mb-2">📭</span>
                                        <p class="font-medium text-red-500">Belum ada absensi untuk tanggal ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
@endsection
