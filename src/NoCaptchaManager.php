<?php namespace Arcanedev\NoCaptcha;

use Illuminate\Support\Manager;
use Arcanedev\NoCaptcha\Contracts\{
    NoCaptchaManager as NoCaptchaManagerContract,
    NoCaptcha as NoCaptchaContract,
};

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
    public function getDefaultDriver(): string
    {
        return $this->config('version');
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
    public function version($version = null): NoCaptchaContract
    {
        return $this->driver($version);
    }

    /**
     * Create the v2 captcha.
     *
     * @return \Arcanedev\NoCaptcha\NoCaptchaV2
     */
    public function createV2Driver(): NoCaptchaContract
    {
        return $this->buildDriver(NoCaptchaV2::class);
    }

    /**
     * Create the v3 captcha.
     *
     * @return \Arcanedev\NoCaptcha\NoCaptchaV3
     */
    public function createV3Driver(): NoCaptchaContract
    {
        return $this->buildDriver(NoCaptchaV3::class);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Build a driver.
     *
     * @param  string  $driver
     *
     * @return \Arcanedev\NoCaptcha\Contracts\NoCaptcha
     */
    protected function buildDriver(string $driver): NoCaptchaContract
    {
        return $this->container->make($driver, [
            'secret'  => $this->config('secret'),
            'siteKey' => $this->config('sitekey'),
            'locale'  => $this->config('lang') ?: $this->container->getLocale(),
        ]);
    }

    /**
     * Get a value from the config file.
     *
     * @param  string      $key
     *
     * @return mixed
     */
    protected function config(string $key = '')
    {
        return $this->container['config']->get("no-captcha.{$key}");
    }
}
