<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\RegisterRequest;
use App\Models\Shop;
use App\Models\Location;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/thanks';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function register(RegisterRequest $request)
    {
        $user = $this->create($request->all());

        // This will log in the new user
        $this->guard()->login($user);

        return redirect($this->redirectPath());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'authority' => 2,
            'password' => Hash::make($data['password']),
        ]);
    }

    public function owner()
    {
        $user = Auth::user();
        $locations = Location::all();
        $categories = Category::all();
        return view('owner', compact('user', 'locations', 'categories'));
    }

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

            $data = $request->only(['name', 'location', 'category', 'detail']);
            Shop::create([
                'name' => $data['name'],
                'location_id' => $data['location'],
                'category_id' => $data['category'],
                'detail' => $data['detail'],
                'photo' => $url, // 画像のURLを保存
            ]);

            return redirect()->back()->with('success', '店舗情報の登録が完了しました');
        }
    }
}
