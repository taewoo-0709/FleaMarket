@extends('layouts.nav')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile_update.css') }}?v={{ time() }}">
@endsection

@section('content')
@if (session('message'))
  <div class="alert-message">
    {{ session('message') }}
  </div>
@endif
<div class="profile-update-form">
  <h2 class="profile-update-form__heading">プロフィール設定</h2>
  <div class="profile-update-form__inner">
    <form class="profile-update-form__form" action="/mypage/profile" method="post" enctype="multipart/form-data">
      @csrf
      @method('PATCH')
      @if (session('redirect_after_profile_update'))
        <input type="hidden" name="redirect_after_profile_update" value="{{ session('redirect_after_profile_update') }}">
      @endif
      <div class="profile-update-form__group">
        <div class="profile-image-wrapper">
          <div class="profile-image-circle">
            <img id="profilePreview" src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="">
          </div>
          <label class="profile-image__label" for="profile_image">画像を選択する</label>
          <input class="profile-image__input" type="file" name="avatar" id="profile_image">
        </div>
      </div>
      <div class="profile-update-form__group">
        <label class="profile-update-form__label" for="name">ユーザー名<span>※</span></label>
        <input class="profile-update-form__input" type="name" name="name" id="name" value="{{ old('name', $user->name) }}">
        <p class="profile-update-form__error-message">
          @error('name')
          {{ $message }}
          @enderror
        </p>
      </div>
      <div class="profile-update-form__group">
        <label class="profile-update-form__label" for="postal_code">郵便番号<span>※</span></label>
        <input class="profile-update-form__input" type="text" name="postcode" id="postal_code" value="{{ old('postcode', $user->postcode) }}">
        <p class="profile-update-form__error-message">
          @error('postcode')
          {{ $message }}
          @enderror
        </p>
      </div>
      <div class="profile-update-form__group">
        <label class="profile-update-form__label" for="address">住所<span>※</span></label>
        <input class="profile-update-form__input" type="text" name="address" id="address" value="{{ old('address', $user->address) }}">
        <p class="profile-update-form__error-message">
          @error('address')
          {{ $message }}
          @enderror
        </p>
      </div>
      <div class="profile-update-form__group">
        <label class="profile-update-form__label" for="building">建物名</label>
        <input class="profile-update-form__input" type="text" name="building" id="building" value="{{ old('building', $user->building) }}">
      </div>
      <input class="profile-update-form__btn btn" type="submit" value="更新する">
    </form>
  </div>
</div>

<script>
  document.getElementById('profile_image').addEventListener('change', function (e) {
    const file = e.target.files[0];
    const preview = document.getElementById('profilePreview');

    if (file && file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = function (event) {
        preview.src = event.target.result;
      };
      reader.readAsDataURL(file);
    }
  });
</script>

@endsection