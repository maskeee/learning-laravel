<!DOCTYPE html>
<html>
  <head>
    <title>@yield('title', 'Sample App')</title>
    <link rel="stylesheet" href="/css/app.css">
  </head>
  <body>
    @include('layouts.header')
    <div class="container">
        @yield('content')
        @include('layouts.footer')
    </div>
  </body>
</html>
