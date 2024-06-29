<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MiddlewareController;


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
Route::get('/menu', [HomeController::class, 'index']);
Route::get('/thanks', [HomeController::class, 'thanks']);

Auth::routes(['verify' => true]);

// 認証済みユーザーのためのルート
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage', [HomeController::class, 'mypage']);
    Route::get('/profile', function () {
        // 確認済みのユーザーのみがこのルートにアクセス可能
    });
});
