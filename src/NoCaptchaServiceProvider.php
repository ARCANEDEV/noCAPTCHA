<?php namespace Arcanedev\NoCaptcha;

use Arcanedev\LaravelHtml\Contracts\FormBuilder;
use Arcanedev\Support\PackageServiceProvider as ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class     NoCaptchaServiceProvider
 *
 * @package  Arcanedev\NoCaptcha
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class NoCaptchaServiceProvider extends ServiceProvider
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
    public function register()
    {
        parent::register();

        $this->registerConfig();
        $this->registerNoCaptchaManager();
    }

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        parent::boot();

        $this->publishConfig();
        $this->registerFormMacros($this->app);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            Contracts\NoCaptcha::class,
        ];
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    private function registerNoCaptchaManager()
    {
        $this->singleton(Contracts\NoCaptchaManager::class, function ($app) {
            return new NoCaptchaManager($app);
        });

        $this->bind(Contracts\NoCaptcha::class, function (Application $app) {
            /** @var  \Illuminate\Contracts\Config\Repository  $config */
            $config = $app['config'];

            return $app->make(Contracts\NoCaptchaManager::class)->version(
                $config->get('no-captcha.version')
            );
        });
    }

    /**
     * Register Form Macros.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     */
    private function registerFormMacros($app)
    {
        foreach ([FormBuilder::class, 'form'] as $alias) {
            if ($app->bound($alias)) {
                $app[$alias]->macro('captcha', function($name = null) use ($app) {
                    return $app[Contracts\NoCaptcha::class]->input($name);
                });
            }
        }
    }
}
