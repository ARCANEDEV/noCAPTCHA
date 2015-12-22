<?php namespace Arcanedev\NoCaptcha\Tests;

/**
 * Class     LaravelTestCase
 *
 * @package  Arcanedev\NoCaptcha\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class LaravelTestCase extends \Orchestra\Testbench\TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
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
            'Illuminate\Html\HtmlServiceProvider',
            'Arcanedev\NoCaptcha\Laravel\ServiceProvider',
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
            'Form'      => 'Illuminate\Html\FormFacade',
            'HTML'      => 'Illuminate\Html\HtmlFacade',
            'NoCaptcha' => 'Arcanedev\NoCaptcha\Laravel\Facade',
        ];
    }
}
