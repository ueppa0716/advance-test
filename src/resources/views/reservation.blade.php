@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/reservation.css') }}">
@endsection

@section('content')
<h1 class="reserve__heading">{{ $user->name }}さんの予約履歴リスト</h1>
@if (session('message'))
<div class="message-container">
    <span class="message">
        {{ session('message') }}
    </span>
</div>
@endif
<table class="reserve__table">
    <tr class="reserve__row">
        <th class="reserve-label">店名</th>
        <th class="reserve-label">予約日時</th>
        <th class="reserve-label">人数</th>
        <th class="reserve-label">評価</th>
    </tr>
    @if (isset($reserveLists))
    @foreach ($reserveLists as $reserveList)
    <tr class="reserve__row">
        <th class="reserve-label">{{ $reserveList->shop->name }}</th>
        <th class="reserve-label">{{ $reserveList->date->format('Y/m/d H:i') }}</th>
        <th class="reserve-label">{{ $reserveList->people }}</th>
        <th class="reserve-label">
            <p><a class="modal-label" href="#modal_review_{{ $reserveList->id }}">店舗の評価とコメント</a></p>
            <div class="modal-group" id="modal_review_{{ $reserveList->id }}">
                <form class="review__form" action="/reservation/review" method="post">
                    @csrf
                    <div class="modal__heading"><span class="modal_btn"><a class="modal__btn-text" href="#">x</a></span></div>
                    <label class="review__form-name">店舗名：{{ $reserveList->shop->name }}</label>
                    <label class="review__form-text">評価</label>
                    <select class="review__form-point" name="point" id="point">
                        @for ($i = 1; $i <= 5; $i++) <option value="{{ $i }}" {{ old('point', 1) == $i ? 'selected' : '' }}>
                            {{ $i }}点</option>
                            @endfor
                    </select>
                    <div class="review__form__comment">
                        <label class="review__form-text">コメント</label>
                        <textarea class="review__form__comment-area" name="comment" id="comment" rows="3">{{ old('comment') }}</textarea>
                    </div>
                    <button type="submit" class="review__form-btn">送信</button>
                    <input type="hidden" name="reservation_id" value="{{ $reserveList->id }}">
                </form>
            </div>
        </th>
    </tr>
    @endforeach
    @endif
</table>
@endsection('content')

@if (isset($reserveLists))
{{ $reserveLists->appends(request()->input())->links('vendor.pagination.custom') }}
@endif