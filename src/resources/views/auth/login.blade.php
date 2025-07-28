@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}?v={{ time() }}">
@endsection

@section('content')
<div class="login-form">
  <h2 class="login-form__heading">ログイン</h2>
  <div class="login-form__inner">
    <form class="login-form__form" action="/login" method="post">
      @csrf
      <div class="login-form__group">
        <label class="login-form__label" for="email">メールアドレス<span>※</span></label>
        <input class="login-form__input" type="text" name="email" id="email" value="{{ old('email') }}">
          @error('email')
          <p class="login-form__error-message">
          {{ $message }}
          </p>
          @enderror
      </div>
      <div class="login-form__group">
        <label class="login-form__label" for="password">パスワード<span>※</span></label>
        <input class="login-form__input" type="password" name="password" id="password">
          @error('password')
          <p class="login-form__error-message">
          {{ $message }}
          </p>
          @enderror
      </div>
      <input class="login-form__btn btn" type="submit" value="ログインする">
      <a class="login-form__link" href="/register">会員登録はこちら</a>
    </form>
  </div>
</div>
@endsection