<?php

namespace Neo\Gist\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Neo\Gist\Gist
 */
class Gist extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Neo\Gist\Gist::class;
    }
}
