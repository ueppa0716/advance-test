@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')

<div class="mypage">
    <p class="mypage__text">{{ $user->name }}さん</p>
    <div class="mypage__group">
        <div class="mypage__reserve">
            <p class="mypage__reserve-heading">予約状況</p>
            @if (isset($reserveLists))
            @foreach ($reserveLists as $reserveList)
            <div class="mypage__reserve__shop">
                <div class="mypage__reserve__shop-heading">
                    <p class="mypage__reserve-title">予約</p>
                    <form class="" method="post" action="/mypage">
                        @csrf
                        <input class="mypage__cancel-btn" type="submit" value="x" name="cancel">
                        <input type="hidden" name="shop_id" value="{{ $reserveList->shop->id }}">
                    </form>
                </div>
                <table class="mypage__reserve-table">
                    <tr class="mypage__reserve-row">
                        <td class="mypage__reserve-label">Shop</td>
                        <td class="mypage__reserve-txt">{{ $reserveList->shop->name }}</td>
                    </tr>
                    <tr class="mypage__reserve-row">
                        <td class="mypage__reserve-label">Date</td>
                        <td class="mypage__reserve-txt">{{ $reserveList->date->format('Y-m-d')}}</td>
                    </tr>
                    <tr class="mypage__reserve-row">
                        <td class="mypage__reserve-label">Time</td>
                        <td class="mypage__reserve-txt">{{ date('H:i', strtotime($reserveList->date))}}</td>
                    </tr>
                    <tr class="mypage__reserve-row">
                        <td class="mypage__reserve-label">Number</td>
                        <td class="mypage__reserve-txt">{{ $reserveList->people }}人</td>
                    </tr>
                </table>
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
                            <li class="mypage__like__content-tag">#{{ $likeList->shop->location->location }}</li>
                            <li class="mypage__like__content-tag">#{{ $likeList->shop->category->category }}</li>
                        </ul>
                        <div class="mypage__like__group-form">
                            <form class="" method="get" action="/detail">
                                @csrf
                                <button class="detail__btn" type="submit" value="detail" name="detail">詳しくみる</button>
                                <input type="hidden" name="shop_id" value="{{ $likeList->shop->id }}">
                            </form>
                            <form class="" method="post" action="/mypage">
                                @csrf
                                <button class="like__btn" type="submit" value="like" name="like"><span class="like__logo-red"></span></button>
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