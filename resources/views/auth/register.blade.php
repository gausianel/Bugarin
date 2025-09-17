@extends('layouts.guest')

@section('title', 'Register')

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
        animation: fadeSlideUp 0.8s ease-out forwards
    }
</style>


<div class="w-full max-w-md bg-white rounded-xl shadow-lg border border-gray-200 p-8 mx-4 animate-fade-slide">

<!-- Header -->
<div class="text-center mb-6">
    <div class="w-14 h-14 mx-auto mb-3 rounded-full
    flex items-center justify-center">
    <h1 class="text-4xl font-extrabold text-indigo-600">Bugarin</h1>
    <i class="fas fa-user-plus text-indigo-600 text-xl"></i>
    </div>
    <h1 class="text-2xl font-semibold text-black">Daftar sebagai Member</h1>
    <p class="text-sm text-gray-500 mt-2">Bergabunglah dengan kami dan mulai perjalanan Anda bersama
    Bugarin</p>
</div>

<!-- Error Messages -->
@if($errors->any())
<div class="bg-red-50 text-red-600 text-sm p-3 rounded-md mb-4">
    @foreach($errors->all() as $error)
        <p>{{ $error }}</p>
    @endforeach
</div>
@endif

<!-- Status Message -->
@if(session('status'))
<div class="bg-green-50 text-green-600 text-sm p-3 rounded-md mb-4">
    {{ session('status') }}
</div>
@endif

<!-- Registration Form -->
<form method="POST" action="{{ route('register') }}"
class="space-y-5">
    @csrf

    <!-- Name -->
    <div>
        <label for="name" class="block mb-1 text-sm
        text-gray-600">Nama</label>
        <input 
            type="text" 
            id="name" 
            name="name" 
            value="{{ old('name') }}"
            required 
            autofocus
            class="w-full px-4 py-2 text-sm rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500"
        >
    </div>

    <!-- Email -->
    <div>
        <label for="email" class="block mb-1 text-sm
        text-gray-600">Email</label>
        <input 
            type="email" 
            id="email" 
            name="email" 
            value="{{ old('email') }}"
            required 
            class="w-full px-4 py-2 text-sm rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500"
        >
    </div>

    <!-- Password -->
    <div>
        <label for="password" class="block mb-1 text-sm
        text-gray-600">Password</label>
        <input
        type="password"
        id="password"
        name="password"
        value="{{ old('password') }}"
        required
        class="w-full px-4 py-2 text-sm rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
    </div>

    <!-- Confirm Password -->
    <div>
        <label for="password_confirmation" class="block mb-1 text-sm
        text-gray-600">Confirm Password</label>
        <input 
            type="password" 
            id="password_confirmation" 
            name="password_confirmation" 
            required 
            class="w-full px-4 py-2 text-sm rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500"
        >
    </div>

    <!-- Submit Button -->
    <div>
        <button type="submit" class="w-full bg-blue-600 text-white
        px-4 py-2 rounded-lg hover:bg-blue-700 focus:outline-none
        focus:ring-2 focus:ring-indigo-500">
            Daftar
        </button>
    </div>
</form>

<!-- Footer -->
<p class="text-center text-sm text-gray-500 mt-6">
    Sudah memiliki akun? 
    <a href="{{ route('login') }}" class="text-indigo-600 hover:underline
    font-medium">Masuk</a>
</p>
</div>
@endsection