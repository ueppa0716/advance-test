<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MiddlewareController;
use App\Http\Controllers\Auth\RegisterController;


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

Route::get('/', [HomeController::class, 'shop']);
Route::post('/', [HomeController::class, 'shop']);
Route::get('/thanks', [HomeController::class, 'thanks']);
Route::get('/detail', [HomeController::class, 'detail']);
Route::post('/register', [RegisterController::class, 'register']);

Auth::routes(['verify' => true]);

// 認証済みユーザーのためのルート
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage', [HomeController::class, 'mypage']);
    Route::post('/mypage', [HomeController::class, 'mypage']);
    Route::post('/done', [HomeController::class, 'done']);
    Route::get('/reservation', [HomeController::class, 'reserve']);
    Route::post('/reservation', [HomeController::class, 'review']);
    Route::get('/profile', function () {
        // 確認済みのユーザーのみがこのルートにアクセス可能
    });
});
