<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Shop;
use App\Models\Reservation;
use App\Models\Location;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

    public function mypage(Request $request)
    {
        $user = Auth::user();

        if ($request->has('cancel')) {
            $reserveList = Reservation::where('user_id', $user->id)
                ->where('shop_id', $request->shop_id)
                ->first();
            $reserveList->delete();
        }

        if ($request->has('like')) {
            $likeList = Like::where('user_id', $user->id)
                ->where('shop_id', $request->shop_id)
                ->first();
            $likeList->delete();
        }

        if ($request->has('detail')) {
            $this->detail($request);
        }

        if ($request->has('update')) {
            $reserveList = Reservation::where('user_id', $user->id)
                ->where('shop_id', $request->shop_id)
                ->first();
            $reserveList->update([
                'date' => $request->input('date') . ' ' . $request->input('time'),
                'people' => $request->input('people'),
            ]);
            return redirect()->back()->with('message', '予約変更が完了しました');
        }

        $reserveLists = Reservation::where('user_id', $user->id)
            ->with('shop.location', 'shop.category')
            ->get();

        $likeLists = Like::where('user_id', $user->id)
            ->with('shop.location', 'shop.category')
            ->get();

        return view('mypage', compact('user', 'reserveLists', 'likeLists'));
    }

    public function shop(Request $request)
    {
        $user = Auth::user();

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

        $userLikes = collect();

        if (!empty($user)) {
            $userLikes = Like::where('user_id', $user->id)->get();
        }

        if (!empty($user)) {
            $shopInfos->each(function ($shopInfo) use ($userLikes) {
                $shopInfo->liked = $userLikes->contains('shop_id', $shopInfo->id);
            });
        } else {
            $shopInfos->each(function ($shopInfo) {
                $shopInfo->liked = false;
            });
        }

        return view('shop', compact('shopInfos', 'locations', 'categories'));
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

    public function detail(Request $request)
    {
        $user = Auth::user();

        $now = Carbon::now();

        $shopInfo = Shop::find($request->shop_id);

        return view('detail', compact('shopInfo', 'now'));
    }

    public function done(Request $request)
    {
        $user = Auth::user();

        Reservation::create([
            'user_id' => $user->id,
            'shop_id' => $request->shop_id,
            'date' => Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->time),
            'people' => $request->people,
        ]);

        return view('done');
    }
}
