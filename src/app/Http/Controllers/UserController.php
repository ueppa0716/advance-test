<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use App\Http\Requests\ReserveRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ShopRequest;
use App\Models\User;
use App\Models\Shop;
use App\Models\Reservation;
use App\Models\Location;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Mail\NotificationEmail;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function mypage(ReserveRequest $request)
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
        }

        if ($request->has('like')) {
            $likeList = Like::where('user_id', $user->id)
                ->where('shop_id', $request->shop_id)
                ->first();
            if ($likeList) {
                $likeList->delete();
            }
        }

        if ($request->has('detail')) {
            $this->detail($request);
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
                ->where('shop_id', $request->shop_id)
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
