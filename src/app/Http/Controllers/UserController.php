<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Requests\FeedbackRequest;
use App\Http\Requests\ReserveRequest;
use App\Models\Reservation;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

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



    // Pro入会テストにて追加
    public function feedback($shop_id)
    {
        $user = Auth::user();
        $now = Carbon::now();

        $shopInfo = Shop::find($shop_id);

        // 店舗ごとの評価点の平均を計算
        $shopRating = Feedback::where('shop_id', $shop_id)
            ->where('status', '<>', 0)
            ->avg('point');

        // 評価点の平均を`shopInfo`に追加
        $shopInfo->average_rating = $shopRating;

        return view('feedback', compact('user', 'shopInfo', 'now'));
    }

    public function send(FeedbackRequest $request, $shop_id)
    {
        $user = Auth::user();
        $shopInfo = Shop::find($shop_id);
        if (Feedback::where('shop_id', $request->shop_id)
            ->where('status', 1)
            ->where('user_id', $user->id)
            ->first()
        ) {
            return redirect()->back()->with('message', 'すでに口コミ投稿済です');
        };

        // 画像ファイルを取得
        $file = $request->file('photo');

        if ($file) {
            // ファイル名を生成
            $filename = time() . '.' . $file->getClientOriginalExtension();

            // ストレージに保存
            $path = $file->storeAs('images', $filename, 'public');

            // 保存先のURLを取得
            $url = Storage::url($path);
        } else {
            $url = null; // 画像がアップロードされていない場合の処理
        }
        Feedback::create([
            'user_id' => $user->id,
            'shop_id' => $request->shop_id,
            'comment' => $request->comment,
            'point' => $request->point,
            'photo' => $url, // 画像のURLを保存
            'status' => 1,
        ]);
        return redirect('/detail/' . $shopInfo->id);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if ($request->has('update')) {
            Feedback::where('id', $request->feedback_id)
                ->update([
                    'comment' => $request->comment,
                    'point' => $request->point,
                ]);
        }

        if ($request->has('delete')) {
            Feedback::where('id', $request->feedback_id)
                ->update([
                    'status' => 0,
                ]);
            return redirect()->back();
        }

        return redirect()->back();
    }
}
