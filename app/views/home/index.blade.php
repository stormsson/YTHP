@extends('layouts.base')

@section('title')
Home
@stop

@section('js_top')
<script src="https://apis.google.com/js/client:platform.js?onload=start" async defer></script>
@stop

@section('body')
  <h1>You have arrived.</h1>

<hr/>

<span id="signinButton">
  <span class="g-signin"
    data-scope="https://www.googleapis.com/auth/youtube.readonly https://www.googleapis.com/auth/plus.me"
    data-clientid="{{Config::get('ythp.client_id')}}"
    data-redirecturi="postmessage"
    data-accesstype="offline"
    data-cookiepolicy="single_host_origin"
    data-state="{{$anti_forgery_token}}"
    data-callback="signInCallback">

  </span>
</span>
<div id="result"></div>

<div id="logout" >
    <a href="{{route('logout')}}" onclick="gapi.auth.signOut();">Logout</a>
</div>

    <script type="text/javascript">

    function signInCallback(authResult) {

        console.log('signinCallback + code :');
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
                window.location= '{{route('dashboard_index')}}';
              },
              error: function(jqXHR,textStatus,errorThrown)
              {

                console.log("error:");
                console.log(textStatus);

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
