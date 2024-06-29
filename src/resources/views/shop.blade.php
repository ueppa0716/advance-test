@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop.css') }}">
@endsection

@section('header')
<form class="search__form" action="/search" method="get">
    @csrf
    <div class="search__form__location">
        <select class="search__form__location-select" name="location" value="{{request('location')}}">
            <option selected>All areas</option>
            @foreach ($shopInfos as $shopInfo)
            <option value="{{ $shopInfo->id }}" @if( request('location')=='All areas' ) selected @endif>{{ $shopInfo->location }}</option>
            @endforeach
        </select>
    </div>
    <div class="search__form__category">
        <select class="search__form__category-select" name="category" value="{{request('category')}}">
            <option selected>All genres</option>
            @foreach ($shopInfos as $shopInfo)
            <option value="{{ $shopInfo->id }}" @if( request('location')=='All genres' ) selected @endif>{{ $shopInfo->category }}</option>
            @endforeach
        </select>
    </div>
    <span class="search__logo"></span><input class="search__form__keyword" type="text" name="keyword" placeholder="Search..." value="{{request('keyword')}}">
</form>
@endsection

@section('content')
<div class="shop">
    @if (isset($shopInfos))
    @foreach ($shopInfos as $shopInfo)
    <div class="shop__group">
        <img src="{{$shopInfo->photo}}" alt="" class="shop__img">
        <div class="shop__content">
            <p class="shop__content-text">{{ $shopInfo->name }}</p>
            <ul class="shop__content-detail">
                <li class="shop__content-tag">#{{ $shopInfo->location }}</li>
                <li class="shop__content-tag">#{{ $shopInfo->category }}</li>
            </ul>
            <form class="shop__group-btn" action="/shop" method="post">
                @csrf
                <button class="detail__btn" type="submit" value="detail" name="detail">詳しくみる</button>
                <button class="like__btn" type="submit" value="like" name="like"><span class="like__logo"></span></button>
                <input type="hidden" name="shop_id" value="{{ $shopInfo->id }}">
            </form>
        </div>
    </div>
    @endforeach
    @endif
</div>
@endsection