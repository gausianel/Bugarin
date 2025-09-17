@extends('layouts.guest')

@section('title', 'Register Gym')

@section('content')
<style>
    @keyframes fadeSlideUp {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-slide {
        animation: fadeSlideUp 0.8s ease-out forwards;
    }
</style>

<div class="w-full max-w-md bg-white rounded-xl shadow-lg border border-gray-200 p-8 mx-4 animate-fade-slide">
    <!-- Header -->
    <div class="text-center mb-6">
        <h1 class="text-4xl font-extrabold text-indigo-600">Bugarin</h1>
        <i class="fas fa-dumbbell text-indigo-600 text-xl mt-2"></i>
        <h2 class="text-2xl font-semibold text-black mt-4">Daftar untuk Gym</h2>
        <p class="text-sm text-gray-500 mt-1">Daftarkan Gym anda segera bersama Bugarin!</p>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="bg-red-50 text-red-600 text-sm p-3 rounded-md mb-4">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Registration Form -->
    <form method="POST" action="{{ route('register.gym') }}" class="space-y-5">
        @csrf

        <!-- Gym Name -->
        <div>
            <label for="name" class="block mb-1 text-sm text-gray-600">Nama Gym</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                class="w-full px-4 py-2 text-sm rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block mb-1 text-sm text-gray-600">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                class="w-full px-4 py-2 text-sm rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block mb-1 text-sm text-gray-600">Password</label>
            <input type="password" id="password" name="password" required autocomplete="new-password"
                class="w-full px-4 py-2 text-sm rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block mb-1 text-sm text-gray-600">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                class="w-full px-4 py-2 text-sm rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <!-- Submit -->
        <div>
            <button type="submit"
                class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                Daftar Gym
            </button>
        </div>
    </form>

    <!-- Footer -->
    <p class="text-center text-sm text-gray-500 mt-6">
        Sudah punya akun Gym?
        <a href="{{ route('login') }}" class="text-indigo-600 hover:underline font-medium">Masuk</a>
    </p>
</div>
@endsection
