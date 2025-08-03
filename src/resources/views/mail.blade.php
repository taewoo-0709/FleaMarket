@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mail.css') }}?v={{ time() }}">
@endsection

@section('content')
<div class="mail-form">
  <p class="mail-form-guide">
    登録していただいたメールアドレスに認証メールを送付しました。
  </p>
  <p class="mail-form-guide">
    メール認証を完了してください。
  </p>

  <form action="" method="">
    @csrf
    <button class="mail-form-btn" type="submit">認証はこちらから</button>
  </form>
  <a class="mail-resubmit" href="">認証メールを再送する</a>
</div>
@endsection