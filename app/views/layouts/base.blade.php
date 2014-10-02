<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    @section('meta')
    @show

    @section('og_meta')
    @show

    @yield('extra_meta')
    <title> YTHP | </title>

    @section('css_head')

    @show

    @section('js_top')
    @show
  </head>

  <body >

    <div class="main" >
        @section('header')
        @show

        @yield('body')

        @section('footer')
            @include('partials/footer')
        @show

        @section('js_bottom')
            <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
            <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.14/angular.min.js"></script>
            <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.14/angular-animate.min.js"></script>

        @show
    </div>

</body>
</html>
