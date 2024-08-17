@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/manager.css') }}">
@endsection

@section('content')
    <p class="manager__heading">管理者メニュー</p>
    <p class="manager-form__success-message">
        @if (session('success'))
            {{ session('success') }}
        @endif
    </p>
    <div class="manager-form">
        <h2 class="manager-form__heading">店舗代表者登録</h2>
        <div class="manager-form__inner">
            <form class="manager-form__form" action="/manager" method="post">
                @csrf
                <div class="manager-form__group">
                    <img class="img-user" src="https://img.icons8.com/?size=100&id=60655&format=png&color=555555"
                        alt="ユーザー">
                    <input class="manager-form__input" type="text" name="name" id="name" placeholder="Ownername"
                        value="{{ old('name') }}" />
                </div>
                <div class="manager-form__group">
                    <img class="img-mail" src="https://img.icons8.com/?size=100&id=85500&format=png&color=555555"
                        alt="メール">
                    <input class="manager-form__input" type="mail" name="email" id="email" placeholder="Email"
                        value="{{ old('email') }}" />
                </div>
                <div class="manager-form__group">
                    <img class="img-lock" src="https://img.icons8.com/?size=100&id=83187&format=png&color=555555"
                        alt="ロック">
                    <input class="manager-form__input" type="password" name="password" id="password"
                        placeholder="Password">
                </div>
                <input class="manager-form__btn btn" type="submit" value="登録" name="owner">
                <input type="hidden" name="authority" value="2">
            </form>
        </div>
        <p class="manager-form__error-message">
            @error('name')
                {{ $message }}
            @enderror
        </p>
        <p class="manager-form__error-message">
            @error('email')
                {{ $message }}
            @enderror
        </p>
        <p class="manager-form__error-message">
            @error('password')
                {{ $message }}
            @enderror
        </p>
    </div>
@endsection('content')
