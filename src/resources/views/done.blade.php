@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/done.css') }}">
@endsection

@section('content')

<div class="message-form">
    <p class="message-form__text">ご予約ありがとうございます</p>
    <span class="message-form-btn"><a class="message-form-btn" href="/">戻る</a></span>
</div>

@endsection