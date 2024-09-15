<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MiddlewareController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ChargeController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [ShopController::class, 'shop']);
Route::post('/shop/like', [LikeController::class, 'shopLike']);
Route::get('/thanks', [RegisterController::class, 'thanks']);
Route::get('/detail/{shop_id}', [ShopController::class, 'detail']);

Route::middleware(['guest'])->group(function () {
    Route::get('/register', [RegisterController::class, 'register']);
    Route::post('/register', [RegisterController::class, 'create']);
    Route::get('/login', [LoginController::class, 'login']);
    Route::post('/login', [LoginController::class, 'login']);
});

Auth::routes(['verify' => true]);

// 認証済みユーザーのためのルート
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage', [UserController::class, 'mypage']);
    Route::post('/mypage/like', [LikeController::class, 'mypageLike']);
    Route::post('/mypage/update', [ReservationController::class, 'mypageUpdate']);
    Route::post('/mypage/delete', [ReservationController::class, 'mypageDelete']);
    Route::post('/confirm/{shop_id}', [ReservationController::class, 'confirm']);
    Route::post('/done', [ReservationController::class, 'done']);
    Route::get('/reservation', [UserController::class, 'reserve']);
    Route::post('/reservation/review', [UserController::class, 'review']);
    Route::get('/evaluation/{shop_id}', [ShopController::class, 'evaluation']);
    Route::get('/manager', [ManagerController::class, 'manager']);
    Route::post('/manager/admin', [ManagerController::class, 'admin']);
    Route::get('/mail', [ManagerController::class, 'mail']);
    Route::post('/mail/send', [ManagerController::class, 'send']);
    Route::get('/owner', [OwnerController::class, 'owner']);
    Route::post('/owner/open', [OwnerController::class, 'open']);
    Route::get('/update', [OwnerController::class, 'info']);
    Route::post('/update/shop', [OwnerController::class, 'updateShop']);
    Route::get('/check', [OwnerController::class, 'check']);
    Route::get('/charge', [ChargeController::class, 'stripe']);
    Route::post('/charge', [ChargeController::class, 'pay']);
    Route::get('/profile', function () {
        // 確認済みのユーザーのみがこのルートにアクセス可能
    });
});
