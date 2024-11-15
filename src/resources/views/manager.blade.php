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
        <form class="manager-form__form" action="/manager/admin" method="post">
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
            <input class="manager-form-btn" type="submit" value="登録" name="owner">
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
<!-- Pro入会テストにて追加 -->
<div class="csvInport-form">
    <h2 class="manager-form__heading">新規店舗追加</h2>
    <div class="manager-form__inner">
        <form class="manager-form__form" action="/manager/csv" method="post" enctype="multipart/form-data">
            @csrf
            <input class="manager-form__input" type="file" name="csvFile" id="csvFile">
            <input class="manager-form-btn" type="submit" value="登録" name="owner">
        </form>
        @if ($errors->any())
        <div class="error-message">
            <ul>
                @foreach ($errors->all() as $error)
                <li class="error-message__text">・{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        @if (session('validationErrors'))
        <div class="error-message">
            <ul>
                @foreach (session('validationErrors') as $error)
                <li>
                    <ul>
                        @foreach ($error['errors'] as $msg)
                        <li class="error-message__text">・{{ $msg }}</li>
                        @endforeach
                    </ul>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
        <p class="success-message__text">
            @if (session('message'))
            {{ session('message') }}
            @endif
        </p>
    </div>
</div>
@endsection('content')