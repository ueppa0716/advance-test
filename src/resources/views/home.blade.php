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
    <header class="menu">
        <div class="menu__heading"><span class="menu_btn"><a class="menu__btn-text" href="/">x</a></span></div>
    </header>
    <div class="menu_group">
        <nav class="menu__link-nav">
            <ul class="menu__link-ul">
                <li class="menu__link-li"><a class="menu__link-text" href="/">Home</a></li>
                @if (Auth::check())
                <li class="menu__link-li">
                    <form class="" action="/logout" method="post">
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

</body>

</html>