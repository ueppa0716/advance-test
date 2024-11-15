@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/info.css') }}">
@endsection

@section('content')
<div class="detail-group">
    <div class="detail-group__shop">
        <div class="detail__shop-heading">
            <span class="detail__shop-btn"><a class="" href="/owner">&lt</a></span>
            <p class="detail__shop-title">{{ $userShop->name }}</p>
        </div>
        <div class="detail__shop__content">
            <a href="{{ $userShop->photo }}" download="photo.jpg">
                <img src="{{ $userShop->photo }}" alt="Shop Photo" class="detail__shop-img">
            </a>
            <ul class="detail__shop__detail">
                <li class="detail__shop-tag">#{{ $userShop->location->location }}</li>
                <li class="detail__shop-tag">#{{ $userShop->category->category }}</li>
            </ul>
            <p class="detail__shop__text">{{ $userShop->detail }}</p>
        </div>
    </div>
    <form class="detail-group__update" method="post" action="/update/shop" enctype="multipart/form-data">
        @csrf
        <div class="detail__update-content">
            <p class="detail__update-title">店舗情報更新</p>
            <!-- 入力フィールド -->
            <input class="detail__update-input" type="text" name="name" id="name" value="{{$userShop->name}}">
            <select class="detail__update-input" name="location">
                <option value="" disabled selected>{{ $userShop->location->location ?? 'エリアを選択' }}</option>
                @foreach ($locations as $location)
                <option value="{{ $location->id }}" {{ old('location') == $location->id ? 'selected' : '' }}>
                    {{ $location->location }}
                </option>
                @endforeach
            </select>
            <select class="detail__update-input" name="category">
                <option value="" disabled selected>{{ $userShop->category->category ?? 'ジャンルを選択' }}</option>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->category }}
                </option>
                @endforeach
            </select>
            <textarea class="detail__update-input" name="detail" id="detail" rows="3">{{ $userShop->detail }}</textarea>
            <label for="photo">写真を選択:</label>
            <input type="file" id="photo" name="photo" accept="image/*">
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <p class="detail__update__success-message">
                @if (session('success'))
                {{ session('success') }}
                @endif
            </p>
            <p class="detail__update__error-message">
                @error('name')
                {{ $message }}
                @enderror
            </p>
            <p class="detail__update__error-message">
                @error('location')
                {{ $message }}
                @enderror
            </p>
            <p class="detail__update__error-message">
                @error('category')
                {{ $message }}
                @enderror
            </p>
            <p class="detail__update__error-message">
                @error('detail')
                {{ $message }}
                @enderror
            </p>
            <p class="detail__update__error-message">
                @error('photo')
                {{ $message }}
                @enderror
            </p>
        </div>
        <!-- 予約ボタン -->
        <input class="detail__update-btn" type="submit" value="更新する" name="update">
    </form>
</div>
@endsection