<?php
namespace Stormsson\Ythp;

use Stormsson\Ythp\Authenticator;
use Illuminate\Support\ServiceProvider;

class YthpServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('authenticator', function () {
            return new Authenticator();
        });
    }
}
