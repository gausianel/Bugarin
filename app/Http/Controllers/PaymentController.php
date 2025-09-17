<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Member_Gym;

class PaymentController extends Controller
{
    public function create($member_gym_id)
    {
        $memberGym = Member_Gym::findOrFail($member_gym_id);
        return view('payment.create', compact('memberGym'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_gym_id' => 'required|exists:member_gyms,id',
            'payment_method' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        Payment::create([
            'member_gym_id' => $validated['member_gym_id'],
            'payment_method' => $validated['payment_method'],
            'amount' => $validated['amount'],
            'payment_date' => now(),
            'status' => 'pending',
        ]);

        return redirect()->route('payments.list')->with('success', 'Payment created!');
    }

    public function confirmPayment($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->status = 'confirmed';
        $payment->save();

        return back()->with('success', 'Payment confirmed!');
    }

    public function listPayments()
    {
        $payments = Payment::with('memberGym')->get();
        return view('payment.list', compact('payments'));
    }
}