<?php
namespace Stormsson\Ythp;

use Stormsson\Ythp\GoogleAuthenticator;
use Stormsson\Ythp\UserManager;

use Illuminate\Support\ServiceProvider;

class YthpServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton('googleauthenticator', function () {
            return new GoogleAuthenticator();
        });

        $this->app->singleton('usermanager', function () {
            return new UserManager();
        });
    }
}
