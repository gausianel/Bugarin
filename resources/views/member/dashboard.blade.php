@extends('layouts.app')

@section('title', 'Dashboard Member - Bugarin')

@section('content')
<div class="min-h-screen bg-gray-100 flex flex-col">

    <header class="w-full bg-gray-100 px-6 py-4 flex justify-between items-center sticky top-0 z-20 shadow">
    <h1 class="text-2xl font-bold text-indigo-600 flex items-center gap-2">Bugarin</h1>

    <div class="flex items-center gap-6">
        <!-- Ikon Profil -->
        <a href="{{ route('member.profile.update') }}" 
           class="text-gray-600 hover:text-indigo-600 transition"
           title="Profile">
            <i class="fa-solid fa-user text-2xl"></i>
        </a>

        <!-- Ikon Logout -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" 
                class="text-gray-600 hover:text-red-600 transition"
                title="Logout">
                <i class="fa-solid fa-right-from-bracket text-2xl"></i>
            </button>
        </form>
    </div>
</header>



    <!-- Content Wrapper (biar mirip tampilan aplikasi HP) -->
    <main class="flex-1 flex items-center justify-center p-4 bg-gray-100">
        <div class="w-full max-w-md mx-auto space-y-6 pb-20">

            {{-- Membership --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm hover:shadow-md transition">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">🎟️ Status Membership</h2>
                <div class="mt-3 text-gray-600 space-y-1 text-sm">

                    @php
                        $membership = auth()->user()
                            ->memberships()
                            ->with('package')
                            ->latest('end_date')
                            ->first();
                    @endphp
                   

                    @if($membership)
                    <p>Paket: 
                        <span class="font-bold text-indigo-600">
                            {{ $membership->package->name ?? '-' }}
                        </span>
                    </p>
                    <p>Durasi: 
                        <span class="font-bold">
                            {{ $membership->package ? $membership->package->duration_in_months.' bulan' : '-' }}
                        </span>
                    </p>
                    <p>Berlaku sampai: 
                        <span class="font-bold">
                            {{ \Carbon\Carbon::parse($membership->end_date) ->format('d F Y') }}
                        </span>
                    </p>
                    <p class="mt-2 {{ $membership->status === 'active' ? 'text-green-600' : 'text-red-600' }} font-semibold">
                        {{ $membership->status === 'active' ? '✅ Aktif' : '❌ Tidak Aktif' }}
                    </p>
                @else
                    <p class="text-gray-500">Belum ada membership aktif</p>
                @endif


                </div>
            </div>

            {{-- Jadwal Kelas --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm hover:shadow-md transition">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">📅 Jadwal Kelas Anda</h2>
                <ul class="mt-3 divide-y divide-gray-200 text-gray-700 text-sm">
                    @forelse($classes as $class)
                        <li class="flex justify-between py-2">
                            <span>{{ $class->name }}</span>
                            <span class="text-gray-500">{{ $class->day }} - {{ $class->time }}</span>
                        </li>
                    @empty
                        <li class="py-2 text-gray-500">Mohon maaf fitur ini belum tersedia</li>
                    @endforelse
                </ul>
            </div>

            {{-- Riwayat Check-in --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm hover:shadow-md transition">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">📋 Riwayat Check-in</h2>
                <div class="mt-3 overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-indigo-600 text-white">
                                <th class="px-3 py-2 text-left font-medium">Tanggal</th>
                                <th class="px-3 py-2 text-left font-medium">Jam</th>
                                <th class="px-3 py-2 text-left font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($attendances as $attendance)
                                <tr class="hover:bg-indigo-50 transition">
                                    <td class="px-3 py-2">{{ \Carbon\Carbon::parse($attendance->date)->format('d F Y') }}</td>
                                    <td class="px-3 py-2">{{ $attendance->time }}</td>
                                    <td class="px-3 py-2 {{ $attendance->status == 'Hadir' ? 'text-green-600' : 'text-red-600' }} font-semibold">
                                        {{ $attendance->status }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-3 py-2 text-center text-gray-500">Belum ada riwayat check-in</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Informasi Gym --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm hover:shadow-md transition">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">ℹ️ Informasi Gym</h2>
                <ul class="mt-3 list-disc list-inside text-gray-600 space-y-1 text-sm">
                    @forelse($announcements as $info)
                        <li>{{ $info->title }}: <span class="font-semibold">{{ $info->content }}</span></li>
                    @empty
                        <li class="text-gray-500">Belum ada informasi terbaru</li>
                    @endforelse
                </ul>
            </div>

            {{-- Floating QR Button (tengah bawah) --}}
            <button id="openQrModal"
                class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-indigo-600 text-white p-3 rounded-full shadow-lg hover:bg-indigo-700 transition z-30">
                <i class="fa-solid fa-qrcode text-2xl"></i>
            </button>


        </div> {{-- penutup max-w-md --}}
        </main>
        </div>

        {{-- Modal QR (DIPINDAH KE LUAR agar tetap center) --}}
        <div id="qrModal" 
            class="fixed inset-0 bg-black bg-opacity-50 hidden z-40 flex items-center justify-center">
            <div class="bg-white p-6 rounded-2xl shadow-lg w-[90%] max-w-sm text-center relative mx-auto">
            
            <!-- Close Button -->
            <button id="closeQrModal" 
                class="absolute top-3 right-3 text-gray-500 hover:text-red-600 text-lg">
                ✖
            </button>

            <h2 class="text-lg font-semibold text-gray-800 mb-3">🎫 QR Check-in Anda</h2>
            <div id="qrcode" class="bg-gray-100 p-3 rounded-lg inline-block"></div>

            <!-- TOKEN -->
            <p id="tokenText" class="font-mono text-base text-indigo-600 tracking-wider mt-3">
                {{ $token }}
            </p>
            <button onclick="copyToken()" 
                class="mt-2 px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm text-gray-700 transition">
                📋 Copy
            </button>

            <p class="mt-3 text-gray-600 text-sm">
                Scan QR ini di gym untuk check-in.<br>
                Token bisa dipakai sebagai alternatif manual.
            </p>
        </div>
    </div>


            </div>
        </main>

    </div>
    @endsection


@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    let currentToken = "{{ $token }}";

        // 🔹 Buka & Tutup Modal
    const modal = document.getElementById("qrModal");
    document.getElementById("openQrModal").addEventListener("click", () => {
        modal.classList.remove("hidden");
        modal.classList.add("flex");
    });
    document.getElementById("closeQrModal").addEventListener("click", () => {
        modal.classList.add("hidden");
        modal.classList.remove("flex");
    });


    // 🔹 Generate QR Code
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

    // 🔹 Refresh QR & Token
    function refreshQr() {
        console.log("Refreshing QR...");
       

        fetch("{{ route('member.qr.refresh') }}")
            .then(res => res.json())
            .then(data => {
                console.log("New Token:", data.token);
                currentToken = data.token;

                // update QR
                generateQr(currentToken);

                // update teks token
                document.getElementById("tokenText").innerText = currentToken;

                showToast("🔄 QR Code diperbarui!");
            })
            .catch(err => console.error("Error refreshing QR:", err))
            .finally(() => {
                btn.disabled = false;
                btn.innerText = "🔄 Refresh QR Code";
            });
    }

    // 🔹 Copy Token
    function copyToken() {
        const tokenElement = document.getElementById("tokenText");
        const tokenText = tokenElement ? tokenElement.textContent.trim() : "";

        if (!tokenText) {
            console.error("Token tidak ditemukan!");
            return;
        }

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(tokenText).then(() => {
                showToast("✅ Token berhasil dicopy!");
            }).catch(err => {
                console.error("Gagal copy:", err);
            });
        } else {
            // fallback
            const textarea = document.createElement("textarea");
            textarea.value = tokenText;
            textarea.style.position = "fixed";
            textarea.style.opacity = 0;
            document.body.appendChild(textarea);
            textarea.focus();
            textarea.select();
            try {
                document.execCommand("copy");
                showToast("✅ Token berhasil dicopy!");
            } catch (err) {
                console.error("Fallback gagal copy:", err);
            }
            document.body.removeChild(textarea);
        }
    }

    // 🔹 Toast Notif
    function showToast(message) {
        const toast = document.createElement("div");
        toast.innerText = message;
        toast.className = "fixed bottom-5 right-5 bg-indigo-600 text-white px-4 py-2 rounded-lg shadow-lg text-sm opacity-0 transition-opacity duration-300";
        document.body.appendChild(toast);

        // fade in
        setTimeout(() => {
            toast.classList.add("opacity-100");
        }, 50);

        // fade out + remove
        setTimeout(() => {
            toast.classList.remove("opacity-100");
            setTimeout(() => toast.remove(), 300);
        }, 2000);
    }

    // 🔹 Generate pertama kali
    generateQr(currentToken);

    // 🔹 Auto refresh tiap 30 detik
    setInterval(refreshQr, 30000);
</script>
@endpush
