<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\QrToken;
use App\Models\User;

class QrController extends Controller
{
public function refresh(Request $request)
{
    $user = auth()->user();
    $token = Str::random(32);

    QrToken::create([
        'token'      => $token,
        'user_id'    => $user->id,
        // class_id ga dipake lagi
        'expires_at' => now()->addMinutes(5),
    ]);

    return response()->json([
        'success' => true,
        'data' => [
            'token'   => $token,
            'user_id' => $user->id,
        ]
    ]);
}



}
