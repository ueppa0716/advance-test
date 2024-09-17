@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/emails/mail.css') }}">
@endsection

@section('content')
<div class="mail-form">
    <h2 class="mail-form__heading">ユーザーへのお知らせメール送信</h2>
    <div class="mail-form__inner">
        <p class="mail__success-message">
            @if (session('success'))
            {{ session('success') }}
            @endif
        </p>
        <form class="" action="/mail/send" method="post">
            @csrf
            <div class="mail-form__group">
                <label class="mail-form-label" for="subject">件名:</label>
                <input class="mail-form__input" type="mail" name="subject" id="subject" placeholder="subject"
                    value="{{ old('subject') }}" />
            </div>
            <div class="mail-form__group">
                <label class="mail-form-label" for="message">メッセージ:</label>
                <textarea class="mail-form__input" rows="5" name="content" id="content" placeholder="message"></textarea>
            </div>
            <input class="mail-form-btn" type="submit" value="送信">
        </form>
    </div>
    <p class="mail-form__error-message">
        @error('subject')
        {{ $message }}
        @enderror
    </p>
    <p class="mail-form__error-message">
        @error('content')
        {{ $message }}
        @enderror
    </p>
</div>
@endsection('content')