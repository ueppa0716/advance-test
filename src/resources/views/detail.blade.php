@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')

<div class="detail__group">
    <div class="detail__group-shop">
        <div class="detail__shop-heading">
            <span class="detail__shop-btn"><a class="" href="/">&lt</a></span>
            <p class="detail__shop-title">{{ $shopInfo->name }}</p>
        </div>
        <div class="detail__shop-content">
            <img src="{{ $shopInfo->photo }}" alt="" class="detail__shop-img">
            <ul class="detail__shop-detail">
                <li class="detail__shop-tag">#{{ $shopInfo->location->location }}</li>
                <li class="detail__shop-tag">#{{ $shopInfo->category->category }}</li>
            </ul>
            <p class="detail__shop-txt">{{ $shopInfo->detail }}</p>
        </div>
    </div>
    <form class="detail__group-reserve" method="post" action="/done">
        @csrf
        <div class="detail__reserve-content">
            <p class="detail__reserve-title">予約</p>
            <!-- 入力フィールド -->
            <input class="detail__reserve-input" type="date" name="date" id="date" value="{{$now->format('Y-m-d')}}">
            <input class=" detail__reserve-input" type="time" name="time" id="time" value="{{date('H:i', strtotime($now))}}">
            <input class="detail__reserve-input" type="number" name="people" id="people" min="1" max="10" value="{{ old('people', 1) }}" step="1" data-format="$1 人">
            <input type="hidden" name="shop_id" value="{{ $shopInfo->id }}">
            <!-- 確認フィールド -->
            <table class="detail__reserve-table">
                <tr class="detail__reserve-row">
                    <td class="detail__reserve-label">Shop</td>
                    <td class="detail__reserve-txt">{{ $shopInfo->name }}</td>
                </tr>
                <tr class="detail__reserve-row">
                    <td class="detail__reserve-label">Date</td>
                    <td class="detail__reserve-txt">{{ old('date') }}</td>
                </tr>
                <tr class="detail__reserve-row">
                    <td class="detail__reserve-label">Time</td>
                    <td class="detail__reserve-txt">{{ old('time') }}</td>
                </tr>
                <tr class="detail__reserve-row">
                    <td class="detail__reserve-label">Number</td>
                    <td class="detail__reserve-txt">{{ old('people') }}</td>
                </tr>
            </table>
        </div>
        <!-- 予約ボタン -->
        <input class="detail__reserve-btn" type="submit" value="予約する" name="reserve">
    </form>
</div>
@endsection