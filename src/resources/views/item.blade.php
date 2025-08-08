@extends('layouts.nav')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}?v={{ time() }}">
@endsection

@section('content')
<div class="detail-form__container">
  <div class="detail-form">
    <div class="detail-image-wrapper">
      <img src="{{ asset('storage/' . $item->image_url) }}" alt="商品画像">
    </div>
  </div>

  <div class="detail-info">
    <h2 class="detail-title">{{ $item->title }}</h2>
    <p class="detail-brand">{{ $item->brand }}</p>
    <p class="detail-price">¥{{ number_format($item->price) }} (税込)</p>

    <div class="icon-group">
      <form action="{{ route('like.toggle', $item->id) }}" method="post">
      @csrf
        <div class="icon-item">
          <button class="like-button {{ Auth::check() && Auth::user()->likedItems->contains($item->id) ? 'liked' : '' }}" type="submit">
            @if(Auth::check() && Auth::user()->likedItems->contains($item->id))
            ★
            @else
            ☆
            @endif
          </button>
          <span class="like-number">{{ $item->likedUsers->count() ?? 0 }}</span>
        </div>
      </form>
      <div class="icon-item">
        <img class="icon-image" src="{{ asset('images/comment.svg') }}" alt="コメントアイコン">
        <span class="comment-number">{{ $item->comments->count() ?? 0 }}</span>
      </div>
    </div>
    @if (!$isSeller && !$isPurchased)
      <a class="detail-form__btn btn-link" href="{{ url('/purchase/' . $item->id) }}">購入手続きへ</a>
    @else
      <button class="detail-form__btn btn-disabled" disabled>
        @if ($isSeller)
          自分の商品は購入できません
        @elseif ($isPurchased)
          この商品はすでに購入されています
        @endif
      </button>
    @endif

    <div class="detail-form__group">
      <h3 class="item-explain-detail">商品説明</h3>
      <p class="detail-explain">{{ $item->item_explain }}</p>
    </div>

    <div class="detail-form__group">
    <h3 class="item-explain-detail">商品の情報</h3>
      <div class="item-infomation-group">
        <p class="category-condition">カテゴリー</p>
        <div class="category-values">
          @foreach ($item->categories as $category)
          <span class="detail-category">{{ $category->category_name }}</span>
          @endforeach
        </div>
      </div>
      <div class="item-infomation-group">
        <p class="category-condition">商品の状態</p>
        <span class="detail-condition">{{ $item->condition->condition_kind }}</span>
      </div>
    </div>

    <h3 class="comment-detail">コメント（{{ $item->comments->count() }}件）</h3>
    @foreach($item->comments as $comment)
      <div class="comment-container">
        <div class="comment-user-info">
          <img class="comment-avatar" src="{{ asset('storage/' . ($comment->user->avatar ?? 'default.png')) }}" alt="">
          <p class="user-name">{{ $comment->user->name }}</p>
        </div>
        <p class="comment-data">{{ $comment->comment }}</p>
      </div>
    @endforeach

    <form class="comment-form" action="/item/{{ $item->id }}/comment" method="post">
      @csrf
      <label class="comment__label" for="content">商品へのコメント</label>
      <textarea class="detail-form__textarea" name="comment" maxlength="255" rows="5" id="content"></textarea>
      <p class="detail__error-message">
          @error('comment')
          {{ $message }}
          @enderror
        </p>
      <input class="detail-form__btn" type="submit" value="コメントを送信する">
    </form>
  </div>
</div>

@endsection