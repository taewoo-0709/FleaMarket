@extends('layouts.nav')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}?v={{ time() }}" >
@endsection

@section('content')
<div class="tab-wrapper">
  <a class="tab {{ $tab === 'recommend' ? 'active' : ''}}" href="{{ url('/', ['tab' => 'recommend']) }}">おすすめ</a>

  @auth
    <a class="tab {{ $tab === 'mylist' ? 'active' : '' }}" href="{{ url('/') }}?tab=mylist">マイリスト</a>
    @endauth
</div>

<div class="item-list">
  @forelse($items as $item)
    <div class="item-card">
      <div class="item-image">
        <img src="{{ asset('storage/' . $item->image_url) }}" alt="商品画像">
      </div>
      <p class="item-name">{{ $item->name }}</p>
      @if($item->order)
        <span class="sold-label">Sold</span>
      @endif
    </div>
    @empty
    <p>表示できる商品はありません</p>
    @endforelse
</div>
@endsection