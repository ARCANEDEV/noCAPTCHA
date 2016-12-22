<?php namespace Arcanedev\NoCaptcha;

use Arcanedev\Support\PackageServiceProvider as ServiceProvider;

/**
 * Class     NoCaptchaServiceProvider
 *
 * @package  Arcanedev\NoCaptcha
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class NoCaptchaServiceProvider extends ServiceProvider
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Package name.
     *
     * @var string
     */
    protected $package = 'no-captcha';

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the base path of the package.
     *
     * @return string
     */
    public function getBasePath()
    {
        return dirname(__DIR__);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerConfig();
        $this->registerNoCaptcha();
    }

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        parent::boot();

        $this->publishConfig();
        $this->registerValidatorRules($this->app);
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
            'arcanedev.no-captcha',
        ];
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register NoCaptcha service.
     */
    private function registerNoCaptcha()
    {
        $this->singleton(Contracts\NoCaptcha::class, function($app) {
            /** @var  \Illuminate\Config\Repository  $config */
            $config = $app['config'];

            return new NoCaptcha(
                $config->get('no-captcha.secret'),
                $config->get('no-captcha.sitekey'),
                $config->get('no-captcha.lang'),
                $config->get('no-captcha.attributes', [])
            );
        });

        $this->singleton('arcanedev.no-captcha', Contracts\NoCaptcha::class);
    }

    /**
     * Register Validator rules.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    private function registerValidatorRules($app)
    {
        $app['validator']->extend('captcha', function($attribute, $value) use ($app) {
            unset($attribute);
            $ip = $app['request']->getClientIp();

            return $app[Contracts\NoCaptcha::class]->verify($value, $ip);
        });
    }

    /**
     * Register Form Macros.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    private function registerFormMacros($app)
    {
        if ($app->bound('form')) {
            $app['form']->macro('captcha', function($name = null, array $attributes = []) use ($app) {
                return $app[Contracts\NoCaptcha::class]->display($name, $attributes);
            });
        }
    }
}
