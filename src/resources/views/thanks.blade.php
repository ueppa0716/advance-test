@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
@endsection

@section('content')
<div class="message-form">
    <p class="message-form__text">会員登録ありがとうございます</p>

    <form class="thanks-form" action="/login" method="get">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        <input type="hidden" name="password" value="{{ $password }}">
        <input class="thanks-form-btn" type="submit" value="ログインする">
    </form>
</div>
@endsection