@extends('layouts.nav')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}?v={{ time() }}">
@endsection

@section('content')
@if(session('error'))
  <div class="alert-message__error">
    {{ session('error') }}
  </div>
@endif
@if(session('message'))
  <div class="alert-message">
    {{ session('message') }}
  </div>
@endif
<form action="/purchase/confirm/{{ $item->id }}" method="post">
  @csrf
  <div class="purchase-layout">
    <div class="purchase-left">
      <div class="purchase-product">
        <div class="purchase-image-wrapper">
          <img src="{{ asset('storage/' . $item->image_url) }}" alt="商品画像">
        </div>
        <div class="purchase-title-wrapper">
          <h2 class="purchase-title">{{ $item->title }}</h2>
          <p class="purchase-price">¥{{ number_format($item->price) }}</p>
        </div>
      </div>
      <div class="purchase-block">
        <label class="purchase-form__label">支払い方法</label>
        <div class="custom-select-wrapper">
          <select class="purchase-form__input" id="payment-select"  name="payment_id" >
          <option value="">選択してください</option>
            @foreach($payments as $payment)
              <option value="{{ $payment->id }}" data-label="{{ $payment->payment_method }}"
              {{ old('payment_id') == $payment->id ? 'selected' : '' }}>
              {{ $payment->payment_method }}
              </option>
              @endforeach
          </select>
        </div>
        <p class="purchase-form__error-message">
          @error('payment_id')
          {{ $message }}
          @enderror
        </p>
      </div>
      <div class="purchase-block">
        <div class="address-form-wrapper">
          <p class="purchase-form__label">配送先</p>
          <a class="change-address" href="{{ url('/purchase/address/' . $item->id) }}">変更する</a>
        </div>
        <div class="shipping-form">
          @php
            $postcode = old('shipping_postcode', $shippingAddress['shipping_postcode'] ?? $user->postcode);
            $address = old('shipping_address', $shippingAddress['shipping_address'] ?? $user->address);
              $building = old('shipping_building', $shippingAddress['shipping_building'] ?? $user->building);
          @endphp
          <p class="shipping-postcode">〒{{ $shippingAddress['shipping_postcode'] }}</p>
          <p class="shipping-address">{{ $shippingAddress['shipping_address'] }}</p>
          <p class="shipping-building">{{ $shippingAddress['shipping_building'] }}</p>
        </div>
          <p class="purchase-form__error-message">
            @error('address')
            {{ $message }}
            @enderror
        </p>
      </div>
    </div>
    <div class="purchase-right">
      <div class="summary-box">
        <div class="summary-row__price">
          <span class="summary-title">商品代金</span>
          <span class="summary-content">¥{{ number_format($item->price) }}</span>
        </div>
        <div class="summary-row">
          <span class="summary-title">支払い方法</span>
          <span class="summary-content" id="selected-payment" >
            {{ optional($payments->firstWhere('id', old('payment_id')))->payment_method ?? '未選択' }}
          </span>
        </div>
      </div>
      <div class="purchase-submit">
        <input class="purchase-form__btn" type="submit" value="購入する">
      </div>
    </div>
  </div>
</form>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('payment-select');
    const display = document.getElementById('selected-payment');

    select.addEventListener('change', function () {
      const selectedOption = select.options[select.selectedIndex];
      const label = selectedOption.dataset.label || '未選択';
      display.textContent = label;
    });
  });
</script>

@endsection