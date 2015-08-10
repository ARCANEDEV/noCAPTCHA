<?php namespace Arcanedev\NoCaptcha\Laravel;

use Arcanedev\NoCaptcha\NoCaptcha;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

/**
 * Class ServiceProvider
 * @package Arcanedev\NoCaptcha\Laravel
 */
class ServiceProvider extends IlluminateServiceProvider
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $configFile = __DIR__ . '/../../config/no-captcha.php';

        $this->mergeConfigFrom($configFile, 'no-captcha');
        $this->publishes([
            $configFile => config_path('no-captcha.php')
        ], 'config');

        $this->registerValidatorRules();
        $this->registerFormMacros();
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->bind('arcanedev.no-captcha', function() {
            $config = config('no-captcha');

            return new NoCaptcha($config['secret'], $config['sitekey'], $config['lang']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['arcanedev.no-captcha'];
    }

    /* ------------------------------------------------------------------------------------------------
     |  Package Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register Validator rules.
     */
    private function registerValidatorRules()
    {
        $app = $this->app;

        $this->app['validator']->extend('captcha', function($attribute, $value) use ($app) {
            $ip = $app['request']->getClientIp();

            return $app['arcanedev.no-captcha']->verify($value, $ip);
        });
    }

    /**
     * Register Form Macros.
     */
    private function registerFormMacros()
    {
        if ($this->app->bound('form')) {
            $this->app['form']->macro('captcha', function($attributes = []) {
                return app('arcanedev.no-captcha')->display($attributes);
            });
        }
    }
}
