@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')

<div class="mypage">
    <p class="mypage__text">{{ $user->name }}さん</p>
    <div class="mypage-group">
        <div class="mypage-reserve">
            <div class="mypage-reserve__heading">
                <p class="mypage-reserve__heading__title">予約状況</p>
                <a class="mypage-reserve__link__text" href="/reservation">過去の予約履歴</a>
            </div>
            @if (session('message'))
            <span class="message">
                {{ session('message') }}
            </span>
            @endif
            @if ($errors->any())
            <p class="mypage-reserve__error-message">
                @error('date')
                {{ $message }}
                @enderror
            </p>
            <p class="mypage-reserve__error-message">
                @error('people')
                {{ $message }}
                @enderror
            </p>
            @endif

            @if (isset($reserveLists))
            @foreach ($reserveLists as $reserveList)
            <div class="mypage-reserve__shop">
                <div class="mypage-reserve__shop__heading">
                    <div class="mypage-reserve__shop__text">
                        <img class="img-clock"
                            src="https://img.icons8.com/?size=100&id=10034&format=png&color=ffffff"
                            alt="時計">
                        <p class="mypage-reserve__title">予約{{ $loop->iteration }}</p>
                    </div>
                    <form class="" method="post" action="/mypage/delete">
                        @csrf
                        <input class="mypage__cancel-btn" type="submit" value="x" name="cancel">
                        <input type="hidden" name="shop_id" value="{{ $reserveList->shop_id }}">
                    </form>
                </div>
                <form class="" method="post" action="/mypage/update">
                    @csrf
                    <table class="mypage-reserve__table">
                        <tr class="mypage-reserve__row">
                            <td class="mypage-reserve-label">Shop</td>
                            <td class="mypage-reserve__text">{{ $reserveList->shop->name }}</td>
                        </tr>
                        <tr class="mypage-reserve__row">
                            <td class="mypage-reserve-label">Date</td>
                            <td class="mypage-reserve__text">
                                <input class="mypage-reserve__text" type="date" name="date"
                                    value="{{ $reserveList->date->format('Y-m-d') }}">
                            </td>
                        </tr>
                        <tr class="mypage-reserve__row">
                            <td class="mypage-reserve-label">Time</td>
                            <td class="mypage-reserve__text">
                                <input class="mypage-reserve__text" type="time" name="time"
                                    value="{{ date('H:i', strtotime($reserveList->date)) }}">
                            </td>
                        </tr>
                        <tr class="mypage-reserve__row">
                            <td class="mypage-reserve-label">Number</td>
                            <td class="mypage-reserve__text">
                                <select class="mypage-reserve__text" name="people">
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
                @if (empty($reserveList->payment))
                <form action="/charge" method="post" class="payment-form">
                    @csrf
                    <script src="https://checkout.stripe.com/checkout.js" class="stripe-button" data-key="{{ env('STRIPE_KEY') }}"
                        data-amount="1000" data-name="Stripe Demo" data-label="決済はこちらから！"
                        data-description="Online course about integrating Stripe"
                        data-image="https://stripe.com/img/documentation/checkout/marketplace.png" data-locale="auto" data-currency="JPY">
                    </script>
                    <input type="hidden" name="reservation_id" value="{{ $reserveList->id }}">
                </form>
                @endif
            </div>
            @endforeach
            @endif
        </div>
        </form>
        <div class="mypage-like">
            <p class="mypage-like__heading">お気に入り店舗</p>
            <div class="mypage-like__group">
                @if (isset($likeLists))
                @foreach ($likeLists as $likeList)
                <div class="mypage-like__shop">
                    <img src="{{ $likeList->shop->photo }}" alt="" class="mypage-like__img">
                    <div class="mypage-like__content">
                        <p class="mypage-like__content__text">{{ $likeList->shop->name }}</p>
                        <ul class="mypage-like__content__detail">
                            <li class="mypage-like__content-tag">
                                #{{ $likeList->shop->location->location }}
                            </li>
                            <li class="mypage-like__content-tag">
                                #{{ $likeList->shop->category->category }}
                            </li>
                        </ul>
                        <ul class="mypage-like__content__detail">
                            <li class="mypage-like__content-tag">
                                店舗評価点： @if (is_null($likeList->shop->average_rating))
                                評価なし
                                @else
                                {{ number_format($likeList->shop->average_rating, 1) }}
                                @endif
                            </li>
                        </ul>
                        <div class="mypage-like__group-form">
                            <a href="/detail/{{ $likeList->shop->id }}" class="detail-btn">詳しくみる</a>
                            <form class="" method="post" action="/mypage/like">
                                @csrf
                                <button class="like-btn" type="submit" value="like" name="like"><span
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