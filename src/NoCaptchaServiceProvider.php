<?php namespace Arcanedev\NoCaptcha;

use Arcanedev\Support\PackageServiceProvider as ServiceProvider;

/**
 * Class NoCaptchaServiceProvider
 * @package Arcanedev\NoCaptcha\Laravel
 */
class NoCaptchaServiceProvider extends ServiceProvider
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Vendor name.
     *
     * @var string
     */
    protected $vendor = 'arcanedev';

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
        $this->registerValidatorRules();
        $this->registerFormMacros();
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
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register NoCaptcha service.
     */
    private function registerNoCaptcha()
    {
        $this->app->bind('arcanedev.no-captcha', function($app) {
            /** @var  \Illuminate\Config\Repository  $config */
            $config  = $app['config'];

            return new NoCaptcha(
                $config->get('no-captcha.secret'),
                $config->get('no-captcha.sitekey'),
                $config->get('no-captcha.lang')
            );
        });
    }

    /**
     * Publish config file.
     */
    protected function publishConfig()
    {
        $this->publishes([
            $this->getConfigFile() => config_path("{$this->package}.php")
        ], 'config');
    }

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
