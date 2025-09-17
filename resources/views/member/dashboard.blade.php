@extends('layouts.guest')

@section('title', 'Dashboard Member - Bugarin')

@section('content')
<div class="min-h-screen bg-gray-100 flex flex-col">

    <!-- Header -->
    <header class="w-full bg-gray-100 px-6 py-4 flex justify-between items-center sticky top-0 z-20">
        <h1 class="text-2xl font-bold text-indigo-600 flex items-center gap-2">Bugarin</h1>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl font-medium shadow-md transition">
                Logout
            </button>
        </form>
    </header>

    <!-- Content -->
    <main class="flex-1 p-6 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 gap-6">

        <!-- Membership Status -->
        <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1 flex flex-col justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">🎟️ Status Membership</h2>
                <div class="mt-3 text-gray-600 space-y-1">
                    @if($membership)
                        <p>Paket: <span class="font-bold text-indigo-600">{{ $membership->package->name ?? '-' }}</span></p>
                        <p>Berlaku sampai: <span class="font-bold">{{ \Carbon\Carbon::parse($membership->end_date)->format('d F Y') }}</span></p>
                        <p class="mt-2 {{ $membership->is_active ? 'text-green-600' : 'text-red-600' }} font-semibold">
                            {{ $membership->is_active ? '✅ Aktif' : '❌ Tidak Aktif' }}
                        </p>
                    @else
                        <p class="text-gray-500">Belum ada membership aktif</p>
                    @endif
                </div>
            </div>
            <a href="{{ route('member.membership') }}" class="mt-4 inline-block px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg font-medium text-sm text-center transition">
                Lihat Detail Membership
            </a>
        </div>

        <!-- Jadwal Kelas -->
        <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1 flex flex-col justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">📅 Jadwal Kelas Anda</h2>
                <ul class="mt-4 divide-y divide-gray-200 text-gray-700">
                    @forelse($classes as $class)
                        <li class="flex justify-between py-3">
                            <span>{{ $class->name }}</span>
                            <span class="text-gray-500">{{ $class->day }} - {{ $class->time }}</span>
                        </li>
                    @empty
                        <li class="py-3 text-gray-500">Belum ada jadwal kelas</li>
                    @endforelse
                </ul>
            </div>
            <a href="{{ route('member.classes') }}" class="mt-4 inline-block px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg font-medium text-sm text-center transition">
                Lihat Semua Kelas
            </a>
        </div>

        <!-- Riwayat Check-in -->
        <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1 flex flex-col justify-between md:col-span-2 xl:col-span-1">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">📋 Riwayat Check-in</h2>
                <div class="mt-4 overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-indigo-600 text-white">
                                <th class="px-4 py-3 text-left font-medium">Tanggal</th>
                                <th class="px-4 py-3 text-left font-medium">Jam</th>
                                <th class="px-4 py-3 text-left font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($attendances as $attendance)
                                <tr class="hover:bg-indigo-50 transition">
                                    <td class="px-4 py-3">{{ \Carbon\Carbon::parse($attendance->date)->format('d F Y') }}</td>
                                    <td class="px-4 py-3">{{ $attendance->time }}</td>
                                    <td class="px-4 py-3 {{ $attendance->status == 'Hadir' ? 'text-green-600' : 'text-red-600' }} font-semibold">
                                        {{ $attendance->status }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-center text-gray-500">Belum ada riwayat check-in</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <a href="{{ route('member.attendance') }}" class="mt-4 inline-block px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg font-medium text-sm text-center transition">
                Lihat Riwayat Lengkap
            </a>
        </div>

        <!-- Informasi Gym -->
        <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1 flex flex-col justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">ℹ️ Informasi Gym</h2>
                <ul class="mt-3 list-disc list-inside text-gray-600 space-y-2">
                    @forelse($announcements as $info)
                        <li>{{ $info->title }}: <span class="font-semibold">{{ $info->description }}</span></li>
                    @empty
                        <li class="text-gray-500">Belum ada informasi terbaru</li>
                    @endforelse
                </ul>
            </div>
            <a href="{{ route('member.info') }}" class="mt-4 inline-block px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg font-medium text-sm text-center transition">
                Lihat Info Lengkap
            </a>
        </div>

    <!-- Generate QR -->
    <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1 flex flex-col items-center justify-center">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">🎫 QR Check-in Anda</h2>
        <div id="qrcode" class="bg-gray-100 p-4 rounded-lg"></div>
        <p class="mt-2 text-gray-600 text-center">Scan QR ini di gym untuk melakukan check-in.</p>
    </div>

    <!-- QRCode.js + refresh tiap 30 detik -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        let currentToken = "{{ $token }}";

        function generateQr(token) {
            document.getElementById("qrcode").innerHTML = "";
            new QRCode(document.getElementById("qrcode"), {
                text: token,
                width: 200,
                height: 200,
                colorDark : "#4F46E5",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
        }

        generateQr(currentToken);

        setInterval(() => {
            fetch("{{ route('member.qr.refresh') }}")
                .then(res => res.json())
                .then(data => {
                    currentToken = data.token;
                    generateQr(currentToken);
                });
        }, 30000); // refresh tiap 30 detik
</script>

@endsection
