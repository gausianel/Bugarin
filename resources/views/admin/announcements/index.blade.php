@extends('layouts.guest')

@section('title', 'Admin Dashboard')

@section('content')
<div x-data="{ open: true }" class="flex min-h-screen bg-gray-100">

    <!-- Sidebar -->
    <aside class="w-64 fixed left-0 top-0 h-screen bg-white shadow-lg flex flex-col">
        <!-- Header Sidebar -->
        <div class="flex items-center justify-center p-6 border-b">
            <span class="font-bold text-indigo-600 text-2xl">Bugarin</span>
        </div>

        <!-- Menu Sidebar -->
        <nav class="flex-1 p-4 space-y-2 text-sm">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-gray-700' }}">
                <span>🏠</span><span>Dashboard</span>
            </a>
            <a href="{{ route('admin.announcements.index') }}"
               class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition {{ request()->routeIs('admin.announcements.*') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-gray-700' }}">
                <span>📢</span><span>Pengumuman</span>
            </a>
            <a href="{{ route('admin.attendance.index') }}"
               class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition {{ request()->routeIs('admin.attendance.*') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-gray-700' }}">
                <span>🪪</span><span>Absensi</span>
            </a>
            <a href="{{ route('admin.classes.index') }}"
               class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition {{ request()->routeIs('admin.classes.*') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-gray-700' }}">
                <span>📚</span><span>Kelas</span>
            </a>
            <a href="{{ route('admin.membership-packages.index') }}"
               class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition {{ request()->routeIs('admin.membership-packages.*') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-gray-700' }}">
                <span>📦</span><span>Paket Membership</span>
            </a>
            <a href="{{ route('admin.reports.index') }}"
               class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-gray-700' }}">
                <span>📊</span><span>Laporan</span>
            </a>

            <!-- Garis pemisah -->
            <div class="-mx-4">
                <hr class="border-gray-300">
            </div>

            <!-- ⚙️Settings -->
            <a href="{{ route('admin.settings') }}"
               class="flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-50 transition {{ request()->routeIs('admin.settings') ? 'bg-indigo-100 text-indigo-600 font-semibold' : 'text-gray-700' }}">
                <span>⚙️</span><span>Settings</span>
            </a>
        </nav>

        <!-- Logout -->
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
    <main class="flex-1 ml-64 p-6"
        x-data="{ editing: null, form: { id: '', title: '', content: '', published_at: '' } }">

         <!-- ✅ Flash Message -->
    @if(session('success'))
        <div 
            x-show="showSuccess" 
            x-transition 
            x-init="setTimeout(() => showSuccess = false, 3000)"
            class="mb-6 p-4 rounded-lg bg-green-100 border border-green-300 text-green-800 flex justify-between items-center"
        >
            <span>✅ {{ session('success') }}</span>
            <button @click="showSuccess=false" class="ml-4 text-green-700 font-bold">✖</button>
        </div>
    @endif

    @if(session('error'))
        <div 
            x-show="showError" 
            x-transition 
            x-init="setTimeout(() => showError = false, 3000)"
            class="mb-6 p-4 rounded-lg bg-red-100 border border-red-300 text-red-800 flex justify-between items-center"
        >
            <span>❌ {{ session('error') }}</span>
            <button @click="showError=false" class="ml-4 text-red-700 font-bold">✖</button>
        </div>
    @endif

        <!-- Judul Halaman -->
        <div class="flex items-center justify-between mb-6 mt-10">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center space-x-2">
                <span>📢</span> <span>Daftar Pengumuman</span>
            </h1>
        </div>

        <!-- Form Tambah / Edit -->
        <div class="mb-8 bg-white rounded-xl shadow p-6 border border-gray-100">
            <h2 class="text-lg font-semibold text-gray-700 mb-4"
                x-text="editing ? '✏️ Edit Pengumuman' : '+ Tambah Pengumuman'"></h2>

                <form :action="editing ? '/admin/announcements/' + form.id : '{{ route('admin.announcements.store') }}'"
        method="POST" class="space-y-4">
        @csrf

        <!-- Selalu ada gym_id -->
        <input type="hidden" name="gym_id" value="1">

        <!-- Tambahan untuk update -->
        <template x-if="editing">
            <input type="hidden" name="_method" value="PUT">
        </template>

        <!-- Input Judul -->
        <input type="text" name="title" x-model="form.title"
            placeholder="Judul pengumuman"
            class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none"
            required>

        <!-- Input Isi -->
        <textarea name="content" x-model="form.content"
                placeholder="Isi pengumuman"
                class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none"
                rows="4" required></textarea>

        <!-- Input Tanggal -->
        <input type="date" name="published_at" x-model="form.published_at"
            class="p-3 border rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none">

        <!-- Tombol -->
        <div class="flex space-x-3 pt-2">
            <button 
                class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 
                    text-white font-medium shadow-md 
                    hover:from-blue-700 hover:to-blue-600 
                    hover:scale-105 hover:shadow-lg 
                    focus:ring-2 focus:ring-blue-300 
                    transition transform duration-200 ease-in-out"
                x-text="editing ? 'Update' : 'Simpan'">
                Kirim
            </button>

            <button 
                type="button" 
                x-show="editing"
                @click="editing=false; form={id:'',title:'',content:'',published_at:''}"
                class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-red-500 to-red-600 
                    text-white font-medium shadow-md 
                    hover:from-red-600 hover:to-red-500 
                    hover:scale-105 hover:shadow-lg 
                    focus:ring-2 focus:ring-red-300 
                    transition transform duration-200 ease-in-out">
                Batal
            </button>
        </div>
    </form>

    </div>

    <!-- Tabel Pengumuman -->
    <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">Judul</th>
                    <th class="px-6 py-3 text-left">Tanggal</th>
                    <th class="px-6 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($announcements as $a)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-3 font-medium text-gray-800">{{ $a->title }}</td>
                    <td class="px-6 py-3 text-gray-600">{{ $a->published_at ?? '-' }}</td>
                    <td class="px-6 py-3 flex space-x-2">
                        <!-- Tombol Edit -->
                        <button 
                        @click="editing=true; form={id:'{{ $a->id }}',title:'{{ $a->title }}',content:`{{ $a->content }}`,published_at:'{{ $a->published_at }}'}"
                        class="px-4 py-1.5 bg-yellow-400 hover:bg-yellow-500 text-white rounded-lg text-sm transition">
                        Edit
                    </button>


                        <!-- Tombol Hapus -->
                        <form method="POST" action="{{ route('admin.announcements.destroy', $a) }}">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Hapus pengumuman ini?')"
                                    class="px-4 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm transition">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-6 text-center text-gray-500">Belum ada pengumuman</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $announcements->links() }}
    </div>
</main>
</div>
@endsection
