<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Shop;
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

    public function mypage()
    {
        $user = Auth::user();

        return view('mypage', compact('user'));
    }

    public function shop(Request $request)
    {
        $query = Shop::query();
        $locations = Shop::distinct()->get(['location']);
        $categories = Shop::distinct()->get(['category']);

        $query = $this->getSearchQuery($request, $query);

        $shopInfos = $query->get();

        return view('shop', compact('shopInfos', 'locations', 'categories'));
    }

    private function getSearchQuery($request, $query)
    {
        if (!empty($request->keyword)) {
            $query->where(function ($q) use ($request) {
                $q->where('location', 'like', '%' . $request->keyword . '%')
                    ->orWhere('category', 'like', '%' . $request->keyword . '%')
                    ->orWhere('name', 'like', '%' . $request->keyword . '%');
            });
        }

        if (!empty($request->location) && $request->location != 'All areas') {
            $query->where('location', '=', $request->location);
        }

        if (!empty($request->category) && $request->category != 'All genres') {
            $query->where('category', '=', $request->category);
        }

        return $query;
    }
}
