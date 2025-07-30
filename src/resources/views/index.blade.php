@extends('layouts.nav')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}?v={{ time() }}" >
@endsection

@section('content')

@if(session('message'))
  <div class="alert-message">
    {{ session('message') }}
  </div>
@endif

<div class="tab-wrapper">
  <a class="tab {{ ($tab ?? '') === 'recommend' ? 'active' : '' }}" href="{{ url('/') }}?tab=recommend">おすすめ</a>
  <a class="tab {{ ($tab ?? '') === 'mylist' ? 'active' : '' }}" href="{{ url('/') }}?tab=mylist">マイリスト</a>
</div>

@if(!empty($keyword))
  <div class="item-list">
    @forelse($items as $item)
      <div class="item-card">
        <div class="item-image">
          <img src="{{ asset('storage/' . $item->image_url) }}" alt="商品画像">
        </div>
        <p class="item-title">{{ $item->title }}</p>
        @if($item->order)
          <span class="sold-label">Sold</span>
        @endif
      </div>
    @empty
      <div class="empty-alart"></div>
        <p>該当する商品が見つかりませんでした</p>
    @endforelse
  </div>
@else
  <div class="item-list">
    @if(($tab ?? '') === 'mylist')
      @auth
        @forelse($items as $item)
          <div class="item-card">
            <div class="item-image">
              <img src="{{ asset('storage/' . $item->image_url) }}" alt="商品画像">
            </div>
            <p class="item-title">{{ $item->title }}</p>
            @if($item->order)
              <span class="sold-label">Sold</span>
            @endif
          </div>
        @empty
          <div class="empty-alart">
            <p>マイリストに商品はありません</p>
          </div>
        @endforelse
      @else
        <div class="empty-alart">
          <p>ログインが必要です</p>
        </div>
      @endauth
    @else
      @forelse($items as $item)
        <div class="item-card">
          <div class="item-image">
            <img src="{{ asset('storage/' . $item->image_url) }}" alt="商品画像">
          </div>
          <p class="item-title">{{ $item->title }}</p>
          @if($item->order)
            <span class="sold-label">Sold</span>
          @endif
        </div>
      @empty
        <div class="empty-alart">
          <p>おすすめ商品はありません</p>
        </div>
      @endforelse
    @endif
  </div>
@endif

@endsection
