<?php

class Activity extends Eloquent
{
    public static $unguarded = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'activities';

    public function channel()
    {
        return $this->belongsTo('Channel');
    }
}
