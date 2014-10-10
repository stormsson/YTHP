<?php

class Subscription extends Eloquent
{
    public static $unguarded = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'subscriptions';

    public function user()
    {
        return $this->belongsTo('User');
    }
}
