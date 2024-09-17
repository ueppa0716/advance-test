<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Reservation;
use App\Models\Location;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ShopController extends Controller
{
    public function shop(Request $request)
    {
        $user = Auth::user();
        if ($user = Auth::user()) {
            if (is_null($user->email_verified_at)) {
                return view('auth.verify');
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

    public function detail($shop_id)
    {
        $user = Auth::user();
        $now = Carbon::now();

        $shopInfo = Shop::find($shop_id);

        // 店舗ごとの評価点の平均を計算
        $shopRating = Reservation::where('shop_id', $shop_id)
            ->avg('point');

        // 評価点の平均を`shopInfo`に追加
        $shopInfo->average_rating = $shopRating;

        return view('detail', compact('user', 'shopInfo', 'now'));
    }

    public function evaluation($shop_id)
    {
        $user = Auth::user();
        $shop = Shop::find($shop_id);
        $now = Carbon::now();

        $reviewLists = Reservation::where('shop_id', $shop_id)
            ->with('shop.location', 'shop.category')
            ->where('date', '<', $now)
            ->paginate(5);

        return view('evaluation', compact('user', 'shop', 'reviewLists'));
    }
}
