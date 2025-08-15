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
  <a class="tab {{ ($tab ?? '') === 'recommend' ? 'active' : '' }}" href="{{ url('/') }}?tab=recommend&keyword={{ urlencode($keyword ?? '') }}">おすすめ</a>
  <a class="tab {{ ($tab ?? '') === 'mylist' ? 'active' : '' }}" href="{{ url('/') }}?tab=mylist&keyword={{ urlencode($keyword ?? '') }}">マイリスト</a>
</div>

@if(!empty($keyword))
  <div class="item-list">
    @forelse($items ?? [] as $item)
      <div class="item-card">
        <div class="item-image">
          <a href="{{ route('items.detail', ['item_id' => $item->id ?? 0]) }}">
            <img src="{{ asset('storage/' . ($item->image_url ?? 'default.png')) }}" alt="商品画像">
          </a>
        </div>
        <p class="item-title">{{ $item->title ?? 'タイトルなし' }}</p>
        @if(!empty($item->order))
          <span class="sold-label">Sold</span>
        @endif
      </div>
    @empty
      <div class="empty-alert-wrapper">
        <p class="empty-message">該当する商品が見つかりませんでした</p>
      </div>
    @endforelse
  </div>
@else
  <div class="item-list">
    @if(($tab ?? '') === 'mylist')
      @auth
        @forelse($items ?? [] as $item)
          <div class="item-card">
            <div class="item-image">
              <a href="{{ route('items.detail', ['item_id' => $item->id ?? 0]) }}">
                <img src="{{ asset('storage/' . ($item->image_url ?? 'default.png')) }}" alt="商品画像">
              </a>
            </div>
            <p class="item-title">{{ $item->title ?? 'タイトルなし' }}</p>
            @if(!empty($item->order))
              <span class="sold-label">Sold</span>
            @endif
          </div>
        @empty
          <div class="empty-alert-wrapper">
            <p class="empty-message">マイリストに商品はありません</p>
          </div>
        @endforelse
      @else
        <div class="empty-alert-wrapper">
          <p class="empty-message">ログインが必要です</p>
        </div>
      @endauth
    @else
      @forelse($items ?? [] as $item)
        <div class="item-card">
          <div class="item-image">
            <a href="{{ route('items.detail', ['item_id' => $item->id ?? 0]) }}">
              <img src="{{ asset('storage/' . ($item->image_url ?? 'default.png')) }}" alt="商品画像">
            </a>
          </div>
          <p class="item-title">{{ $item->title ?? 'タイトルなし' }}</p>
          @if(!empty($item->order))
            <span class="sold-label">Sold</span>
          @endif
        </div>
      @empty
        <div class="empty-alert-wrapper">
          <p class="empty-message">おすすめ商品はありません</p>
        </div>
      @endforelse
    @endif
  </div>
@endif

@endsection