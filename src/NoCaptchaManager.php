<?php namespace Arcanedev\NoCaptcha;

use Illuminate\Support\Manager;
use Arcanedev\NoCaptcha\Contracts\NoCaptchaManager as NoCaptchaManagerContract;

/**
 * Class     NoCaptchaManager
 *
 * @package  Arcanedev\NoCaptcha
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class NoCaptchaManager extends Manager implements NoCaptchaManagerContract
{
    /* -----------------------------------------------------------------
     |  Getters
     | -----------------------------------------------------------------
     */

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return config('no-captcha.version');
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the NoCaptcha driver by the given version.
     *
     * @param  string|null  $version
     *
     * @return \Arcanedev\NoCaptcha\NoCaptchaV3|\Arcanedev\NoCaptcha\NoCaptchaV2
     */
    public function version($version = null)
    {
        return $this->driver($version);
    }

    /**
     * @return \Arcanedev\NoCaptcha\NoCaptchaV3
     */
    public function createV2Driver()
    {
        return new NoCaptchaV2(
            config('no-captcha.secret'),
            config('no-captcha.sitekey'),
            config('no-captcha.lang') ?: $this->app->getLocale()
        );
    }

    /**
     * @return \Arcanedev\NoCaptcha\NoCaptchaV3
     */
    public function createV3Driver()
    {
        return new NoCaptchaV3(
            config('no-captcha.secret'),
            config('no-captcha.sitekey'),
            config('no-captcha.lang') ?: $this->app->getLocale()
        );
    }
}
