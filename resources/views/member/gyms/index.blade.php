@extends('layouts.app')

@section('title', 'List Gym')

@section('content')
<div class="min-h-screen bg--50 py-12 px-4 md:px-12">

    <!-- Title -->
    <h1 class="text-3xl md:text-5xl font-extrabold mb-12 text-gray-800 text-center">
         <span class="text-indigo-600">Temukan Gym</span> Favorit Anda
    </h1>

    <!-- Search -->
    <form href="{{ route('gyms.index') }}" 
          class="mb-12 max-w-2xl mx-auto flex items-center bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
        <input 
            type="text"
            name="search"
            placeholder="🔍 Cari gym berdasarkan nama atau alamat..."
            value="{{ request('search') }}"
            class="w-full px-5 py-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm md:text-base"
        >
        <button type="submit" 
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 font-semibold transition">
            Cari
        </button>
    </form>

    <!-- List Gym -->
    <div class="grid sm:grid-cols-3 lg:grid-cols-2 gap-10">
        @forelse($gyms as $gym)
            <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition duration-300 flex flex-col overflow-hidden border border-gray-100">
                <!-- Foto gym -->
                <div class="relative">
                    <img src="{{ $gym->url_photo ? asset('storage/' . $gym->url_photo) : asset('images/default-gym.jpg') }}" 
                        alt="{{ $gym->name }}" 
                        class="w-full h-52 object-cover">
                    
                    <!-- Rating -->
                    <span class="absolute top-4 right-4 bg-white/95 text-xs px-3 py-1 rounded-full shadow text-gray-700 font-semibold">
                        ⭐ {{ rand(4,5) }}.{{ rand(0,9) }}/5
                    </span>
                </div>

                <!-- Info gym -->
                <div class="p-6 flex flex-col flex-1">
                    <h2 class="text-lg md:text-xl font-bold text-gray-900 mb-2 truncate">
                        {{ $gym->name }}
                    </h2>

                    <p class="text-sm text-gray-500 flex items-center gap-1 mb-3 truncate">
                        📍 <span>{{ $gym->address }}</span>
                    </p>

                    <p class="text-gray-600 text-sm line-clamp-3 mb-6 flex-1">
                        {{ $gym->deskripsi }}
                    </p>

                    <!-- Button -->
                    <a href="{{ route('gyms.show', $gym->id) }}" 
                       class="mt-auto bg-gradient-to-r from-blue-600 to-purple-600 text-white text-center py-2.5 rounded-xl font-medium hover:from-blue-700 hover:to-purple-700 transition shadow-md">
                        Lihat Detail
                    </a>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-500 col-span-full">Belum ada gym yang tersedia.</p>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-14 flex justify-center">
        {{ $gyms->links() }}
    </div>
</div>
@endsection
