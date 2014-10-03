@extends('layouts.base')

@section('title')
Home
@stop

@section('body')
  <h1>You have arrived.</h1>

<div>
    <ul>
        @foreach($items as $i)
            <li>
                {{ $i->getSnippet()->getTitle() }} : {{ $i->getSnippet()->getDescription() }}
            </li>
        @endforeach
    </ul>
</div>
<hr/>

<div>
    <ul>
        @foreach($items as $i)
            <li>
                {{ json_encode($i) }}
            </li>
            <li>
                {{ json_encode($i->getSnippet()) }}
            </li>
        @endforeach
    </ul>
</div>
<hr/>

<div id="signinButton">
  <span class="g-signin"
    data-scope="https://www.googleapis.com/auth/youtube.readonly"
    data-clientid="{{Config::get('ythp.client_id')}}"
    data-redirecturi="postmessage"
    data-accesstype="offline"
    data-cookiepolicy="single_host_origin"
    data-state="{{$anti_forgery_token}}"
    data-callback="signInCallback">
  </span>
</div>
<div id="result"></div>

<div id="logout" >
    <a href="{{route('logout')}}">Logout</a>
</div>

    <!-- Place this asynchronous JavaScript just before your </body> tag -->
    <script type="text/javascript">
      (function() {
       var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
       po.src = 'https://apis.google.com/js/client:plusone.js';
       var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
     })();



    function signInCallback(authResult) {
        console.log('authresult:');
        console.log(authResult);

        if(authResult['status']['signed_in'])
        {
            $('#signinButton').hide();
            $('#logout').show();
        }

        if (authResult['code']) {
            // Nascondi il pulsante di accesso ora che l'utente è autorizzato. Ad esempio:
            $('#signinButton').hide();
            $('#logout').show();

            // Invia il codice al server
            $.ajax({
              type: 'GET',
              url: '{{route('auth')}}',
              //contentType: 'application/octet-stream; charset=utf-8',
              success: function(result) {
                // Gestisci o verifica la risposta del server, se necessario.

                console.log('son qua');
                console.log(result);
                $('#result').html(result);
              },
              data: {
                'code': authResult['code'] ,
                'state': '{{$anti_forgery_token}}'
              }
            });
        } else if (authResult['error']) {
        // Si è verificato un errore.
        // Possibili codici di errore:
        //   "access_denied" - L'utente ha negato l'accesso alla tua app
        //   "immediate_failed" - Impossibile eseguire l'accesso automatico dell'utente
        // console.log('There was an error: ' + authResult['error']);
       }

    }
    </script>


@stop
