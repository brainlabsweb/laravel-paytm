<?php

namespace Brainlabsweb\Paytm;

use Illuminate\Support\Facades\Facade;

class Paytm extends Facade
{
    /**
     * Get the binding in IoC Container
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'paytm';
    }
}
