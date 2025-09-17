@extends('layouts.guest')

@section('title', 'Profile Member')

@section('name', 'content')

<style>
    @keyframes fadeSlideUp {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-fade-slide {
        animation: fadeSlideUp 0.8s ease-out forwards;
    }
</style>

<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg border
        border-gray-200 p-8 mx-4 animate-fade-slide">

        <!-- Header -->
        <div class="text-center mb-6">
            <div class="w-14 h-14 mx-auto mb-3 flex items-center justify-center">
                <h1 class="text-4xl font-extrabold text-indigo-600">Bugarin</h1>
                <i class="fas fa-user text-indigo-600 text-3xl"></i>
            </div>
            <h1 class="text-2xl font-semibold text-gray-800">Member Profile</h1>
            <p class="text-sm text-gray-500">Lengkapi data diri kamu</p>
        </div>

        {{-- Error Messages --}}
        @if($errors->any())
            <div class="bg-red-50 text-red-600 text-sm p-3 rounded-md mb-4">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Status Message --}}
        @if(session('status'))
            <div class="bg-green-50 text-green-600 text-sm p-3 rounded-md mb-4">
                {{ session('status') }}
            </div>
        @endif

        <!-- Profile Form -->
       <form action="{{ route('member.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

            <!-- Nomor HP -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP / WhatsApp</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-indigo-200 focus:border-indigo-500">
            </div>

            <!-- Tanggal Lahir -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                <input type="date" name="birthdate" value="{{ old('birthdate', $user->birthdate ?? '') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-indigo-200 focus:border-indigo-500">
            </div>

            <!-- Jenis Kelamin -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                <select name="gender" class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-indigo-200 focus:border-indigo-500">
                    <option value="">-- Pilih --</option>
                    <option value="Laki-laki" {{ old('gender', $user->gender ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('gender', $user->gender ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>

            <!-- Alamat / Domisili -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Domisili</label>
                <input type="text" name="address" value="{{ old('address', $user->address ?? '') }}"
                    placeholder="Contoh: Bandung, Jawa Barat"
                    class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-indigo-200 focus:border-indigo-500">
            </div>

            <!-- Foto Profil -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                <input type="file" name="avatar"
                    class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4
                           file:rounded-lg file:border-0 file:text-sm
                           file:font-semibold file:bg-indigo-50 file:text-indigo-700
                           hover:file:bg-indigo-100" />
                @if(!empty($user->avatar))
                    <div class="mt-2">
                        <img src="{{ asset('storage/'.$user->avatar) }}" alt="Profile Picture"
                             class="h-16 w-16 rounded-full border object-cover">
                    </div>
                @endif
            </div>

            <!-- Button -->
            <div class="pt-4">
                <button type="submit"
                    class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg shadow hover:bg-indigo-700 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
