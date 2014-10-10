<?php
namespace Stormsson\Ythp;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class UserManager
{
    public function getOrRegisterByGoogleId($google_id)
    {

        $user = \User::where('google_id', '=', $google_id)->first();
        if (!$user) {
            $user = new \User();
            $user->google_id = $google_id;
            $user->active = \User::STATUS_ACTIVE;
            $user->save();
        }

        $this->login($user);
        return Auth::user();
    }


    public function login(\User $user)
    {
        $user->last_login = date('Y-m-d H:i:s');
        $user->save();
        Auth::login($user);
    }
}
