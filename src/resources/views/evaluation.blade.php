@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/evaluation.css') }}">
@endsection

@section('content')
    <h1 class="review__heading">{{ $shop->name }}の口コミ</h1>
    <table class="review__table">
        <tr class="review__row">
            <th class="review__label">口コミ投稿者</th>
            <th class="review__label">来店日時</th>
            <th class="review__label">人数</th>
            <th class="review__label">評価点</th>
            <th class="review__label">コメント</th>
        </tr>
        @if (isset($reviewLists))
            @foreach ($reviewLists as $reviewList)
                <tr class="review__row">
                    <th class="review__label">{{ $reviewList->user->name }}</th>
                    <th class="review__label">{{ $reviewList->date->format('Y/m/d H:i') }}</th>
                    <th class="review__label">{{ $reviewList->people }}</th>
                    <th class="review__label">{{ $reviewList->point }}</th>
                    <th class="review__label">{{ $reviewList->comment }}</th>
                </tr>
            @endforeach
        @endif
    </table>
@endsection('content')

@if (isset($reviewLists))
    {{ $reviewLists->appends(request()->input())->links('vendor.pagination.custom') }}
@endif
