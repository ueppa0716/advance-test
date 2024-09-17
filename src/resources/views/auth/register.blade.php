@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
<div class="register-form">
    <h2 class="register-form__heading">Registration</h2>
    <div class="register-form__inner">
        <form class="register-form__form" action="/register" method="post">
            @csrf
            <div class="register-form__group">
                <img class="img-user" src="https://img.icons8.com/?size=100&id=60655&format=png&color=555555"
                    alt="ユーザー">
                <input class="register-form__input" type="text" name="name" id="name" placeholder="Username"
                    value="{{ old('name') }}" />
            </div>
            <div class="register-form__group">
                <img class="img-mail" src="https://img.icons8.com/?size=100&id=85500&format=png&color=555555"
                    alt="メール">
                <input class="register-form__input" type="mail" name="email" id="email" placeholder="Email"
                    value="{{ old('email') }}" />
            </div>
            <div class="register-form__group">
                <img class="img-lock" src="https://img.icons8.com/?size=100&id=83187&format=png&color=555555"
                    alt="ロック">
                <input class="register-form__input" type="password" name="password" id="password"
                    placeholder="Password">
            </div>
            <input class="register-form-btn" type="submit" value="登録">
        </form>
    </div>
    <p class="register-form__error-message">
        @error('name')
        {{ $message }}
        @enderror
    </p>
    <p class="register-form__error-message">
        @error('email')
        {{ $message }}
        @enderror
    </p>
    <p class="register-form__error-message">
        @error('password')
        {{ $message }}
        @enderror
    </p>
</div>
@endsection('content')