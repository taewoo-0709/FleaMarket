@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile_update.css') }}?v={{ time() }}">
@endsection

@section('link')
<div class="search">
  <input class="search__input" type="text" placeholder="なにをお探しですか？">
</div>
<a class="nav__logout" href="/login">ログアウト</a>
<a class="nav__mypage" href="/mypage">マイページ</a>
<a class="nav__sell" href="/sell">出品</a>
@endsection

@section('content')
<div class="profile-update-form">
  <h2 class="profile-update-form__heading content__heading">プロフィール設定</h2>
  <div class="profile-update-form__inner">
    <form class="profile-update-form__form" action="/mypage/profile" method="post">
      @csrf
      @method('PUT')
      <div class="profile-update-form__group">
        <div class="profile-image">
          <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="">
          <input class="profile-update-form__input--file" type="file" name="profile_image" id="profile_image">
        </div>
      </div>
      <div class="profile-update-form__group">
        <label class="profile-update-form__label" for="name">ユーザー名</label>
        <input class="profile-update-form__input" type="name" name="name" id="name">
        <p class="profile-update-form__error-message">
          @error('name')
          {{ $message }}
          @enderror
        </p>
      </div>
      <div class="profile-update-form__group">
        <label class="profile-update-form__label" for="postal_code">郵便番号</label>
        <input class="profile-update-form__input" type="text" name="postal_code" id="postal_code">
        <p class="profile-update-form__error-message">
          @error('postal_code')
          {{ $message }}
          @enderror
        </p>
      </div>
      <div class="profile-update-form__group">
        <label class="profile-update-form__label" for="address">住所</label>
        <input class="profile-update-form__input" type="text" name="address" id="address">
        <p class="profile-update-form__error-message">
          @error('address')
          {{ $message }}
          @enderror
        </p>
      </div>
      <div class="profile-update-form__group">
        <label class="profile-update-form__label" for="building">建物名</label>
        <input class="profile-update-form__input" type="text" name="building" id="building">
        <p class="profile-update-form__error-message">
          @error('password_confirmation')
          {{ $message }}
          @enderror
        </p>
      </div>
      <input class="profile-update-form__btn btn" type="submit" value="更新する">
    </form>
  </div>
</div>
@endsection