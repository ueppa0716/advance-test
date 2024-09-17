<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use App\Http\Requests\ReserveRequest;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserController extends Controller
{
    public function mypage(ReserveRequest $request)
    {
        $user = Auth::user();
        $now = Carbon::now();

        if (empty($user->email_verified_at)) {
            return view('auth.verify');
        }

        if ($request->has('detail')) {
            $this->detail($request);
        }

        $reserveLists = Reservation::where('user_id', $user->id)
            ->with('shop.location', 'shop.category')
            ->where('date', '>', $now)
            ->get();

        // サブクエリでショップの評価点の平均を計算
        $shopRatings = Reservation::select('shop_id', DB::raw('AVG(point) as average_rating'))
            ->groupBy('shop_id')
            ->pluck('average_rating', 'shop_id');

        $likeLists = Like::where('user_id', $user->id)
            ->with('shop.location', 'shop.category')
            ->get()
            ->map(function ($like) use ($shopRatings) {
                // 平均評価点を設定する
                $like->shop->average_rating = $shopRatings->get($like->shop_id, 0);
                return $like;
            });

        return view('mypage', compact('user', 'reserveLists', 'likeLists'));
    }

    public function reserve(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();

        $reserveLists = Reservation::where('user_id', $user->id)
            ->with('shop.location', 'shop.category')
            ->where('date', '<', $now)
            ->paginate(5);

        return view('reservation', compact('user', 'reserveLists'));
    }

    public function review(Request $request)
    {
        $user = Auth::user();

        if ($request) {
            $reserveList = Reservation::where('user_id', $user->id)
                ->where('id', $request->reservation_id)
                ->first();
            if (!empty($reserveList->point)) {
                $reserveList->update([
                    'point' => $request->point,
                    'comment' => $request->comment,
                ]);
                return redirect()->back()->with('message', 'レビューを更新しました');
            } else {
                $reserveList->update([
                    'point' => $request->point,
                    'comment' => $request->comment,
                ]);
                return redirect()->back()->with('message', 'レビューありがとうございます。');
            }
        }
    }
}
