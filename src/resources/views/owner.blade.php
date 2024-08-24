@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/owner.css') }}">
@endsection

@section('content')
<p class="owner__heading">店舗代表者メニュー</p>
<p class="owner-form__success-message">
    @if (session('success'))
    {{ session('success') }}
    @endif
</p>
<div class="owner-form">
    <h2 class="owner-form__heading">店舗情報登録</h2>
    <div class="owner-form__inner">
        <form class="owner-form__form" action="/owner" method="post" enctype="multipart/form-data">
            @csrf
            <div class="owner-form__group">
                <input class="owner-form__input" type="text" name="name" id="name" placeholder="Shopname"
                    value="{{ old('name') }}" />
            </div>
            <div class="owner-form__group">
                <select class="owner__form__location-select" name="location">
                    @foreach ($locations as $location)
                    <option value="{{ $location->id }}" {{ old('location') == $location->id ? 'selected' : '' }}>
                        {{ $location->location }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="owner-form__group">
                <select class="owner__form__category-select" name="category">
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->category }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="owner-form__group">
                <textarea class="review__form-comment-area" name="comment" id="comment" rows="3">{{ old('comment') }}</textarea>
            </div>
            <div class="owner-form__group">
                <label for="photo">写真を選択:</label>
                <input type="file" id="photo" name="photo" accept="image/*">
            </div>
            <input class="owner-form__btn btn" type="submit" value="登録" name="shop">
        </form>
    </div>
    <p class="owner-form__error-message">
        @error('name')
        {{ $message }}
        @enderror
    </p>
    <p class="owner-form__error-message">
        @error('email')
        {{ $message }}
        @enderror
    </p>
    <p class="owner-form__error-message">
        @error('password')
        {{ $message }}
        @enderror
    </p>
</div>
@endsection