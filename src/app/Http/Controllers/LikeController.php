<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
