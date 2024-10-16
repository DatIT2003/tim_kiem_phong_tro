<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Payment;
use App\Motelroom;
use Stripe\Stripe;
use Stripe\Charge;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function showPaymentForm($id)
    {
        $motelroom = Motelroom::findOrFail($id);

        return view('payment.form', compact('motelroom'));
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'stripeToken'   => 'required',
            'motelroom_id'  => 'required|exists:motelrooms,id',
        ]);

        $motelroom = Motelroom::findOrFail($request->motelroom_id);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $charge = Charge::create([
                'amount'        => $motelroom->price * 100,
                'currency'      => 'usd',
                'description'   => 'Payment for motel room: ' . $motelroom->title,
                'source'        => $request->stripeToken,
                'metadata'      => [
                    'order_id' => $motelroom->id,
                ],
            ]);

            Payment::create([
                'user_id'           => Auth::id(),
                'motelroom_id'      => $motelroom->id,
                'stripe_payment_id' => $charge->id,
                'amount'            => $motelroom->price,
                'status'            => $charge->status,
            ]);

            $motelroom->update(['is_rented' => 1]);

            return redirect()->route('payment.success')->with('success', 'Payment successful!');
        } catch (\Exception $e) {
            return back()->withErrors('Error! ' . $e->getMessage());
        }
    }

    public function paymentSuccess()
    {
        return view('payments.success');
    }
}
