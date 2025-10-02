@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-xl shadow-md p-6">
    <h2 class="text-lg font-semibold text-gray-700 mb-4">🔑 Test Token Manual</h2>

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-3">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-3">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.token.manual.check') }}">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Masukkan Token</label>
            <input type="text" name="token" class="w-full border rounded p-2" required>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Cek Token
        </button>
    </form>
</div>
@endsection
