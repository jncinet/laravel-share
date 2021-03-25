<?php

namespace Jncinet\LaravelShare\Events;

use Illuminate\Database\Eloquent\Model;

class Event
{
    /**
     * @var Model
     */
    public $share;

    /**
     * Event constructor.
     * @param Model $share
     */
    public function __construct(Model $share)
    {
        $this->share = $share;
    }
}