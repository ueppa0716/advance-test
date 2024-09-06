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

class ManagerController extends Controller
{
    public function manager()
    {
        $user = Auth::user();
        return view('manager', compact('user'));
    }

    public function admin(RegisterRequest $request)
    {
        if ($request->has('owner')) {
            $data = $request->only(['name', 'email', 'password']);
            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'authority' => 1,
                'password' => Hash::make($data['password']),
            ]);
            return redirect()->back()->with('success', '店舗代表者の登録が完了しました');
        }
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
