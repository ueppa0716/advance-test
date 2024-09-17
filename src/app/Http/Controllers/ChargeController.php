<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Carbon\Carbon;

class ChargeController extends Controller
{
    public function pay(Request $request)
    {
        $now = Carbon::now();
        $reserveList = Reservation::where('id', $request->reservation_id)->first();
        $reserveList->update([
            'payment' => $now,
        ]);

        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $customer = Customer::create(array(
                'email' => $request->stripeEmail,
                'source' => $request->stripeToken
            ));

            $charge = Charge::create(array(
                'customer' => $customer->id,
                'amount' => 1000,
                'currency' => 'jpy'
            ));

            return back()->with('message', '支払が完了しました');;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}
