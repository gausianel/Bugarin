@extends('layouts.guest')

@section('title', 'Forgot Password')

@section('content')
<div class="w-full max-w-md bg-white rounded-xl shadow-lg border border-gray-200 p-8 mx-4 animate-fade-slide">
    
    <!-- Header -->
    <div class="text-center mb-6">
        <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-indigo-100 flex items-center justify-center">
            <i class="fas fa-user text-indigo-600 text-xl"></i>
        </div>
        <h1 class="text-2xl font-semibold text-gray-800">Forgot Password</h1>
        <p class="text-sm text-gray-500">
            Forgot your password? No problem. Just let us know your email address 
            and we will email you a password reset link that will allow you to choose a new one.
        </p>
    </div>

    <!-- Error messages -->
    @if($errors->any())
        <div class="bg-red-50 text-red-600 text-sm p-3 rounded-md mb-4">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Status message -->
    @if(session('status'))
        <div class="bg-green-50 text-green-600 text-sm p-3 rounded-md mb-4">
            {{ session('status') }}
        </div>
    @endif

    <!-- Forgot Password Form -->
    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block mb-1 text-sm text-gray-600">Email</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                autofocus 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
            >
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                Email Password Reset Link
            </button>
        </div>
    </form>

    <!-- Back to login -->
    <p class="text-center text-sm text-gray-500 mt-6">
        Remember your password? 
        <a href="{{ route('login') }}" class="text-indigo-600 hover:underline font-medium">Login</a>
    </p>
</div>
@endsection
