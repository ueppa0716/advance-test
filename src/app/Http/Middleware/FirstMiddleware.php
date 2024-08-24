<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Log;

class FirstMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    // public function handle(Request $request, Closure $next, $role)
    // {
    //     if (Gate::denies($role)) {
    //         // 権限がない場合、403エラーページにリダイレクト
    //         abort(403, 'Unauthorized action.');
    //     }

    //     return $next($request);
    // }

    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->authority == 0) {
            return $next($request);
        }
        return redirect('/mypage'); // 権限がない場合はマイページへリダイレクト

        // // ログインページやその他の除外対象のルート
        // $excludedRoutes = ['login', 'register', 'password/reset', 'password/email'];
        // if (in_array($request->route()->getName(), $excludedRoutes)) {
        //     return $next($request);
        // }

        // if ($request->authority == 0) {
        //     return view('/manager');
        // } elseif ($request->authority == 1) {
        //     return view('/owner');
        // } else {
        //     return view('/mypage');
        // }
    }
}
