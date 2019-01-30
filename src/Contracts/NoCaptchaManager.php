<?php namespace Arcanedev\NoCaptcha\Contracts;

/**
 * Interface     NoCaptchaManager
 *
 * @package  Arcanedev\NoCaptcha\Contracts
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface NoCaptchaManager
{
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
    public function version($version = null);
}
