<?php
namespace Stormsson\Ythp;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class Authenticator
{
    protected $anti_forgery_token   = false;
    protected $google_access_token  = false;
    protected $google_refresh_token  = false;
    protected $google_client = null;

    const SESSION_PREFIX = 'ythp.';

    public function __construct()
    {
        $this->google_client = new \Google_Client();
        $this->google_client->setClientId(Config::get('ythp.client_id'));
        $this->google_client->setClientSecret(Config::get('ythp.client_secret'));
        $this->google_client->setScopes(array('https://www.googleapis.com/auth/youtube.readonly'));
        $this->google_client->setRedirectUri('postmessage');
        $this->google_client->setAccessType('offline');

        if ($aft = Session::get(self::SESSION_PREFIX.'anti_forgery_token', false)) {
            $this->anti_forgery_token = $aft;
        }

        if ($gat = Session::get(self::SESSION_PREFIX.'google_access_token', false)) {
            $this->google_access_token = $gat;
        }

        if ($grt = Session::get(self::SESSION_PREFIX.'google_refresh_token', false)) {
            $this->google_refresh_token = $grt;
        }
    }

    public function getAntiForgeryToken()
    {
        if (false  === $this->anti_forgery_token) {
            $this->generateAntiForgeryToken();
        }

        return $this->anti_forgery_token;
    }

    protected function generateAntiForgeryToken()
    {
        $this->anti_forgery_token = sha1(rand());
        Session::put(self::SESSION_PREFIX.'anti_forgery_token', $this->anti_forgery_token);
    }

    public function getGoogleClient()
    {
        return $this->google_client;
    }

    public function doAuth($code = false)
    {
        $client = $this->getGoogleClient();

        //$client->refreshToken($this->google_refresh_token);
        if ($this->google_access_token) {
            $token = json_decode($this->google_access_token);
            if (time() > $token->created + $token->expires_in) {
                $client->refreshToken($this->google_access_token);
                //$this->logout();
                //return false;
            } else {
                $client->setAccessToken($this->google_access_token);
            }


            Session::put(self::SESSION_PREFIX.'google_access_token', $client->getAccessToken());
            return $client;
        } else {
            // non ho il google access token
            if ($code) {
                $pippo = $client->authenticate($code);

                $this->google_access_token = $client->getAccessToken();
                $this->google_refresh_token = $client->getRefreshToken();


                Session::put(self::SESSION_PREFIX.'google_access_token', $this->google_access_token);
                Session::put(self::SESSION_PREFIX.'google_refresh_token', $this->google_refresh_token);

                return $client;
            }
        }

        return false;
    }

    public function logout()
    {
        $this->anti_forgery_token = false;
        $this->google_access_token = false;
        $this->google_client = null;


        Session::forget(self::SESSION_PREFIX.'anti_forgery_token');
        Session::forget(self::SESSION_PREFIX.'google_access_token');
        Session::forget(self::SESSION_PREFIX.'google_refresh_token');
    }

    public function isLogged()
    {
        return false !== $this->google_client;
    }
}
