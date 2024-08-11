<?php

namespace RealRashid\Cliq\Facade;

use Illuminate\Support\Facades\Facade;

/**
 *
 */
class Cliq extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cliq';
    }
}
