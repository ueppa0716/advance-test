@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
@endsection

@section('content')
    <div class="message-form">
        <p>会員登録ありがとうございます。</p>
        <form class="login-form__form" action="/login" method="post">
            @csrf
            <input type="hidden" name="email" value="{{ $registerInfo->email }}">
            <input type="hidden" name="password" value="{{ $registerInfo->password }}">
            <input class="register-form__btn btn" type="submit" value="ログインする">
        </form>
    </div>
@endsection
