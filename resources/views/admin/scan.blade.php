@extends('layouts.guest')

@section('title', 'Scan QR Member')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen">
    <h1 class="text-2xl font-bold mb-6">📷 Scan QR Member</h1>

    <div id="reader" style="width: 300px;"></div>
    <p id="result" class="mt-4 text-lg text-green-600"></p>
</div>

<script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
<script>
    function onScanSuccess(decodedText, decodedResult) {
        document.getElementById("result").innerText = "QR: " + decodedText;

        // DEBUG route
        console.log("URL cek QR:", "{{ route('scan.qr.check') }}");

        fetch("{{ route('scan.qr.check') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ token: decodedText })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
        })
        .catch(err => console.error("Error:", err));
    }

    let scanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
    scanner.render(onScanSuccess);
</script>
@endsection
