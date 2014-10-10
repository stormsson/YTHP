<?php
namespace Stormsson\Ythp\Connectors;

class Youtube
{
    protected $googleClient = null;
    protected $user = null;

    public function __construct(\User $user, $client)
    {
        $this->googleClient = $client;
        $this->user = $user;
    }

    public function fetchSubscriptions()
    {
        $youtubeService = new \Google_Service_YouTube($this->googleClient);
        $subscriptions = $youtubeService->subscriptions->listSubscriptions('id,snippet', array('mine'=>true));

        $items = $subscriptions->items;
        //var_dump("itemses: ".count($items));
        return $items;
    }
}
