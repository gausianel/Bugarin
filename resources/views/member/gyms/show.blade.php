@extends('layouts.guest')

@section('title', 'Detail Gym')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 md:px-8">
    <div class="max-w-3xl mx-auto bg-white shadow-xl rounded-2xl overflow-hidden">

        <!-- Header -->
        <div class="relative">
            <img src="{{ $gym->url_photo ? asset('storage/'.$gym->url_photo) : asset('images/default-gym.jpg') }}" 
                 alt="Foto {{ $gym->name }}"
                 class="w-full h-64 object-cover">
            <div class="absolute inset-0 bg-black/40 flex items-end p-6">
                <h1 class="text-3xl md:text-4xl font-extrabold text-white drop-shadow-lg">
                    {{ $gym->name }}
                </h1>
            </div>
        </div>

        <!-- Content -->
        <div class="p-8 md:p-10">
            <!-- Tombol Back -->
            <div class="mb-6">
                <a href="{{ route('gyms.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 rounded-lg hover:bg-gray-200 transition">
                    ← Kembali
                </a>
            </div>

            <!-- Info -->
            <div class="space-y-4">
                <!-- Alamat -->
                <p class="text-gray-600 flex items-center gap-2">
                    📍 <span>{{ $gym->address }}</span>
                </p>

                <!-- Deskripsi -->
                @if($gym->deskripsi)
                    <p class="text-gray-700 leading-relaxed">
                        {{ $gym->deskripsi }}
                    </p>
                @else
                    <p class="text-gray-400 italic">Belum ada deskripsi untuk gym ini.</p>
                @endif
            </div>

            <!-- Divider -->
            <hr class="my-8 border-gray-200">

        <!-- Tombol Pilih Gym -->
    <form action="{{ route('member.profile.chooseGym') }}" method="POST">
        @csrf
        <input type="hidden" name="gym_id" value="{{ $gym->id }}">
        a
        <button type="submit"
            class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold py-3 rounded-xl shadow-lg hover:from-blue-600 hover:to-blue-700 transition transform hover:-translate-y-0.5">
            Pilih Gym Ini
        </button>
    </form>




            <!-- Tombol Edit (jika admin) -->
            
        </div>
    </div>
</div>
@endsection
