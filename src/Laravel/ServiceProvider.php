<?php namespace Arcanedev\NoCaptcha\Laravel;

use Arcanedev\NoCaptcha\NoCaptcha;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

/**
 * Class     ServiceProvider
 *
 * @package  Arcanedev\NoCaptcha\Laravel
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
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
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->package(
            'arcanedev/no-captcha',
            'no-captcha',
            realpath(dirname(__FILE__) . '/..')
        );

        $this->registerServices();

        $this->registerValidatorRules();

        $this->registerFormMacros();
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        //
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
     * Register Services.
     */
    private function registerServices()
    {
        $this->app->bind('arcanedev.no-captcha', function($app) {
            $config = $app['config']->get('no-captcha::config');

            return new NoCaptcha($config['secret'], $config['sitekey'], $config['lang']);
        });
    }

    /**
     * Register Validator rules.
     */
    private function registerValidatorRules()
    {
        $this->app['validator']->extend('captcha', function($attribute, $value) {
            $ip = $this->app['request']->getClientIp();

            return $this->app['arcanedev.no-captcha']->verify($value, $ip);
        });
    }

    /**
     * Register Form Macros.
     */
    private function registerFormMacros()
    {
        if ($this->app->bound('form')) {
            $this->app['form']->macro('captcha', function($attributes = []) {
                return $this->app['arcanedev.no-captcha']->display($attributes);
            });
        }
    }
}
