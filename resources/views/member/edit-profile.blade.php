@extends('layouts.guest')

@section('title', 'Edit Profile Member')

@section('content')
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

<div class="w-full max-w-2xl bg-white rounded-xl shadow-lg border border-gray-200 p-5 mx-4 animate-fade-slide">
a
    <!-- Header -->
    <div class="text-center mb-6">
        <div class="flex flex-col items-center justify-center mb-3">
            <h1 class="text-4xl font-extrabold text-indigo-600">Bugarin</h1>
            <i class="fas fa-user-edit text-indigo-600 text-3xl mt-2"></i>
        </div>
        <h1 class="text-2xl font-semibold text-gray-800">Edit Profile</h1>
        <p class="text-sm text-gray-500">Perbarui data diri kamu</p>
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
    @if(session('success'))
        <div class="bg-green-50 text-green-600 text-sm p-3 rounded-md mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Edit Profile Form -->
    <form action="{{ route('member.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Nama -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}"
                class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-indigo-200 focus:border-indigo-500" required>
        </div>

        <!-- Email (readonly) -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" value="{{ $user->email ?? '' }}" disabled
                class="w-full px-4 py-2 border rounded-lg bg-gray-100 cursor-not-allowed text-gray-500">
            <p class="text-xs text-gray-400 mt-1">Email tidak dapat diubah</p>
        </div>

        <!-- Nomor HP -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP / WhatsApp</label>
            <input type="text" name="phone" value="{{ old('phone', $profile->phone ?? '') }}"
                class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-indigo-200 focus:border-indigo-500">
        </div>

        <!-- Tanggal Lahir -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
            <input type="date" name="birth_date" value="{{ old('birth_date', $profile->birth_date ?? '') }}"
                class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-indigo-200 focus:border-indigo-500">
        </div>

        <!-- Jenis Kelamin -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
            <select name="gender" class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-indigo-200 focus:border-indigo-500">
                <option value="">-- Pilih --</option>
                <option value="Laki-laki" {{ old('gender', $profile->gender ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Perempuan" {{ old('gender', $profile->gender ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>

        <!-- Alamat -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
            <textarea name="address" rows="3"
                class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-indigo-200 focus:border-indigo-500">{{ old('address', $profile->address ?? '') }}</textarea>
        </div>

        <!-- Foto Profil -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
            <input type="file" name="profile_photo"
                class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4
                       file:rounded-lg file:border-0 file:text-sm
                       file:font-semibold file:bg-indigo-50 file:text-indigo-700
                       hover:file:bg-indigo-100" />
            @if(!empty($profile->profile_photo))
                <div class="mt-2">
                    <img src="{{ asset('storage/'.$profile->profile_photo) }}" alt="Profile Picture"
                         class="h-16 w-16 rounded-full border object-cover">
                </div>
            @endif
        </div>

        <!-- Button -->
        <div class="pt-2">
            <button type="submit"
                class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg shadow hover:bg-indigo-700 transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
