<?php

use Arcanedev\NoCaptcha\Contracts\NoCaptcha;
use Arcanedev\NoCaptcha\Contracts\NoCaptchaManager;

if ( ! function_exists('no_captcha')) {
    /**
     * Get the NoCaptcha builder.
     *
     * @param  string|null  $version
     *
     * @return \Arcanedev\NoCaptcha\NoCaptchaV3|\Arcanedev\NoCaptcha\NoCaptchaV2
     */
    function no_captcha($version = null)
    {
        return is_null($version)
            ? app(NoCaptcha::class)
            : app(NoCaptchaManager::class)->version($version);
    }
}
