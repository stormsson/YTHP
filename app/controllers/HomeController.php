<?php

class HomeController extends BaseController
{
    /*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

    public function index()
    {
        $args = array();

        $anti_forgery_token = Session::get('anti_forgery_token', false);

        if (!$anti_forgery_token) {
            $anti_forgery_token = sha1(rand());
            Session::put('anti_forgery_token', $anti_forgery_token);
        }
        $args['anti_forgery_token'] = $anti_forgery_token;

        return Response::make(View::make("home/index", $args), 200);
    }



    public function storeToken()
    {
        $data = Input::all();

        if ($data['state'] != Session::get('anti_forgery_token')) {
            return Response::json(array(), 401);
        }

        $code = $data['code'];

        $client = new Google_Client();
        $client->setClientId(Config::get('ythp.client_id'));
        $client->setClientSecret(Config::get('ythp.client_secret'));
        $client->setScopes(array('https://www.googleapis.com/auth/youtube.readonly'));
        $client->setRedirectUri('postmessage');

        if (!Session::get('google_access_token')) {
            $client->authenticate($code);
            Session::put('google_access_token', $client->getAccessToken());
        } else {
            $token = json_decode(Session::get('google_access_token'));
            $client->refreshToken($token->refresh_token);
            Session::put('google_access_token', $client->getAccessToken());
        }

        $youtubeService = new Google_Service_YouTube($client);
        $subscriptions = $youtubeService->subscriptions->listSubscriptions('id,snippet', array('mine'=>true));

        $items = $subscriptions->items;
        foreach ($items as $i) {
            var_dump($i->getSnippet());
        }
        dd();




        //$reqUrl = 'https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=' . $token->access_token;
        //$req = new Google_Http_Request($reqUrl);

        //$tokenInfo = json_decode($client->getIo()->authenticatedRequest($req)->getResponseBody());

        // In caso di errore nei dati del token, termina l'operazione.
        if ($tokenInfo->error) {
            return new Response($tokenInfo->error, 500);
        }

        return Response::json($data, 200);
    }
}
