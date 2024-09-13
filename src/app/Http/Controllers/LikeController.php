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

class LikeController extends Controller
{
    public function shopLike(Request $request)
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
                return redirect()->back();
            }
        }
    }

    public function mypageLike(Request $request)
    {
        $user = Auth::user();
        if ($request->has('like')) {
            $likeList = Like::where('user_id', $user->id)
                ->where('shop_id', $request->shop_id)
                ->first();
            if ($likeList) {
                $likeList->delete();
            }
            return redirect()->back();
        }
    }
}
