<?php

declare(strict_types=1);

namespace Arcanedev\NoCaptcha\Contracts;

/**
 * Interface  NoCaptchaManager
 *
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
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
