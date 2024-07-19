@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop.css') }}">
@endsection

@section('header')
<form class="search__form" action="/" method="get">
    @csrf
    <div class="search__form__location">
        <select class="search__form__location-select" name="location">
            <option value="All areas" {{ request('location') == 'All areas' ? 'selected' : '' }}>All areas</option>
            @foreach ($locations as $location)
            <option value="{{ $location->id }}" {{ request('location') == $location->id ? 'selected' : '' }}>
                {{ $location->location }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="search__form__category">
        <select class="search__form__category-select" name="category">
            <option value="All genres" {{ request('category') == 'All genres' ? 'selected' : '' }}>All genres</option>
            @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                {{ $category->category }}
            </option>
            @endforeach
        </select>
    </div>
    <span class="search__logo"></span><input class="search__form__keyword" type="text" name="keyword" placeholder="Search..." value="{{ request('keyword') }}">
    <input class="search__form-btn" type="submit" value="検索">
</form>
@endsection

@section('content')
<div class="shop">
    @if (isset($shopInfos))
    @foreach ($shopInfos as $shopInfo)
    <div class="shop__group">
        <img src="{{ $shopInfo->photo }}" alt="" class="shop__img">
        <div class="shop__content">
            <p class="shop__content-text">{{ $shopInfo->name }}</p>
            <ul class="shop__content-detail">
                <li class="shop__content-tag">#{{ $shopInfo->location->location }}</li>
                <li class="shop__content-tag">#{{ $shopInfo->category->category }}</li>
            </ul>
            <div class="shop__group-form">
                <form class="shop__group-btn" action="/detail" method="get">
                    @csrf
                    <button class="detail__btn" type="submit" value="detail" name="detail">詳しくみる</button>
                    <input type="hidden" name="shop_id" value="{{ $shopInfo->id }}">
                </form>
                <form class="shop__group-btn" action="/" method="post">
                    @csrf
                    @if($shopInfo->liked)
                    <button class="like__btn" type="submit" value="like" name="like"><span class="like__logo-red"></span></button>
                    @else
                    <button class="like__btn" type="submit" value="like" name="like"><span class="like__logo"></span></button>
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