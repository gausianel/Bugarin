@extends('layouts.guest')

@section('title', 'Paket Membership - ' . $gym->name)

@section('content')
<div class="min-h-screen bg-white py-12 px-4 md:px-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8 text-center">
            📦 Paket Membership di {{ $gym->name }}
        </h1>

        @if($packages->isEmpty())
            <p class="text-center text-gray-500">Belum ada paket untuk gym ini.</p>
        @else
            <div class="grid gap-6 md:grid-cols-2">
                @foreach($packages as $package)
                    <div class="p-6 bg-white border rounded-xl shadow hover:shadow-lg transition">
                        <h2 class="text-xl font-semibold">{{ $package->name }}</h2>
                        <p class="text-gray-500">Durasi: {{ $package->duration }}</p>
                        <p class="text-gray-700 mt-2">{{ $package->description ?? 'Tidak ada deskripsi' }}</p>
                        <p class="mt-4 font-bold text-blue-600">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
                        <button class="mt-4 w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                            Pilih Paket Ini
                        </button>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
