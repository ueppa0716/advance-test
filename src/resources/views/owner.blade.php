@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/owner.css') }}">
@endsection

@section('content')
<p class="owner__heading">店舗代表者メニュー</p>
<p class="owner-form__success-message">
    @if (session('success'))
    {{ session('success') }}
    @endif
</p>
<div class="owner-form">
    @if(!$userShop->isEmpty())
    <h2 class="owner-form__heading">店舗情報管理</h2>
    <div class="owner-form__inner">
        <ul class="owner-form-ul">
            <li class="owner-form-li"><a class="owner__link-text" href="/update">店舗情報の更新はこちらから</a></li>
            <li class="owner-form-li"><a class="owner__link-text" href="/check">店舗への予約状況確認はこちらから</a></li>
        </ul>
        @else
        <h2 class="owner-form__heading">店舗情報登録</h2>
        <div class="owner-form__inner">
            <form class="owner-form__form" action="/owner" method="post" enctype="multipart/form-data">
                @csrf
                <div class="owner-form__group">
                    <input class="owner-form__input" type="text" name="name" id="name" placeholder="Shopname"
                        value="{{ old('name') }}" />
                </div>
                <div class="owner-form__group">
                    <select class="owner-form__location-select" name="location">
                        <option value="" disabled selected>エリアを選択</option>
                        @foreach ($locations as $location)
                        <option value="{{ $location->id }}" {{ old('location') == $location->id ? 'selected' : '' }}>
                            {{ $location->location }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="owner-form__group">
                    <select class="owner-form__category-select" name="category">
                        <option value="" disabled selected>ジャンルを選択</option>
                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->category }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="owner-form__group">
                    <textarea class="owner-form__comment-area" name="detail" id="detail" rows="3">{{ old('comment') }}</textarea>
                </div>
                <div class="owner-form__group">
                    <label for="photo">写真を選択:</label>
                    <input type="file" id="photo" name="photo" accept="image/*">
                </div>
                <input class="owner-form__btn btn" type="submit" value="登録" name="shop">
                <input type="hidden" name="user_id" value="{{ $user->id }}">
            </form>
            @endif
        </div>
        <p class="owner-form__error-message">
            @error('name')
            {{ $message }}
            @enderror
        </p>
        <p class="owner-form__error-message">
            @error('location')
            {{ $message }}
            @enderror
        </p>
        <p class="owner-form__error-message">
            @error('category')
            {{ $message }}
            @enderror
        </p>
        <p class="owner-form__error-message">
            @error('detail')
            {{ $message }}
            @enderror
        </p>
        <p class="owner-form__error-message">
            @error('photo')
            {{ $message }}
            @enderror
        </p>
    </div>
    @endsection