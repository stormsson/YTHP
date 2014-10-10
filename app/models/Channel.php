<?php

class Channel extends Eloquent
{
    public static $unguarded = true;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'channels';
}
