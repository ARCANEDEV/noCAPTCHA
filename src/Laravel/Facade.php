<?php namespace Arcanedev\NoCaptcha\Laravel;

class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'arcanedev.no-captcha'; }
}
