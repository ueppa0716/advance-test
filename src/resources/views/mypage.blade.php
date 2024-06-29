@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')

<div class="mypage__group">
    <p class="mypage__text">{{ $user->name }}さん</p>
</div>

@endsection