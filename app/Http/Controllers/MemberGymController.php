<?php

namespace App\Http\Controllers;

use App\Models\Member_Gym;
use App\Models\Membership_Package;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MemberGymController extends Controller
{
    public function daftarMembership()
    {
        $packages = Membership_Package::all();
        return view('member_gym.daftar_membership', compact('packages'));
    }

    public function paymentOption($package_id)
    {
        $package = Membership_Package::findOrFail($package_id);
        $paymentMethods = ['Transfer', 'E-Wallet', 'Cash'];
        return view('member_gym.payment_option', compact('package', 'paymentMethods'));
    }

   public function storePayment(Request $request)
{
    $validated = $request->validate([
        'package_id'      => 'required|exists:membership_packages,id',
        'payment_method'  => 'required|string',
        'amount'          => 'required|numeric',
    ]);

    $package = Membership_Package::findOrFail($validated['package_id']);

    // Hitung tanggal
    $start = now();
    $end   = $start->copy()
        ->addMonths($package->duration_in_months)
        ->endOfMonth(); // ✅ perbaikan

    // Simpan membership
    $memberGym = Member_Gym::create([
        'member_id'  => auth()->id(),
        'gym_id'     => $package->gym_id,
        'package_id' => $package->id,
        'start_date' => $start->toDateString(),
        'end_date'   => $end,
        'status'     => 'active',
    ]);

    // Simpan payment
    Payment::create([
        'member_gym_id'  => $memberGym->id,
        'payment_method' => $validated['payment_method'],
        'amount'         => $validated['amount'],
        'payment_date'   => now(),
        'status'         => 'pending',
    ]);

    return redirect()
        ->route('member-gym.my-membership')
        ->with('success', 'Payment submitted!');
}


public function viewMyMembership()
{
    $memberships = Member_Gym::where('member_id', auth()->id())
        ->with(['package'])
        ->get();

    return view('member_gym.my_membership', compact('memberships'));
}



    
}