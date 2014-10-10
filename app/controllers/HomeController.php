<?php

class HomeController extends BaseController
{
    protected $googleAuthenticator = false;
    protected $userManager = false;

    public function index()
    {
        $args = array();

        if(Auth::user())
        {
          //  return Redirect::route('dashboard_index');
        }

//var_dump(Session::all());

        $this->googleAuthenticator = App::make('googleauthenticator');
        $args['anti_forgery_token'] = $this->googleAuthenticator->getAntiForgeryToken();

        return Response::make(View::make("home/index", $args), 200);
    }


    public function auth()
    {
        $data = Input::all();
        $this->googleAuthenticator = App::make('googleauthenticator');
        $this->userManager = App::make('usermanager');

        if ($data['state'] != $this->googleAuthenticator->getAntiForgeryToken()) {
            return Response::json(array(), 401);
        }

        $code = $data['code'];
        $client = $this->googleAuthenticator->doAuth($code);

        if ($client) {
            $userInfo = $this->googleAuthenticator->getUserInfo();
            $user = $this->userManager->getOrRegisterByGoogleId($userInfo->id);
            if ($client->getRefreshToken()) {
                $user->google_refresh_token = $client->getRefreshToken();
                $user->save();
            }
        }


        //dd(json_encode(Session::all()));

        return Response::json($user, 200);
    }

    public function logout()
    {
        $this->googleAuthenticator = App::make('googleauthenticator');
        $this->googleAuthenticator->logout();
        return Redirect::route('home');
    }
}
