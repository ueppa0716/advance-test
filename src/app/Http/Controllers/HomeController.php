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

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function thanks(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        return view('thanks', compact('email', 'password'));
    }

    public function mypage(ReserveRequest $request)
    {
        $user = Auth::user();
        $now = Carbon::now();

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

    public function shop(Request $request)
    {
        $user = Auth::user();
        if ($user = Auth::user()) {
            if (is_null($user->email_verified_at)) {
                return view('auth.verify');
            }
        }

        if ($request->has('like')) {
            if (empty($user)) {
                return view('auth.login');
            } else {
                $likeList = Like::where('user_id', $user->id)
                    ->where('shop_id', $request->shop_id)
                    ->first();

                if ($likeList) {
                    $likeList->delete();
                } else {
                    $like = Like::create([
                        'user_id' => $user->id,
                        'shop_id' => $request->shop_id,
                    ]);
                }
            }
        }

        $query = Shop::query();
        $locations = Location::all();
        $categories = Category::all();

        $query = $this->getSearchQuery($request, $query);

        $shopInfos = $query->with(['location', 'category'])->get();

        // 店舗ごとの評価点の平均を計算
        $shopRatings = Reservation::select('shop_id', DB::raw('AVG(point) as average_rating'))
            ->groupBy('shop_id')
            ->pluck('average_rating', 'shop_id');

        $userLikes = collect();

        if (!empty($user)) {
            $userLikes = Like::where('user_id', $user->id)->get();
        }

        $shopInfos->each(function ($shopInfo) use ($userLikes, $shopRatings) {
            $shopInfo->liked = $userLikes->contains('shop_id', $shopInfo->id);
            $shopInfo->average_rating = $shopRatings->get($shopInfo->id); // 評価点の平均を設定、nullのままにする
        });

        return view('shop', compact('user', 'shopInfos', 'locations', 'categories'));
    }

    private function getSearchQuery($request, $query)
    {
        $query->join('locations', 'shops.location_id', '=', 'locations.id')
            ->join('categories', 'shops.category_id', '=', 'categories.id')
            ->select('shops.*');

        if (!empty($request->keyword)) {
            $query->where(function ($q) use ($request) {
                $q->where('locations.location', 'like', '%' . $request->keyword . '%')
                    ->orWhere('categories.category', 'like', '%' . $request->keyword . '%')
                    ->orWhere('shops.name', 'like', '%' . $request->keyword . '%');
            });
        }

        if (!empty($request->location) && $request->location != 'All areas') {
            $query->where('locations.id', '=', $request->location);
        }

        if (!empty($request->category) && $request->category != 'All genres') {
            $query->where('categories.id', '=', $request->category);
        }

        return $query;
    }

    public function detail(ReserveRequest $request)
    {
        $user = Auth::user();
        $now = Carbon::now();

        $shopInfo = Shop::find($request->shop_id);

        // 店舗ごとの評価点の平均を計算
        $shopRating = Reservation::where('shop_id', $request->shop_id)
            ->avg('point');

        // 評価点の平均を`shopInfo`に追加
        $shopInfo->average_rating = $shopRating;

        return view('detail', compact('user', 'shopInfo', 'now'));
    }

    public function evaluation(Request $request)
    {
        $user = Auth::user();
        $shop = Shop::find($request->shop_id);
        $now = Carbon::now();

        $reviewLists = Reservation::where('shop_id', $request->shop_id)
            ->with('shop.location', 'shop.category')
            ->where('date', '<', $now)
            ->paginate(5);

        return view('evaluation', compact('user', 'shop', 'reviewLists'));
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

    public function info()
    {
        $user = Auth::user();
        $locations = Location::all();
        $categories = Category::all();
        $userShop = Shop::where('user_id', $user->id)->first();

        // 店舗ごとの評価点の平均を計算
        $shopRating = Reservation::where('shop_id', $userShop->id)
            ->avg('point');

        // 評価点の平均を`shopInfo`に追加
        $userShop->average_rating = $shopRating;

        return view('info', compact('user', 'userShop', 'locations', 'categories'));
    }

    public function update(Request $request)
    {
        // 該当の店舗情報を取得
        $userShop = Shop::where('user_id', $request->user_id)->first();

        if ($request->has('update')) {
            // 現在の画像を削除するためのURLを保存
            $oldPhoto = $userShop->photo;

            // 新しい画像ファイルを取得
            $file = $request->file('photo');

            if ($file) {
                // ファイル名を生成
                $filename = time() . '.' . $file->getClientOriginalExtension();

                // ストレージに新しい画像を保存
                $path = $file->storeAs('images', $filename, 'public');

                // 保存先のURLを取得
                $url = Storage::url($path);

                // 古い画像が存在する場合は削除
                if ($oldPhoto) {
                    // `public`ディスクのURLをパスに変換して削除
                    Storage::disk('public')->delete(str_replace('/storage/', '', $oldPhoto));
                }

                // 新しい画像のURLを設定
                $userShop->photo = $url;
            }

            // 店舗情報を更新
            $userShop->update([
                'name' => $request->input('name') ?? $userShop->name,
                'location_id' => $request->input('location') ?? $userShop->location_id,
                'category_id' => $request->input('category') ?? $userShop->category_id,
                'detail' => $request->input('detail') ?? $userShop->detail,
                'photo' => $userShop->photo, // 新しい画像のURLを保存
            ]);

            return redirect()->back()->with('success', '店舗情報の更新が完了しました');
        }
    }

    public function check()
    {
        $user = Auth::user();
        $userShop = Shop::where('user_id', $user->id)->first();
        $now = Carbon::now();

        $reserveLists = Reservation::where('shop_id', $userShop->id)
            ->with('shop.location', 'shop.category')
            ->where('date', '>', $now)
            ->paginate(5);

        return view('check', compact('user', 'userShop', 'reserveLists'));
    }

    public function mail()
    {
        $user = Auth::user();
        return view('emails.mail', compact('user'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $subject = $request->input('subject');
        $content = $request->input('content');

        $users = User::where('authority', 2)->get(); // authority が 2 のユーザーを取得

        foreach ($users as $user) {
            Mail::to($user->email)->send(new NotificationEmail($subject, $content));
        }

        return redirect()->back()->with('success', 'メールの送信が完了しました');
    }
}
