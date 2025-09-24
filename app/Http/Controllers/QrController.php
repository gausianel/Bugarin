<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\QrToken;

class QrController extends Controller
{
    public function refresh()
    {
        $user = auth()->user();

        $qrData = [
            'token' => Str::random(32),
            'user_id' => $user->id,
        ];

        return response()->json([
            'success' => true,
            'data' => $qrData,
        ]);
    }
}
