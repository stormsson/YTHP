@extends('layouts.base')

@section('title')
My Dashboard
@stop

@section('body')
  <h1>You have arrived.</h1>

<div>
    <ul>
        @if($items)
          @foreach($items as $i)
              <li>
                  {{ $i->getSnippet()->getTitle() }} : {{ $i->getSnippet()->getDescription() }}
              </li>
          @endforeach
        @endif
    </ul>
</div>
<hr/>

YT:
<div>
    <ul>
        @if($items)
          @foreach($items as $i)
              <li>
                  {{ json_encode($i) }}
              </li>
              <li>
                  {{ json_encode($i->getSnippet()) }}
              </li>
          @endforeach
        @endif
    </ul>
</div>
<hr/>

<div id="logout" >
    <a href="{{route('logout')}}" onclick="gapi.auth.signOut();">Logout</a>
</div>

@stop
