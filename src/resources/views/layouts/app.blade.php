<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>coachtechフリマ</title>
  <link rel="stylesheet" href="{{ asset('css/reset.css') }}?v={{ time() }}">
  <link rel="stylesheet" href="{{ asset('css/common.css') }}?v={{ time() }}">
  @yield('css')
</head>

<body>
  <div class="app">
    <header class="header">
      <a href="{{ url('/') }}">
        <img src="{{ asset('images/logo.svg') }}" alt="ヘッダーロゴ">
      </a>
      @yield('link')
    </header>
    <div class="content">
      @yield('content')
    </div>
  </div>
</body>

</html>