@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')
    <div class="login-form">
        <h2 class="login-form__heading">Login</h2>
        <div class="login-form__inner">
            <form class="login-form__form" action="/login" method="post">
                @csrf
                <div class="login-form__group">
                    <img class="img-mail" src="https://img.icons8.com/?size=100&id=85500&format=png&color=555555"
                        alt="メール">
                    <input class="login-form__input" type="mail" name="email" id="email" placeholder="Email"
                        value="{{ old('email') }}" />
                </div>
                <div class="login-form__group">
                    <img class="img-lock" src="https://img.icons8.com/?size=100&id=83187&format=png&color=555555"
                        alt="ロック">
                    <input class="login-form__input" type="password" name="password" id="password" placeholder="Password">
                </div>
                <input class="login-form__btn btn" type="submit" value="ログイン">
            </form>
        </div>
        <p class="login-form__error-message">
            @error('name')
                {{ $message }}
            @enderror
        </p>
        <p class="login-form__error-message">
            @error('email')
                {{ $message }}
            @enderror
        </p>
        <p class="login-form__error-message">
            @error('password')
                {{ $message }}
            @enderror
        </p>
    </div>
@endsection('content')
