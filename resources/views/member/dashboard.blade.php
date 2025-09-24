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

        {{-- Membership --}}
        <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1 flex flex-col justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">🎟️ Status Membership</h2>
                <div class="mt-3 text-gray-600 space-y-1">
                    @php
                        $membership = auth()->user()->memberships()->with('package')->latest('end_date')->first();
                    @endphp

                    @if($membership)
                        <p>Paket: <span class="font-bold text-indigo-600">{{ $membership->package->name ?? '-' }}</span></p>
                        <p>Berlaku sampai: <span class="font-bold">{{ \Carbon\Carbon::parse($membership->end_date)->format('d F Y') }}</span></p>
                        <p class="mt-2 {{ $membership->status === 'active' ? 'text-green-600' : 'text-red-600' }} font-semibold">
                            {{ $membership->status === 'active' ? '✅ Aktif' : '❌ Tidak Aktif' }}
                        </p>
                    @else
                        <p class="text-gray-500">Belum ada membership aktif</p>
                    @endif
                </div>
            </div>
        </div>


        {{-- Jadwal Kelas --}}
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
                        <li class="py-3 text-gray-500">Mohon maaf fitur ini belum tersedia</li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- Riwayat Check-in --}}
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
        </div>

        {{-- Informasi Gym --}}
        <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1 flex flex-col justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">ℹ️ Informasi Gym</h2>
                <ul class="mt-3 list-disc list-inside text-gray-600 space-y-2">
                    @forelse($announcements as $info)
                        <li>{{ $info->title }}: <span class="font-semibold">{{ $info->content }}</span></li>
                    @empty
                        <li class="text-gray-500">Belum ada informasi terbaru</li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- QR Check-in --}}
        <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1 flex flex-col items-center justify-center">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">🎫 QR Check-in Anda</h2>
            
            <!-- QR Code -->
            <div id="qrcode" class="bg-gray-100 p-4 rounded-lg"></div>
            <p class="mt-2 text-gray-600 text-center">Scan QR ini di gym untuk melakukan check-in.</p>

            <!-- Tombol Refresh Manual -->
            <button 
                id="refreshBtn"
                onclick="refreshQr()" 
                class="mt-4 px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg font-medium shadow-md transition">
                🔄 Refresh QR Code
            </button>

            
        </div>

    </main>
</div>
@endsection

@push('scripts')
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

    function refreshQr() {
        console.log("Refreshing QR...");
        const btn = document.getElementById("refreshBtn");
        btn.disabled = true;
        btn.innerText = "⏳ Refreshing...";

        fetch("{{ route('member.qr.refresh') }}")
            .then(res => res.json())
            .then(data => {
                console.log("New Token:", data.token);
                currentToken = data.token;
                generateQr(currentToken);
            })
            .catch(err => console.error("Error refreshing QR:", err))
            .finally(() => {
                btn.disabled = false;
                btn.innerText = "🔄 Refresh QR Code";
            });
    }

    generateQr(currentToken);

    // Auto refresh tiap 30 detik
    setInterval(refreshQr, 30000);
</script>
@endpush
