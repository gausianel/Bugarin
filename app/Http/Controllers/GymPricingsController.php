<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Models\gym_pricings;
use App\Models\GymPricing; // ✅ singular & PascalCase
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class GymPricingsController extends Controller // atau GymPricingController (lebih umum)
{
    // Tampilkan semua pricing untuk gym tertentu
    public function index($gymId)
    {
        $gym = Gym::with('pricings')->findOrFail($gymId);
        return response()->json($gym->pricings);
    }

    // Tambah pricing baru untuk gym
    public function store(Request $request, $gymId)
    {
        $request->validate([
            'plan_name' => 'required|string|max:100',
            'duration'  => 'required|integer|min:1',
            'price'     => 'required|numeric|min:0',
        ]);

        $pricing = gym_pricings::create([
            'gym_id'     => $gymId,
            'plan_name'  => $request->plan_name,
            'duration'   => $request->duration,
            'price'      => $request->price,
            'created_by' => Auth::id(),
        ]);

        return response()->json($pricing, 201);
    }

    // Update pricing
    public function update(Request $request, $id)
    {
        $pricing = gym_pricings::findOrFail($id);

        $pricing->update($request->only(['plan_name', 'duration', 'price']));

        return response()->json($pricing);
    }

    // Soft delete pricing
    public function destroy($id)
    {
        $pricing = gym_pricings::findOrFail($id);
        $pricing->deleted_by = Auth::id();
        $pricing->save();
        $pricing->delete();

        return response()->json(['message' => 'Pricing deleted successfully']);
    }
}
