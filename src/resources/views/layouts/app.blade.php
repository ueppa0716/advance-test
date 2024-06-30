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
            <h1><a class="header__heading" href="/menu"><span class="header__logo"></span>Rese</a></h1>
            @yield('header')
        </header>

        <div class="content">
            @yield('content')
        </div>


    </div>
</body>

</html>