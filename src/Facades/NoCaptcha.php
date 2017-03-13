<?php namespace Arcanedev\NoCaptcha\Facades;

use Arcanedev\NoCaptcha\Contracts\NoCaptcha as NoCaptchaContract;
use Illuminate\Support\Facades\Facade;

/**
 * Class     NoCaptcha
 *
 * @package  Arcanedev\NoCaptcha\Laravel
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class NoCaptcha extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return NoCaptchaContract::class; }
}
