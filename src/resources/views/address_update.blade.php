@extends('layouts.nav')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address_update.css') }}?v={{ time() }}">
@endsection

@section('content')
<div class="address-update-form">
  <h2 class="address-update-form__heading">住所の変更</h2>
  <div class="address-update-form__inner">
    <form class="address-update-form__form" action="{{ url('/purchase/address/' . $item->id) }}" method="post">
      @csrf
      @method('PATCH')
      <div class="address-update-form__group">
        <label class="address-update-form__label" for="postal_code">郵便番号<span>※</span></label>
        <input class="address-update-form__input" type="text" name="shipping_postcode" id="postal_code">
        <p class="address-update-form__error-message">
          @error('shipping_postcode')
          {{ $message }}
          @enderror
        </p>
      </div>
      <div class="address-update-form__group">
        <label class="address-update-form__label" for="address">住所<span>※</span></label>
        <input class="address-update-form__input" type="text" name="shipping_address" id="address">
        <p class="address-update-form__error-message">
          @error('shipping_address')
          {{ $message }}
          @enderror
        </p>
      </div>
      <div class="address-update-form__group">
        <label class="address-update-form__label" for="building">建物名</label>
        <input class="address-update-form__input" type="text" name="shipping_building" id="building">
      </div>
      <input class="address-update-form__btn" type="submit" value="更新する">
    </form>
  </div>
</div>
@endsection