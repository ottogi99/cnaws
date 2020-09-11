<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('css/all.css') }}" rel="stylesheet">
  @yield('style')

  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}"></script>
  <script src="{{ asset('js/all.js') }}"></script>
</head>
<body>
  <div id="app">
    <div class="container">
      @include('flash::message')
      @yield('content')
    </div>
  </div>

  <script type="text/javascript">
    // 공통 라이브러리 항목
    $("input:text[numberOnly]").on("keyup", function() {
        $(this).val($(this).val().replace(/[^0-9]/g,""));
    });
  </script>
  @yield('script')
</body>
</html>
