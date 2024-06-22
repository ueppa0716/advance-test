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
                    <input class="register-form__input" type="text" name="name" id="name" placeholder="Username"
                        value="{{ old('name') }}" />
                </div>
                <div class="register-form__group">
                    <input class="register-form__input" type="mail" name="email" id="email" placeholder="Email"
                        value="{{ old('email') }}" />
                </div>
                <div class="register-form__group">
                    <input class="register-form__input" type="password" name="password" id="password"
                        placeholder="Password">
                </div>
                <input class="register-form__btn btn" type="submit" value="登録">
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
