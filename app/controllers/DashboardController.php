<?php

class DashboardController extends BaseController
{
    protected $googleAuthenticator = false;
    protected $userManager = false;

    public function index()
    {
        $args = array();

        if(!Auth::user())
        {
            return Redirect::route('home');
        }

        $this->googleAuthenticator = App::make('googleauthenticator');
        $this->userManager = App::make('usermanager');

        $args['anti_forgery_token'] = $this->googleAuthenticator->getAntiForgeryToken();
        $args['items'] = array();

        $client = $this->googleAuthenticator->doAuth();

        var_dump(Session::all());
        //var_dump(Session::get('google_access_token', false));

        if ($client) {
            $userInfo = $this->googleAuthenticator->getUserInfo();
            $user = $this->userManager->getOrRegisterByGoogleId($userInfo->id);
            $user->setGoogleClient($client);

            $youtube_subscriptions = $user->updateYoutubeSubscriptions();


            $args['items'] = $youtube_subscriptions;
        }

        $args['logged'] = $this->googleAuthenticator->isLogged();


        return Response::make(View::make("dashboard/index", $args), 200);
    }

}
