@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/feedback.css') }}">
@endsection

@section('content')
<div class="feedback-group">
    <div class="feedback-group-shop">
        <p class="feedback-group__heading">今回のご利用はいかがでしたか？</p>
        <div class="shop-group">
            <img src="{{ $shopInfo->photo }}" alt="" class="shop-img">
            <div class="shop-content">
                <p class="shop-content__text">{{ $shopInfo->name }}</p>
                <ul class="shop-content__detail">
                    <li class="shop-content-tag">#{{ $shopInfo->location->location }}</li>
                    <li class="shop-content-tag">#{{ $shopInfo->category->category }}</li>
                </ul>
                <div class="shop-group-form">
                    <a href="/detail/{{ $shopInfo->id }}" class="detail-btn">詳しくみる</a>
                    <form class="shop-group-btn" action="/shop/like" method="post">
                        @csrf
                        @if ($shopInfo->liked)
                        <button class="like-btn" type="submit" value="like" name="like" disabled><span
                                class="like-logo--red"></span></button>
                        @else
                        <button class="like-btn" type="submit" value="like" name="like" disabled><span
                                class="like-logo"></span></button>
                        @endif
                        <input type="hidden" name="shop_id" value="{{ $shopInfo->id }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <form class="feedback-group-review" method="post" action="/feedback/send/{{ $shopInfo->id }}" enctype="multipart/form-data">
        @csrf
        <p class="feedback-group__title">体験を評価してください</p>
        <div class="point">
            <input type="radio" id="star5" name="point" value="5" />
            <label for="star5" title="5 stars">★</label>
            <input type="radio" id="star4" name="point" value="4" />
            <label for="star4" title="4 stars">★</label>
            <input type="radio" id="star3" name="point" value="3" />
            <label for="star3" title="3 stars">★</label>
            <input type="radio" id="star2" name="point" value="2" />
            <label for="star2" title="2 stars">★</label>
            <input type="radio" id="star1" name="point" value="1" />
            <label for="star1" title="1 star">★</label>
        </div>
        <div class="comment">
            <p class="feedback-group__title">口コミを投稿</p>
            <textarea class="feedback-group__comment-area" name="comment" id="comment" rows="10" placeholder="カジュアルな夜のお出かけにおすすめのスポット">{{ old('comment') }}</textarea>
            <p class="feedback-group__comment-text">400字以内</p>
        </div>
        <div class="photo">
            <p class="feedback-group__title">画像の追加</p>
            <div class="photo-area">
                <p class="photo-area__text">クリックして画像を追加</p>
                <p class="photo-area__text">またはドラッグアンドドロップ</p>
                <input type="file" id="photo" name="photo" accept="image/*">
            </div>
        </div>
        <input type="hidden" name="shop_id" value="{{ $shopInfo->id }}">
        @if ($errors->any())
        <div class="error-message">
            <ul>
                @foreach ($errors->all() as $error)
                <li class="error-message__text">・{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <p class="error-message__text">
            @if (session('message'))
            {{ session('message') }}
            @endif
        </p>
        <div class="feedback-review-btn__area">
            <input class="feedback-review-btn" type="submit" value="口コミを投稿" name="review">
        </div>
    </form>
</div>
@endsection