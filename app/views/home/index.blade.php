@extends('layouts.base')

@section('title')
Home
@stop

@section('body')
  <h1>You have arrived.</h1>


<span id="signinButton">
  <span
    class="g-signin"
    data-callback="signinCallback"
    data-clientid="{{Config::get('ythp.client_id')}}"
    data-cookiepolicy="single_host_origin"
    data-requestvisibleactions="http://schema.org/AddAction"
    data-scope="https://www.googleapis.com/auth/plus.login profile">
  </span>
</span>

    <!-- Place this asynchronous JavaScript just before your </body> tag -->
    <script type="text/javascript">
      (function() {
       var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
       po.src = 'https://apis.google.com/js/client:plusone.js';
       var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
     })();
    </script>


@stop
