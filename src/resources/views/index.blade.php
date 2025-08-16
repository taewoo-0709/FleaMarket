@extends('layouts.nav')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}?v={{ time() }}" >
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

<div class="tab-wrapper">
  <a class="tab {{ $tab === 'recommend' ? 'active' : '' }}" href="{{ url('/') }}?tab=recommend{{ !empty($keyword) ? '&keyword=' . urlencode($keyword) : '' }}">おすすめ</a>
  <a class="tab {{ $tab === 'mylist' ? 'active' : '' }}" href="{{ url('/') }}?tab=mylist{{ !empty($keyword) ? '&keyword=' . urlencode($keyword) : '' }}">マイリスト</a>
</div>

<div class="item-list">
  @if($tab === 'mylist' && !Auth::check())
    <div class="empty-alert-wrapper">
      <p class="empty-message">ログインが必要です</p>
    </div>
  @elseif($items->isEmpty())
    <div class="empty-alert-wrapper">
      <p class="empty-message">
        {{ $tab === 'mylist' ? 'マイリストに商品はありません' : 'おすすめ商品はありません' }}
      </p>
    </div>
  @else
    @foreach($items as $item)
      <div class="item-card">
        <div class="item-image">
          <a href="{{ route('items.detail', ['item_id' => $item->id]) }}">
            <img src="{{ asset('storage/' . ($item->image_url ?? 'default.png')) }}" alt="商品画像">
          </a>
        </div>
        <p class="item-title">{{ $item->title }}</p>
        @if(!empty($item->order))
          <span class="sold-label">Sold</span>
        @endif
      </div>
    @endforeach
  @endif
</div>

@endsection