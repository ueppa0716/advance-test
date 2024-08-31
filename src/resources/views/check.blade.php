@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/check.css') }}">
@endsection

@section('content')
<h1 class="reserve__heading">{{ $userShop->name }}の予約状況</h1>
<table class="reserve__table">
    <tr class="reserve__row">
        <th class="reserve__label">来店日時</th>
        <th class="reserve__label">予約者名</th>
        <th class="reserve__label">人数</th>
    </tr>
    @if(!$reserveLists->isEmpty())
    @foreach ($reserveLists as $reserveList)
    <tr class="reserve__row">
        <th class="reserve__label">{{ $reserveList->date->format('Y/m/d H:i') }}</th>
        <th class="reserve__label">{{ $reserveList->user->name }}</th>
        <th class="reserve__label">{{ $reserveList->people }}</th>
    </tr>
    @endforeach
    @endif
</table>
@endsection('content')

@if (isset($reserveLists))
{{ $reserveLists->appends(request()->input())->links('vendor.pagination.custom') }}
@endif