@extends('layouts.nav')

@section('css')
<link rel="stylesheet" href="{{ asset('css/exhibition.css') }}?v={{ time() }}">
@endsection

@section('content')
<div class="exhibition-form">
  <h2 class="exhibition-form__heading">商品の出品</h2>
  <div class="exhibition-form__inner">
    <form class="exhibition-form__form" action="/sell" method="post" enctype="multipart/form-data">
      @csrf
      <div class="exhibition-form__group">
        <label class="exhibition-form__label">商品画像<span>※</span></label>
          <div class="exhibition-image-wrapper">
            <img id="exhibitionPreview" src="#" alt="">
            <label class="exhibition-image__label" for="exhibition_image">画像を選択する</label>
            <input class="exhibition-image__input" type="file" name="image_url" id="exhibition_image" accept="image/*">
          </div>
          <p class="exhibition-form__error-message">
            @error('image_url')
            {{ $message }}
            @enderror
          </p>
      </div>
      <div class="exhibition-form__group">
        <h3 class="detail-select">商品の詳細</h3>
          <label class="exhibition-form__label">カテゴリー<span>※</span></label>
            <div class="category-button-group">
              @foreach($categories as $category)
                <label class="category-button">
                  <input type="checkbox" name="category_id[]" value="{{ $category->id }}"
                  {{ is_array(old('category_id')) && in_array($category->id, old('category_id')) ? 'checked' : '' }}>
                  <span>{{ $category->category_name }}</span>
                </label>
              @endforeach
            </div>
            <p class="exhibition-form__error-message">
              @error('category_id')
              {{ $message }}
              @enderror
            </p>
      </div>
      <div class="exhibition-form__group">
        <label class="exhibition-form__label" for="condition_id">商品の状態<span>※</span></label>
        <div class="custom-select-wrapper">
          <select class="exhibition-form__input" name="condition_id" id="condition_id">
            <option value="">選択してください</option>
            @foreach($conditions as $condition)
              <option value="{{ $condition->id }}" {{ old('condition_id') == $condition->id ? 'selected' : '' }}>
                {{ $condition->condition_kind }}
              </option>
            @endforeach
          </select>
        </div>
        <p class="exhibition-form__error-message">
          @error('condition_id')
          {{ $message }}
          @enderror
        </p>
      </div>
      <h3 class="detail-select">商品名と説明</h3>
      <div class="exhibition-form__group">
        <label class="exhibition-form__label" for="title">商品名<span>※</span></label>
          <input class="exhibition-form__input" type="text" name="title" id="title" value="{{ old('title') }}">
          <p class="exhibition-form__error-message">
            @error('title')
            {{ $message }}
            @enderror
          </p>
      </div>
      <div class="exhibition-form__group">
        <label class="exhibition-form__label" for="brand">ブランド名</label>
        <input class="exhibition-form__input" type="text" name="brand" id="brand" value="{{ old('brand', $item->brand ?? '') }}">
      </div>
      <div class="exhibition-form__group">
        <label class="exhibition-form__label" for="content">商品の説明<span>※</span></label>
        <textarea class="exhibition-form__textarea" name="item_explain" maxlength="255" rows="7" id="content">{{ old('item_explain', $item->item_explain ?? '') }}</textarea>
        <p class="exhibition-form__error-message">
          @error('item_explain')
          {{ $message }}
          @enderror
        </p>
      </div>
      <div class="exhibition-form__group">
        <label class="exhibition-form__label" for="price">販売価格<span>※</span></label>
        <div class="price-input-wrapper">
          <span class="yen-symbol">¥</span>
          <input class="exhibition-form__input" type="text" name="price" id="price" value="{{ old('price', $item->price ?? '') }}">
        </div>
        <p class="exhibition-form__error-message">
          @error('price')
          {{ $message }}
          @enderror
        </p>
      </div>
      <input class="exhibition-form__btn" type="submit" value="出品する">
    </form>
  </div>
</div>

<script>
  document.getElementById('exhibition_image').addEventListener('change', function (event) {
    const file = event.target.files[0];
    const preview = document.getElementById('exhibitionPreview');

    if (file) {
      const reader = new FileReader();

      reader.onload = function (e) {
        preview.src = e.target.result;
        preview.style.display = 'block';
      };

      reader.readAsDataURL(file);
    }
  });
</script>

@endsection