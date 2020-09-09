<?php

declare(strict_types=1);

namespace Arcanedev\NoCaptcha;

use Arcanedev\NoCaptcha\Contracts\NoCaptcha as NoCaptchaContract;
use Arcanedev\NoCaptcha\Contracts\NoCaptchaManager as NoCaptchaManagerContract;
use Arcanedev\Support\Providers\PackageServiceProvider as ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;

/**
 * Class     NoCaptchaServiceProvider
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class NoCaptchaServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * Package name.
     *
     * @var string
     */
    protected $package = 'no-captcha';

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        parent::register();

        $this->registerConfig();
        $this->registerNoCaptchaManager();
    }

    /**
     * Bootstrap the application events.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishConfig();
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            NoCaptchaContract::class,
            NoCaptchaManagerContract::class,
        ];
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register noCaptcha manager & contract.
     */
    private function registerNoCaptchaManager(): void
    {
        $this->singleton(NoCaptchaManagerContract::class, NoCaptchaManager::class);

        $this->bind(NoCaptchaContract::class, function (Application $app) {
            /** @var  \Illuminate\Contracts\Config\Repository  $config */
            $config = $app['config'];

            return $app->make(NoCaptchaManagerContract::class)->version(
                $config->get('no-captcha.version')
            );
        });
    }
}
