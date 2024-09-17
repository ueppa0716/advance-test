@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/confirm.css') }}">
@endsection

@section('content')
<div class="confirm-group">
    <div class="confirm-group__shop">
        <div class="confirm-shop__heading">
            <span class="confirm-shop-btn"><a class="" href="/">&lt</a></span>
            <p class="confirm-shop__title">{{ $shopInfo->name }}</p>
        </div>
        <div class="confirm-shop-content">
            <a href="{{ $shopInfo->photo }}" download="photo.jpg">
                <img src="{{ $shopInfo->photo }}" alt="Shop Photo" class="confirm-shop-img">
            </a>
            <p class="confirm-shop__comment">※イメージをクリックすると画像が保存できます</p>
            <ul class="confirm-shop__confirm">
                <li class="confirm-shop-tag">#{{ $shopInfo->location->location }}</li>
                <li class="confirm-shop-tag">#{{ $shopInfo->category->category }}</li>
            </ul>
            <ul class="confirm-shop__confirm">
                <li class="confirm-shop-tag">店舗評価点： @if (is_null($shopInfo->average_rating))
                    評価なし
                    @else
                    {{ number_format($shopInfo->average_rating, 1) }}
                    @endif
                </li>
                <li class="confirm-shop-tag">
                    <a href="/evaluation/{{ $shopInfo->id }}" class="confirm-evaluation-btn">口コミ確認はこちらから</a>
                </li>
            </ul>
            <p class="confirm-shop__text">{{ $shopInfo->detail }}</p>
        </div>
    </div>
    <form class="confirm-group__reserve" method="post" action="/done">
        @csrf
        <div class="confirm-reserve__content">
            <p class="confirm-reserve__title">予約</p>
            <!-- 入力フィールド -->
            <input class="confirm-reserve-input" type="date" name="date" id="date"
                value="{{ \Carbon\Carbon::parse($reserveInfo['date'])->format('Y-m-d') }}" readonly>
            <input class="confirm-reserve-input" type="time" name="time" id="time"
                value="{{ \Carbon\Carbon::parse($reserveInfo['date'])->format('H:i') }}" readonly>
            <input class="confirm-reserve-input" type="number" name="people" id="people"
                value="{{ $reserveInfo['people'] }}" readonly>
            <input type="hidden" name="shop_id" value="{{ $shopInfo->id }}">
            <!-- 確認フィールド -->
            <table class="confirm-reserve__table">
                <tr class="confirm-reserve__row">
                    <td class="confirm-reserve-label">Shop</td>
                    <td class="confirm-reserve__text">{{ $shopInfo->name }}</td>
                </tr>
                <tr class="confirm-reserve__row">
                    <td class="confirm-reserve-label">Date</td>
                    <td class="confirm-reserve__text">{{ \Carbon\Carbon::parse($reserveInfo['date'])->format('Y/m/d') }}</td>
                </tr>
                <tr class="confirm-reserve__row">
                    <td class="confirm-reserve-label">Time</td>
                    <td class="confirm-reserve__text">{{ \Carbon\Carbon::parse($reserveInfo['date'])->format('H:i') }}</td>
                </tr>
                <tr class="confirm-reserve__row">
                    <td class="confirm-reserve-label">People</td>
                    <td class="confirm-reserve__text">{{ $reserveInfo['people'] }} 人</td>
                </tr>
            </table>
            <p class="confirm-reserve__error-message">
                @error('date')
                {{ $message }}
                @enderror
            </p>
            <p class="confirm-reserve__error-message">
                @error('time')
                {{ $message }}
                @enderror
            </p>
            <p class="confirm-reserve__error-message">
                @error('people')
                {{ $message }}
                @enderror
            </p>
        </div>
        <a href="/detail/{{ $shopInfo->id }}" class="detail-btn">入力画面に戻る</a>
        <!-- 予約ボタン -->
        <input class="confirm-reserve-btn" type="submit" value="予約を確定する" name="reserve">
    </form>
</div>
@endsection