<?php namespace Arcanedev\NoCaptcha\Laravel;

use Illuminate\Support\Facades\Facade as IlluminateFacade;

/**
 * Class     Facade
 *
 * @package  Arcanedev\NoCaptcha\Laravel
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Facade extends IlluminateFacade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'arcanedev.no-captcha'; }
}
