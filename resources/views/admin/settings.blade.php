@extends('layouts.guest')

@section('title', 'Profil Gym')

@section('content')
<div class="container mt-10 mb-10 px-6 py-10">
        
    <div class="max-w-xl mx-auto bg-white shadow-xl rounded-2xl p-8">
        
         <!-- Tombol Back -->
        <div class="mb-6">
            <a href="{{ route('admin.dashboard') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray text-black rounded-lg hover:bg-gray-200 transition">
                ← Kembali ke Dashboard
            </a>
        </div>
        
        <h1 class="text-3xl font-extrabold text-gray-800 mb-8 text-center">
            🏋️ Profil Gym
        </h1>

        <form method="POST" action="{{ route('admin.gym.settings.update') }}" enctype="multipart/form-data" class="space-y-2">
            @csrf
            @method('PUT')

            <!-- Nama -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Gym</label>
                <input type="text" 
                    name="name" 
                    value="{{ old('name', optional($gym)->name) }}"
                    class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                    placeholder="Masukkan nama gym">
            </div>

            <!-- Alamat -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                <textarea name="address"
                        rows="3"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                        placeholder="Alamat lengkap gym">{{ old('address', optional($gym)->address) }}</textarea>
            </div>

            <!-- Upload Logo -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">Logo Gym</label>
                <div class="flex items-center gap-6">
                    <div class="flex-1">
                        <input type="file" 
                            name="logo"
                            accept="image/*"
                            class="block w-full text-sm text-gray-600
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-lg file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-indigo-50 file:text-indigo-700
                                    hover:file:bg-indigo-100 cursor-pointer" />
                        <p class="text-xs text-gray-400 mt-2">Format: JPG, PNG, maksimal 2MB</p>
                    </div>

                    @if(optional($gym)->logo)
                        <div class="flex-shrink-0">
                            <img src="{{ asset('storage/'.$gym->logo) }}" 
                                 alt="Logo Gym" 
                                 class="h-20 w-20 object-cover rounded-lg shadow border">
                        </div>
                    @endif
                </div>
            </div>

            <!-- Upload Banner -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">Banner Gym</label>
                <div class="w-full border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-indigo-400 transition cursor-pointer">
                    <input type="file" 
                        name="banner"
                        accept="image/*"
                        class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4
                                file:rounded-lg file:border-0 file:text-sm
                                file:font-semibold file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100 cursor-pointer" />
                    <p class="text-xs text-gray-400 mt-2">Format: JPG, PNG, maksimal 5MB</p>

                    @if(optional($gym)->banner)
                        <div class="mt-4">
                            <img src="{{ asset('storage/'.$gym->banner) }}" 
                                 alt="Banner Gym" 
                                 class="h-40 w-full object-cover rounded-xl shadow border">
                        </div>
                    @endif
                </div>
            </div>

            <!-- Button -->
            <div class="pt-6">
                <button type="submit" 
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl shadow-lg transition transform hover:-translate-y-0.5">
                    💾 Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
