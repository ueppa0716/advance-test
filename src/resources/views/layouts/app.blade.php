<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
    <div class="app">
        <header class="header">
            <h1><a class="header__heading" href="#modal"><span class="header__logo"></span>Rese</a></h1>
            <div class="menu_group" id='modal'>
                <div class="menu__heading"><span class="menu_btn"><a class="menu__btn-text" href="#">x</a></span>
                </div>
                <nav class="menu__link-nav">
                    <ul class="menu__link-ul">
                        <li class="menu__link-li"><a class="menu__link-text" href="/">Home</a></li>
                        @if (isset($user))
                        @if ($user->authority == 0)
                        <li class="menu__link-li"><a class="menu__link-text" href="/manager">Manager</a></li>
                        @endif
                        @if ($user->authority == 1)
                        <li class="menu__link-li"><a class="menu__link-text" href="/manager">Owner</a></li>
                        @endif
                        @endif
                        @if (Auth::check())
                        <li class="menu__link-li">
                            <form action="/logout" method="post">
                                @csrf
                                <button type="submit" class="menu__link-btn">Logout</button>
                            </form>
                        </li>
                        <li class="menu__link-li"><a class="menu__link-text" href="/mypage">Mypage</a></li>
                        @else
                        <li class="menu__link-li"><a class="menu__link-text" href="/register">Registration</a></li>
                        <li class="menu__link-li"><a class="menu__link-text" href="/login">Login</a></li>
                        @endif
                    </ul>
                </nav>
            </div>

            @yield('header')
        </header>

        <div class="content">
            @yield('content')
        </div>


    </div>
</body>

</html>