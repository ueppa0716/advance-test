@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop.css') }}">
@endsection

@section('header')
<form class="search-form__sort" action="/" method="get">
    @csrf
    <div class="search-form__position">
        <p class="search-form__position__text">並び替え：</p>
        <select class="search-form__position-select" name="position">
            <option value="select">選択する</option>
            <option value="random">ランダム</option>
            <option value="higher">評価が高い順</option>
            <option value="lower">評価が低い順</option>
        </select>
    </div>
    <input class="search-form-btn" type="submit" value="ソート">
</form>
<form class="search-form" action="/" method="get">
    @csrf
    <div class="search-form__location">
        <select class="search-form__location-select" name="location">
            <option value="All areas" {{ request('location') == 'All areas' ? 'selected' : '' }}>All areas</option>
            @foreach ($locations as $location)
            <option value="{{ $location->id }}" {{ request('location') == $location->id ? 'selected' : '' }}>
                {{ $location->location }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="search-form__category">
        <select class="search-form__category-select" name="category">
            <option value="All genres" {{ request('category') == 'All genres' ? 'selected' : '' }}>All genres</option>
            @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                {{ $category->category }}
            </option>
            @endforeach
        </select>
    </div>
    <span class="search-logo"></span><input class="search-form__keyword" type="text" name="keyword"
        placeholder="Search..." value="{{ request('keyword') }}">
    <input class="search-form-btn" type="submit" value="検索">
</form>
@endsection

@section('content')
<div class="shop">
    @if (isset($shopInfos))
    @foreach ($shopInfos as $shopInfo)
    <div class="shop-group">
        <img src="{{ $shopInfo->photo }}" alt="" class="shop-img">
        <div class="shop-content">
            <p class="shop-content__text">{{ $shopInfo->name }}</p>
            <ul class="shop-content__detail">
                <li class="shop-content-tag">#{{ $shopInfo->location->location }}</li>
                <li class="shop-content-tag">#{{ $shopInfo->category->category }}</li>
            </ul>
            <ul class="shop-content__detail">
                <li class="shop-content-tag">店舗評価点： @if (is_null($shopInfo->average_rating))
                    評価なし
                    @else
                    {{ number_format($shopInfo->average_rating, 1) }}
                    @endif
                </li>
            </ul>
            <div class="shop-group-form">
                <a href="/detail/{{ $shopInfo->id }}" class="detail-btn">詳しくみる</a>
                <form class="shop-group-btn" action="/shop/like" method="post">
                    @csrf
                    @if ($shopInfo->liked)
                    <button class="like-btn" type="submit" value="like" name="like"><span
                            class="like-logo--red"></span></button>
                    @else
                    <button class="like-btn" type="submit" value="like" name="like"><span
                            class="like-logo"></span></button>
                    @endif
                    <input type="hidden" name="shop_id" value="{{ $shopInfo->id }}">
                </form>
            </div>
        </div>
    </div>
    @endforeach
    @endif
</div>
@endsection