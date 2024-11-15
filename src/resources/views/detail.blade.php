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
                <!-- Pro入会テストにて削除 -->
                <!-- <li class="detail-shop-tag">
                    <a href="/evaluation/{{ $shopInfo->id }}" class="detail-evaluation-btn">口コミ確認はこちらから</a>
                </li> -->
            </ul>
            <p class="detail-shop__text">{{ $shopInfo->detail }}</p>
        </div>

        <!-- Pro入会テストにて追加 -->
        @if (!$feedbackExists)
        <p class="detail-shop-feedback">
            <a href="/feedback/{{ $shopInfo->id }}" class="">口コミを投稿する</a>
        </p>
        @endif
        @if (isset($feedbackInfos))
        <div class="feedback">
            <p class="feedback-title">全ての口コミ情報</p>
            @foreach ($feedbackInfos as $feedbackInfo)
            <div class="feedback-post">
                <form class="" method="post" action="/feedback/update/{{ $shopInfo->id }}">
                    @csrf
                    <div class="feedback-edit">
                        @if (isset($user) && ($feedbackInfo->user_id === $user->id))
                        <input class="feedback-edit__btn" type="submit" value="口コミを編集" name="update">
                        @endif
                        @if (isset($user) && ($feedbackInfo->user_id === $user->id || $user->authority == 0))
                        <input class="feedback-edit__btn" type="submit" value="口コミを削除" name="delete">
                        @endif
                        <input type="hidden" name="feedback_id" value="{{ $feedbackInfo->id }}">
                    </div>
                    <div class="feedback-heading">
                        <p class="feedback-heading__text">口コミ投稿日：{{ $feedbackInfo->created_at->format('Y/m/d') }}</p>
                        <p class="feedback-heading__text">ユーザー名：{{ $feedbackInfo->user->name }}</p>
                    </div>
                    <!-- 口コミ投稿者または管理者 -->
                    @if (isset($user) && ($feedbackInfo->user_id === $user->id || $user->authority == 0))
                    <div class="point">
                        <input type="radio" id="star5" name="point" value="5" {{ $feedbackInfo->point == 5 ? 'checked' : '' }} />
                        <label for="star5" title="5 stars">★</label>
                        <input type="radio" id="star4" name="point" value="4" {{ $feedbackInfo->point == 4 ? 'checked' : '' }} />
                        <label for="star4" title="4 stars">★</label>
                        <input type="radio" id="star3" name="point" value="3" {{ $feedbackInfo->point == 3 ? 'checked' : '' }} />
                        <label for="star3" title="3 stars">★</label>
                        <input type="radio" id="star2" name="point" value="2" {{ $feedbackInfo->point == 2 ? 'checked' : '' }} />
                        <label for="star2" title="2 stars">★</label>
                        <input type="radio" id="star1" name="point" value="1" {{ $feedbackInfo->point == 1 ? 'checked' : '' }} />
                        <label for="star1" title="1 star">★</label>
                    </div>
                    <div class="feedback-content">
                        <img src="{{ $feedbackInfo->photo }}" alt="User Photo" class="feedback-content-img">
                        <textarea name="comment" class="feedback-content__text">{{ $feedbackInfo->comment }}</textarea>
                    </div>
                    @endif
                    <!-- 口コミ投稿者または管理者でない -->
                    @if (!isset($user) || !($feedbackInfo->user_id === $user->id || $user->authority == 0))
                    @for ($i = 1; $i <= 5; $i++)
                        <span class="star" style="color: {{ $i <= $feedbackInfo->point ? 'rgb(1, 63, 245)' : '' }};">★</span>
                        @endfor
                        <div class="feedback-content">
                            <img src="{{ $feedbackInfo->photo }}" alt="User Photo" class="feedback-content-img">
                            <p class="feedback-content__text">{{ $feedbackInfo->comment }}</p>
                        </div>
                        @endif
                </form>
            </div>
            @endforeach
        </div>
        @endif

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