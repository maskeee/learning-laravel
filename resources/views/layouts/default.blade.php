<!DOCTYPE html>
<html>
  <head>
    <title>@yield('title', 'Sample App')</title>
    <link rel="stylesheet" href="/css/app.css">
  </head>
  <body>
    @include('layouts.header')
    <div class="container">
        @include('shared.messages')
        @yield('content')
        @include('layouts.footer')
    </div>
     <script src="/js/app.js"></script>
  </body>
</html>
