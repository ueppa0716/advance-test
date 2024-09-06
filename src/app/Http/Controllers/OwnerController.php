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

class OwnerController extends Controller
{
    public function owner()
    {
        $user = Auth::user();
        $locations = Location::all();
        $categories = Category::all();
        $userShop = Shop::where('user_id', $user->id)->get();

        return view('owner', compact('user', 'locations', 'categories', 'userShop'));
    }

    public function open(ShopRequest $request)
    {
        if ($request->has('shop')) {
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

            $data = $request->only([
                'name',
                'location',
                'category',
                'user_id',
                'detail'
            ]);
            Shop::create([
                'name' => $data['name'],
                'location_id' => $data['location'],
                'category_id' => $data['category'],
                'user_id' => $data['user_id'],
                'detail' => $data['detail'],
                'photo' => $url, // 画像のURLを保存
            ]);

            return redirect()->back()->with('success', '店舗情報の登録が完了しました');
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
}
