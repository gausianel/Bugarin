@extends('layouts.guest')

@section('title', 'Admin Dashboard')

@section('content')
<div class="flex min-h-screen bg-gray-100" x-data="{ editing: false, form: { id:'', class_name:'', instructor_name:'', day:'', time:'', quota:'', gym_id:'' }, setEdit(schedule){ this.editing=true; this.form = {...schedule} }, resetForm(){ this.editing=false; this.form={id:'',class_name:'',instructor_name:'',day:'',time:'',quota:'',gym_id:''} } }">

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
    <main class="flex-1 ml-64 p-6">
        <h1 class="text-3xl font-bold mb-4 mt-10">📦 Manajemen Paket Membership</h1>

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

        <!-- Form Tambah/Edit -->
        <div class="mb-6 p-4 bg-white rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-3" x-text="editing ? '✏️ Edit Paket' : '+ Tambah Paket'"></h2>

            <form :action="editing ? ('/admin/membership-packages/' + form.id) : '{{ route('admin.membership-packages.store') }}'" method="POST" class="space-y-3">
                @csrf
                <template x-if="editing">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <input type="text" name="name" x-model="form.name" placeholder="Nama Paket" class="w-full p-2 border rounded" required>
                <input type="number" name="price" x-model="form.price" placeholder="Harga" class="w-full p-2 border rounded" min="0" required>
                <input type="text" name="duration" x-model="form.duration" placeholder="Durasi (misal: 1 bulan)" class="w-full p-2 border rounded" required>
                <textarea name="description" x-model="form.description" placeholder="Deskripsi Paket" class="w-full p-2 border rounded"></textarea>

                <!-- Pilih Gym hanya saat tambah -->
                <select name="gym_id" class="w-full p-2 border rounded" x-show="!editing" required>
                    <option value="">Pilih Gym</option>
                    @foreach(App\Models\Gym::all() as $gym)
                        <option value="{{ $gym->id }}">{{ $gym->name }}</option>
                    @endforeach
                </select>

                 <!-- Tombol -->
            <div class="flex space-x-3 pt-2">
                <!-- Tombol Simpan/Update -->
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

                <!-- Tombol Batal -->
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

        <!-- Tabel Paket Membership -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full border-collapse">
                <thead class="bg-gray-100 text-sm font-semibold text-gray-700">
                    <tr>
                        <th class="p-3 text-left">Nama Paket</th>
                        <th class="p-3 text-left">Harga</th>
                        <th class="p-3 text-left">Durasi</th>
                        <th class="p-3 text-left">Deskripsi</th>
                        <th class="p-3 text-left">Gym</th>
                        <th class="p-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600">
                    @forelse($packages as $package)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="p-3">{{ $package->name }}</td>
                        <td class="p-3">{{ $package->price }}</td>
                        <td class="p-3">{{ $package->duration }}</td>
                        <td class="p-3">{{ $package->description ?? '-' }}</td>
                        <td class="p-3">{{ $package->gym->name ?? '-' }}</td>
                        <td class="p-3 flex space-x-2">
                            <button type="button" @click='setEdit(@json($package))' class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500 transition">Edit</button>
                            <form method="POST" action="{{ route('admin.membership-packages.destroy', $package) }}">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Hapus paket ini?')" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-3 text-center text-gray-500">Belum ada paket</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $packages->links() }}
        </div>
    </main>
</div>
@endsection