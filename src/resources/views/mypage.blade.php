@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')

<div class="mypage">
    <p class="mypage__text">{{ $user->name }}さん</p>
    <div class="mypage__group">
        <div class="mypage__reserve">
            <div class="mypage__reserve-heading">
                <p class="mypage__reserve-heading-title">予約状況</p>
                <a class="mypage__reserve__link-text" href="/reservation">過去の予約履歴</a>
            </div>
            @if (session('message'))
            <span class="message">
                {{ session('message') }}
            </span>
            @endif
            @if ($errors->any())
            <p class="mypage__reserve__error-message">
                @error('date')
                {{ $message }}
                @enderror
            </p>
            <p class="mypage__reserve__error-message">
                @error('people')
                {{ $message }}
                @enderror
            </p>
            @endif

            @if (isset($reserveLists))
            @foreach ($reserveLists as $reserveList)
            <div class="mypage__reserve__shop">
                <div class="mypage__reserve__shop-heading">
                    <div class="mypage__reserve__shop-text">
                        <img class="img-clock"
                            src="https://img.icons8.com/?size=100&id=10034&format=png&color=ffffff"
                            alt="時計">
                        <p class="mypage__reserve-title">予約{{ $loop->iteration }}</p>
                    </div>
                    <form class="" method="post" action="/mypage/delete">
                        @csrf
                        <input class="mypage__cancel-btn" type="submit" value="x" name="cancel">
                        <input type="hidden" name="shop_id" value="{{ $reserveList->shop_id }}">
                    </form>
                </div>
                <form class="" method="post" action="/mypage/update">
                    @csrf
                    <table class="mypage__reserve-table">
                        <tr class="mypage__reserve-row">
                            <td class="mypage__reserve-label">Shop</td>
                            <td class="mypage__reserve-txt">{{ $reserveList->shop->name }}</td>
                        </tr>
                        <tr class="mypage__reserve-row">
                            <td class="mypage__reserve-label">Date</td>
                            <td class="mypage__reserve-txt">
                                <input class="mypage__reserve-txt" type="date" name="date"
                                    value="{{ $reserveList->date->format('Y-m-d') }}">
                            </td>
                        </tr>
                        <tr class="mypage__reserve-row">
                            <td class="mypage__reserve-label">Time</td>
                            <td class="mypage__reserve-txt">
                                <input class="mypage__reserve-txt" type="time" name="time"
                                    value="{{ date('H:i', strtotime($reserveList->date)) }}">
                            </td>
                        </tr>
                        <tr class="mypage__reserve-row">
                            <td class="mypage__reserve-label">Number</td>
                            <td class="mypage__reserve-txt">
                                <select class="mypage__reserve-txt" name="people">
                                    @for ($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}"
                                        {{ $reserveList->people == $i ? 'selected' : '' }}>
                                        {{ $i }}人</option>
                                        @endfor
                                </select>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="shop_id" value="{{ $reserveList->shop_id }}">
                    <input class="mypage__update-btn" type="submit" value="予約内容変更" name="update">
                </form>
            </div>
            @endforeach
            @endif
        </div>
        </form>
        <div class="mypage__like">
            <p class="mypage__like-heading">お気に入り店舗</p>
            <div class="mypage__like__group">
                @if (isset($likeLists))
                @foreach ($likeLists as $likeList)
                <div class="mypage__like__shop">
                    <img src="{{ $likeList->shop->photo }}" alt="" class="mypage__like__img">
                    <div class="mypage__like__content">
                        <p class="mypage__like__content-text">{{ $likeList->shop->name }}</p>
                        <ul class="mypage__like__content-detail">
                            <li class="mypage__like__content-tag">
                                #{{ $likeList->shop->location->location }}
                            </li>
                            <li class="mypage__like__content-tag">
                                #{{ $likeList->shop->category->category }}
                            </li>
                        </ul>
                        <ul class="mypage__like__content-detail">
                            <li class="mypage__like__content-tag">
                                店舗評価点： @if (is_null($likeList->shop->average_rating))
                                評価なし
                                @else
                                {{ number_format($likeList->shop->average_rating, 1) }}
                                @endif
                            </li>
                        </ul>
                        <div class="mypage__like__group-form">
                            <a href="/detail/{{ $likeList->shop->id }}" class="detail__btn">詳しくみる</a>
                            <form class="" method="post" action="/mypage/like">
                                @csrf
                                <button class="like__btn" type="submit" value="like" name="like"><span
                                        class="like__logo-red"></span></button>
                                <input type="hidden" name="shop_id" value="{{ $likeList->shop->id }}">
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

@endsection