<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReserveRequest;
use App\Models\Shop;
use App\Models\Reservation;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function confirm(ReserveRequest $request, $shop_id)
    {
        $user = Auth::user();
        $now = Carbon::now();

        $reserveDate = Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->time);

        $shop_id = $request->shop_id;
        $shopInfo = Shop::find($shop_id);

        // 店舗ごとの評価点の平均を計算
        $shopRating = Feedback::where('shop_id', $shop_id)
            ->where('status', '<>', 0)
            ->avg('point');

        // 評価点の平均を`shopInfo`に追加
        $shopInfo->average_rating = $shopRating;

        $reserveInfo = [
            'user_id' => $user->id,
            'shop_id' => $request->shop_id,
            'date' => $reserveDate,
            'people' => $request->people,
        ];

        return view('confirm', compact('user', 'reserveInfo', 'shopInfo', 'now'));
    }

    public function done(ReserveRequest $request)
    {
        $user = Auth::user();

        $reserveDate = Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->time);

        Reservation::create([
            'user_id' => $user->id,
            'shop_id' => $request->shop_id,
            'date' => $reserveDate,
            'people' => $request->people,
        ]);

        return view('done', compact('user'));
    }

    public function mypageUpdate(ReserveRequest $request)
    {
        $user = Auth::user();
        $now = Carbon::now();

        if (empty($user->email_verified_at)) {
            return view('auth.verify');
        }

        if ($request->has('update')) {
            $reserveList = Reservation::where('user_id', $user->id)
                ->where('shop_id', $request->shop_id)
                ->where('date', '>', $now)
                ->first();
            if ($reserveList) {
                $reserveList->update([
                    'date' => $request->input('date') . ' ' . $request->input('time'),
                    'people' => $request->input('people'),
                ]);
                return redirect()->back()->with('message', '予約変更が完了しました');
            }
        }
    }

    public function mypageDelete(ReserveRequest $request)
    {
        $user = Auth::user();
        $now = Carbon::now();

        if (empty($user->email_verified_at)) {
            return view('auth.verify');
        }

        if ($request->has('cancel')) {
            $reserveList = Reservation::where('user_id', $user->id)
                ->where('shop_id', $request->shop_id)
                ->where('date', '>', $now)
                ->first();
            if ($reserveList) {
                $reserveList->delete();
            }
            return redirect()->back();
        }
    }
}
