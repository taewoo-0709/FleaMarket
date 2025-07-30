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
        <div class="exhibition-image-wrapper">
          <div class="exhibition-image">
            <label class="exhibition-form__label" for="image">商品画像<span>※</span></label>
            <img id="exhibitionPreview" src="#" alt="">
          </div>
        </div>
        <label class="exhibition-image__label" for="exhibition_image">画像を選択する</label>
        <input class="exhibition-image__input" type="file" name="image_url" id="exhibition_image" accept="image/*">
      </div>

      <div class="exhibition-form__group">
        <h3 class="detail-select">商品の詳細</h3>
          <label class="exhibition-form__label" for="category_id">カテゴリー<span>※</span></label>
            <div class="category-button-group">
              @foreach($categories as $category)
              <div class="category-button">
              <input type="checkbox" name="category_id[]" value="{{ $category->id }}"
                  {{ is_array(old('category_id')) && in_array($category->id, old('category_id')) ? 'checked' : '' }}>
                  {{ $category->category_name }}
              </div>
              @endforeach
            </div>
            <p class="exhibition-form__error-message">
              @error('category_id')
              {{ $message }}
              @enderror
            </p>
      </div>

      <div class="exhibition-form__group">
        <label class="exhibition-form__label" for="condition">商品の状態<span>※</span></label>
        <select class="exhibition-form__input" name="condition_id">
          <option value="">選択してください</option>
          @foreach($conditions as $condition)
            <option value="{{ $condition->id }}" {{ old('condition_id') == $condition->id ? 'selected' : '' }}>
              {{ $condition->condition_kind }}
            </option>
          @endforeach
        </select>
        <p class="exhibition-form__error-message">
          @error('condition_id')
          {{ $message }}
          @enderror
        </p>
      </div>

    <h3 class="detail-select">商品名と説明</h3>
      <div class="exhibition-form__group">
        <label class="exhibition-form__label" for="title">商品名<span>※</span></label>
          <input class="exhibition-form__input" type="text" name="title" value="{{ old('title') }}">
            <p class="exhibition-form__error-message">
              @error('title')
              {{ $message }}
              @enderror
            </p>
      </div>

      <div class="exhibition-form__group">
        <label class="exhibition-form__label" for="brand">ブランド名</label>
        <input class="exhibition-form__input" type="text" name="brand" value="{{ old('brand', $item->brand ?? '') }}">
      </div>

      <div class="exhibition-form__group">
        <label class="exhibition-form__label" for="content">商品の説明<span>※</span></label>
        <textarea class="exhibition-form__textarea" name="content" maxlength="255" rows="7">{{ old('content', $item->content ?? '') }}</textarea>
      </div>

      <div class="exhibition-form__group">
        <label class="exhibition-form__label" for="price">販売価格<span>※</span></label>
        <div class="price-input-wrappar">
          <input class="exhibition-form__input" type="text" name="price" value="{{ old('price', $item->price ?? '') }}">
        </div>
      </div>

      <input class="exhibition-form__btn btn" type="submit" value="出品する">
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