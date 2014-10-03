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

        $client = $this->doAuth();
        //var_dump($client);
        //var_dump(Session::all());
        //var_dump(Session::get('google_access_token', false));

        if ($client) {
            $this->getYoutubeStuff($client);
        }

        return Response::make(View::make("home/index", $args), 200);
    }


    protected function getYoutubeStuff($client)
    {
        $youtubeService = new Google_Service_YouTube($client);
        $subscriptions = $youtubeService->subscriptions->listSubscriptions('id,snippet', array('mine'=>true));

        $items = $subscriptions->items;
        foreach ($items as $i) {
            //var_dump($i->getSnippet());
        }
    }

    protected function doAuth($code = false)
    {
        $client = new Google_Client();
        $client->setClientId(Config::get('ythp.client_id'));
        $client->setClientSecret(Config::get('ythp.client_secret'));
        $client->setScopes(array('https://www.googleapis.com/auth/youtube.readonly'));
        $client->setRedirectUri('postmessage');

        if (Session::get('google_access_token', false)) {

            $token = json_decode(Session::get('google_access_token'));
            $client->setAccessToken(Session::get('google_access_token'));
            //$client->refreshToken($token->refresh_token);
            Session::put('google_access_token', $client->getAccessToken());
            return $client;
        } else {
            // non ho il google access token
            if ($code) {
                $client->authenticate($code);
                Session::put('google_access_token', $client->getAccessToken());
                //die('here2');
                return $client;
            }
        }

        return false;
    }


    public function auth()
    {
        $data = Input::all();

        if ($data['state'] != Session::get('anti_forgery_token')) {
            return Response::json(array(), 401);
        }

        $code = $data['code'];
        $client = $this->doAuth($code);

        $this->getYoutubeStuff($client);
        //dd(json_encode(Session::all()));

        return Response::json($data, 200);
    }

    public function logout()
    {
        Session::flush();
        return Redirect::route('home');
    }
}
