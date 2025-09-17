@extends('layouts.guest')

@section('title', 'Admin Login')

@section('content')
<style>
    /* Animasi Fade-in + Slide-up */
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

<div class="w-full max-w-md bg-white rounded-xl shadow-lg border border-gray-200 p-8 mx-4 animate-fade-slide">
    
    {{-- Header --}}
    <div class="text-center mb-6">
        <div class="w-12 h-12 mx-auto mb-3 flex items-center justify-center">
            <h1 class="text-4xl font-extrabold text-indigo-600">Bugarin</h1>
            <i class="fas fa-user-shield text-indigo-600 text-xl ml-2"></i>
        </div>
        <h1 class="text-2xl font-semibold text-black">Masuk sebagai Admin</h1>
        <p class="text-sm text-gray-500 mt-1">Masuk untuk mengelola gym Anda!</p>
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

    {{-- Login Form --}}
    <form method="POST" action="{{ route('login.admin') }}" class="space-y-5">
        @csrf

        {{-- Nama Gym --}}
        <div>
            <label for="gym" class="block mb-1 text-sm text-gray-600">Nama Gym</label>
            <input 
                type="text"
                id="gym_name"
                name="gym_name"
                value="{{ old('gym_name') }}"
                required
                class="w-full px-4 py-2 text-sm rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            >
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block mb-1 text-sm text-gray-600">Email</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                value="{{ old('email') }}"
                required 
                autofocus
                class="w-full px-4 py-2 text-sm rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            >
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block mb-1 text-sm text-gray-600">Password</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                required 
                class="w-full px-4 py-2 text-sm rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            >
        </div>

        {{-- Remember & Forgot --}}
        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center space-x-2">
                <input type="checkbox" name="remember" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                <span class="text-gray-600">Ingatkan saya</span>
            </label>

            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-indigo-600 hover:underline">
                    Lupa password?
                </a>
            @endif
        </div>

        {{-- Button --}}
        <button 
            type="submit" 
            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg text-sm font-medium transition duration-200"
        >
            Masuk
        </button>
    </form>

    {{-- Footer --}}
    <p class="text-center text-sm text-gray-500 mt-6">
        Ingin masuk sebagai member? 
        <a href="{{ route('login') }}" class="text-indigo-600 hover:underline font-medium">Masuk di sini</a>
    </p>
</div>
@endsection
