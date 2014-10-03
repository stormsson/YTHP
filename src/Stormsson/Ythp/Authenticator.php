<?php
namespace Stormsson\Ythp;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class Authenticator
{
    protected $anti_forgery_token   = false;
    protected $google_access_token  = false;
    protected $google_client = null;

    const SESSION_PREFIX = 'ythp.';

    public function __construct()
    {
        $this->google_client = new \Google_Client();
        $this->google_client->setClientId(Config::get('ythp.client_id'));
        $this->google_client->setClientSecret(Config::get('ythp.client_secret'));
        $this->google_client->setScopes(array('https://www.googleapis.com/auth/youtube.readonly'));
        $this->google_client->setRedirectUri('postmessage');

        if ($aft = Session::get(self::SESSION_PREFIX.'anti_forgery_token', false)) {
            $this->anti_forgery_token = $aft;
        }

        if ($gat = Session::get(self::SESSION_PREFIX.'google_access_token', false)) {
            $this->google_access_token = $gat;
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

        if ($this->google_access_token) {
            $token = json_decode($this->google_access_token);
            if (time() > $token->created + $token->expires_in) {
                $this->logout();
                return false;
            }

            $client->setAccessToken($this->google_access_token);
            Session::put(self::SESSION_PREFIX.'google_access_token', $client->getAccessToken());
            return $client;
        } else {
            // non ho il google access token
            if ($code) {
                $client->authenticate($code);
                $this->google_access_token = $client->getAccessToken();
                Session::put(self::SESSION_PREFIX.'google_access_token', $this->google_access_token);

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
    }
}
