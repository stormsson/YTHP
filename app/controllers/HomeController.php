<?php

use Stormsson\Ythp\Authenticator;

class HomeController extends BaseController
{
    protected $authenticator = false;
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

        $this->authenticator = new Authenticator();
        $args['anti_forgery_token'] = $this->authenticator->getAntiForgeryToken();
        $args['items'] = array();

        $client = $this->authenticator->doAuth();
        //var_dump($client);
        var_dump(Session::all());
        //var_dump(Session::get('google_access_token', false));

        if ($client) {
            $args['items'] = $this->getYoutubeStuff($client);
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

        return $items;
    }

    public function auth()
    {
        $data = Input::all();
        $this->authenticator = new Authenticator();

        if ($data['state'] != $this->authenticator->getAntiForgeryToken()) {
            return Response::json(array(), 401);
        }

        $code = $data['code'];
        $client = $this->authenticator->doAuth($code);

        if ($client) {
            $this->getYoutubeStuff($client);
        }

        //dd(json_encode(Session::all()));

        return Response::json($data, 200);
    }

    public function logout()
    {
        $this->authenticator = new Authenticator();
        $this->authenticator->logout();
        return Redirect::route('home');
    }
}
