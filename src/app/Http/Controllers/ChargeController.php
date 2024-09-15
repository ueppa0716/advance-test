<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;

class ChargeController extends Controller
{
    public function stripe(Request $request)
    {
        $user = Auth::user();
        return view('pay', compact('user'));
    }

    /*単発決済用のコード*/
    public function pay(Request $request)
    {
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
