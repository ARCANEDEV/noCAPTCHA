<?php namespace Arcanedev\NoCaptcha\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;

/**
 * Class     LaravelTestCase
 *
 * @package  Arcanedev\NoCaptcha\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class LaravelTestCase extends BaseTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register Service Providers
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Arcanedev\LaravelHtml\HtmlServiceProvider::class,
            \Arcanedev\NoCaptcha\NoCaptchaServiceProvider::class,
        ];
    }

    /**
     * Get package aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'Form'      => \Arcanedev\LaravelHtml\Facades\Form::class,
            'HTML'      => \Arcanedev\LaravelHtml\Facades\Html::class,
            'NoCaptcha' => \Arcanedev\NoCaptcha\Facades\NoCaptcha::class,
        ];
    }
}
