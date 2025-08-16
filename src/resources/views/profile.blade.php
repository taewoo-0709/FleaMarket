@extends('layouts.nav')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}?v={{ time() }}" >
@endsection

@section('content')
<div class="profile-header">
  <div class="profile-image">
    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="">
  </div>
  <div class="profile-info">
    <h2>{{ Auth::user()->name }}</h2>
      <a class="edit-button" href="/mypage/profile">プロフィールを編集</a>
  </div>
</div>

<div class="tab-wrapper">
  <a class="tab {{ ($page ?? '') === 'sell' ? 'active' : '' }}" href="{{ url('/mypage?page=sell') }}">出品した商品</a>
  <a class="tab {{ ($page ?? '') === 'buy' ? 'active' : '' }}" href="{{ url('/mypage?page=buy') }}">購入した商品</a>
</div>
<div class="item-list">
  @forelse($items as $item)
    <div class="item-card">
      <div class="item-image">
        <a href="{{ route('items.detail', ['item_id' => $item->id]) }}">
          <img src="{{ asset('storage/' . $item->image_url) }}" alt="商品画像">
        </a>
      </div>
      <p class="item-title">{{ $item->title }}</p>
      @if($item->order)
        <span class="sold-label">Sold</span>
      @endif
    </div>
    @empty
      <div class="empty-alert-wrapper">
        <p class="empty-message">{{ ($page ?? '') === 'buy' ? '購入した商品はありません' : '出品した商品はありません' }}</p>
      </div>
  @endforelse
</div>
@endsection
