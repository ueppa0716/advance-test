@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<div class="detail-group">
    <div class="detail-group-shop">
        <div class="detail-shop__heading">
            <span class="detail-shop-btn"><a class="" href="/">&lt</a></span>
            <p class="detail-shop__title">{{ $shopInfo->name }}</p>
        </div>
        <div class="detail-shop__content">
            <a href="{{ $shopInfo->photo }}" download="photo.jpg">
                <img src="{{ $shopInfo->photo }}" alt="Shop Photo" class="detail-shop-img">
            </a>
            <p class="detail-shop__comment">※イメージをクリックすると画像が保存できます</p>
            <ul class="detail-shop__detail">
                <li class="detail-shop-tag">#{{ $shopInfo->location->location }}</li>
                <li class="detail-shop-tag">#{{ $shopInfo->category->category }}</li>
            </ul>
            <ul class="detail-shop__detail">
                <li class="detail-shop-tag">店舗評価点： @if (is_null($shopInfo->average_rating))
                    評価なし
                    @else
                    {{ number_format($shopInfo->average_rating, 1) }}
                    @endif
                </li>
                <li class="detail-shop-tag">
                    <a href="/evaluation/{{ $shopInfo->id }}" class="detail-evaluation-btn">口コミ確認はこちらから</a>
                </li>
            </ul>
            <p class="detail-shop__text">{{ $shopInfo->detail }}</p>
        </div>
    </div>
    <form class="detail-group-reserve" method="post" action="/confirm/{{ $shopInfo->id }}">
        @csrf
        <div class="detail-reserve__content">
            <p class="detail-reserve__title">予約</p>
            <!-- 入力フィールド -->
            <input class="detail-reserve-input" type="date" name="date" id="date"
                value="{{ $now->format('Y-m-d') }}">
            <input class=" detail-reserve-input" type="time" name="time" id="time"
                value="{{ date('H:i', strtotime($now)) }}">
            <select class="detail-reserve-input" name="people" id="people">
                @for ($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}" {{ old('people', 1) == $i ? 'selected' : '' }}>
                    {{ $i }}人</option>
                    @endfor
            </select>
            <input type="hidden" name="shop_id" value="{{ $shopInfo->id }}">
            <p class="detail-reserve__error-message">
                @error('date')
                {{ $message }}
                @enderror
            </p>
            <p class="detail-reserve__error-message">
                @error('time')
                {{ $message }}
                @enderror
            </p>
            <p class="detail-reserve__error-message">
                @error('people')
                {{ $message }}
                @enderror
            </p>
        </div>
        <!-- 予約ボタン -->
        <input class="detail-reserve-btn" type="submit" value="予約入力内容を確認する" name="reserve">
    </form>
</div>
@endsection