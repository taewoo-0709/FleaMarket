@extends('layouts.app')

@section('link')
<div class="header-nav__item">
  <input class="header-nav__search" type="text" placeholder="なにをお探しですか？">
</div>
@if (Auth::check())
<div class="header-nav">
  <ul class="header-nav__list">
    <li>
      <form class="header-nav__logout" action="/logout" method="post">
      @csrf
        <button class="header-nav__button">ログアウト</button>
      </form>
    </li>
    <li>
      <a class="header-nav__mypage" href="/mypage">マイページ</a>
    </li>
    <li>
      <a class="header-nav__sell" href="/sell">出品</a>
    </li>
  </ul>
  @else
  <ul class="header-nav__list">
    <li>
      <a class="header-nav__login" href="/login">ログイン</a>
    </li>
    <li>
      <a class="header-nav__mypage" href="/login">マイページ</a>
    </li>
    <li>
      <a class="header-nav__sell" href="/login">出品</a>
    </li>
  </ul>
@endif
</div>
@endsection