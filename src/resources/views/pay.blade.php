@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pay.css') }}">
@endsection

@section('content')
    <div class="payment-form">
        <h2 class="payment-form__heading">支払メニュー</h2>
        <p class="payment-form__txt">クレジットカードでのお支払が可能です</p>
        <form action="/charge" method="post">
            @csrf
            <script src="https://checkout.stripe.com/checkout.js" class="stripe-button" data-key="{{ env('STRIPE_KEY') }}"
                data-amount="1000" data-name="Stripe Demo" data-label="決済はこちらから！"
                data-description="Online course about integrating Stripe"
                data-image="https://stripe.com/img/documentation/checkout/marketplace.png" data-locale="auto" data-currency="JPY">
            </script>
        </form>
        <div class="message-container">
            <span class="message">
                {{ session('message') }}
            </span>
        </div>
    </div>
@endsection
