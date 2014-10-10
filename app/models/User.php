<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

use Stormsson\Ythp\Connectors\Youtube as YoutubeConnector;

class User extends Eloquent implements UserInterface, RemindableInterface
{
    public static $unguarded = true;

    const STATUS_ACTIVE = true;
    const STATUS_INACTIVE = false;

    use UserTrait, RemindableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password', 'remember_token');



    protected $googleClient = null;

    public function hasGoogleClient()
    {
        return (bool) $this->googleClient;
    }

    public function getGoogleClient()
    {
        return $this->googleClient;
    }

    public function setGoogleClient($client)
    {
        $this->googleClient = $client;
    }

    public function updateYoutubeSubscriptions()
    {
        $connector = new YoutubeConnector($this, $this->googleClient);

        $this->last_yt_subscriptions_update = date('Y-m-d H:i:s');

        $subscriptions = $connector->fetchSubscriptions();

        foreach ($subscriptions as $s) {
            $snippet = $s->getSnippet();
            //dd($snippet);
        }

        $this->save();

        return $subscriptions;
    }
}
