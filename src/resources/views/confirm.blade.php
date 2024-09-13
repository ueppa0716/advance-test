@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/confirm.css') }}">
@endsection

@section('content')
<div class="confirm__group">
    <div class="confirm__group-shop">
        <div class="confirm__shop-heading">
            <span class="confirm__shop-btn"><a class="" href="/">&lt</a></span>
            <p class="confirm__shop-title">{{ $shopInfo->name }}</p>
        </div>
        <div class="confirm__shop-content">
            <a href="{{ $shopInfo->photo }}" download="photo.jpg">
                <img src="{{ $shopInfo->photo }}" alt="Shop Photo" class="confirm__shop-img">
            </a>
            <p class="confirm__shop-comment">※イメージをクリックすると画像が保存できます</p>
            <ul class="confirm__shop-confirm">
                <li class="confirm__shop-tag">#{{ $shopInfo->location->location }}</li>
                <li class="confirm__shop-tag">#{{ $shopInfo->category->category }}</li>
            </ul>
            <ul class="confirm__shop-confirm">
                <li class="confirm__shop-tag">店舗評価点： @if (is_null($shopInfo->average_rating))
                    評価なし
                    @else
                    {{ number_format($shopInfo->average_rating, 1) }}
                    @endif
                </li>
                <li class="confirm__shop-tag">
                    <a href="/evaluation/{{ $shopInfo->id }}" class="confirm__evaluation-btn">口コミ確認はこちらから</a>
                </li>
            </ul>
            <p class="confirm__shop-txt">{{ $shopInfo->detail }}</p>
        </div>
    </div>
    <form class="confirm__group-reserve" method="post" action="/done">
        @csrf
        <div class="confirm__reserve-content">
            <p class="confirm__reserve-title">予約</p>
            <!-- 入力フィールド -->
            <input class="confirm__reserve-input" type="date" name="date" id="date"
                value="{{ \Carbon\Carbon::parse($reserveInfo['date'])->format('Y-m-d') }}" readonly>
            <input class="confirm__reserve-input" type="time" name="time" id="time"
                value="{{ \Carbon\Carbon::parse($reserveInfo['date'])->format('H:i') }}" readonly>
            <input class="confirm__reserve-input" type="number" name="people" id="people"
                value="{{ $reserveInfo['people'] }}" readonly>
            <input type="hidden" name="shop_id" value="{{ $shopInfo->id }}">
            <!-- 確認フィールド -->
            <table class="confirm__reserve-table">
                <tr class="confirm__reserve-row">
                    <td class="confirm__reserve-label">Shop</td>
                    <td class="confirm__reserve-txt">{{ $shopInfo->name }}</td>
                </tr>
                <tr class="confirm__reserve-row">
                    <td class="confirm__reserve-label">Date</td>
                    <td class="confirm__reserve-txt">{{ \Carbon\Carbon::parse($reserveInfo['date'])->format('Y/m/d') }}</td>
                </tr>
                <tr class="confirm__reserve-row">
                    <td class="confirm__reserve-label">Time</td>
                    <td class="confirm__reserve-txt">{{ \Carbon\Carbon::parse($reserveInfo['date'])->format('H:i') }}</td>
                </tr>
                <tr class="confirm__reserve-row">
                    <td class="confirm__reserve-label">People</td>
                    <td class="confirm__reserve-txt">{{ $reserveInfo['people'] }} 人</td>
                </tr>
            </table>
            <p class="confirm__reserve__error-message">
                @error('date')
                {{ $message }}
                @enderror
            </p>
            <p class="confirm__reserve__error-message">
                @error('time')
                {{ $message }}
                @enderror
            </p>
            <p class="confirm__reserve__error-message">
                @error('people')
                {{ $message }}
                @enderror
            </p>
        </div>
        <a href="/detail/{{ $shopInfo->id }}" class="detail__btn">入力画面に戻る</a>
        <!-- 予約ボタン -->
        <input class="confirm__reserve-btn" type="submit" value="予約を確定する" name="reserve">
    </form>
</div>
@endsection