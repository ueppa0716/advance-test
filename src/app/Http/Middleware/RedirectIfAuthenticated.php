<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // // ユーザーの authority に応じてリダイレクト先を指定
                // $user = Auth::user();
                // if ($user->authority == 0) {
                //     return redirect('/manager');
                // } elseif ($user->authority == 1) {
                //     return redirect('/owner');
                // } else {
                //     return redirect('/mypage');
                // }
                // return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
