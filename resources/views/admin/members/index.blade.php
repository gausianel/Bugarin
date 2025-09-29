@extends('layouts.admin')

@section('title', 'Daftar Member')

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
            <a href="{{ route('admin.members.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition {{ request()->routeIs('admin.members.index*') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-gray-700' }}">
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

            <!-- Header -->
            <div>
                <h1 class="text-3xl font-extrabold bg-gradient-to-r from-indigo-600 to-blue-500 bg-clip-text text-transparent">
                    👥 Daftar Member
                </h1>
                <p class="text-gray-600 mt-2 text-sm">
                    Lihat semua member yang terdaftar di gym {{ $gym ? $gym->name : '-' }}.
                </p>
            </div>

            <!-- Card Wrapper -->
            <div class="bg-white rounded-2xl shadow-md p-6">
                <!-- Table Responsive -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700 text-sm uppercase tracking-wide">
                                <th class="px-4 py-3">Nama</th>
                                <th class="px-4 py-3">Email</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Bergabung</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @forelse($members as $member)
                                <tr class="border-b hover:bg-gray-50 transition">
                                    <td class="px-4 py-3 font-medium">{{ $member->name }}</td>
                                    <td class="px-4 py-3">{{ $member->email }}</td>
                                    <td class="px-4 py-3">
                                        @php
                                            $status = 'Aktif'; // contoh default
                                            $color = $status === 'Aktif' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600';
                                        @endphp
                                        <span class="px-2 py-1 text-xs rounded-full font-semibold {{ $color }}">
                                            {{ $status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">{{ $member->created_at->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                                        Belum ada member terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $members->links() }}
                </div>
            </div>
        </section>
    </main>
</div>
@endsection
