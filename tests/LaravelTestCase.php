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
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register Service Providers
     *
     * @return array
     */
    protected function getPackageProviders()
    {
        return [
            'Arcanedev\NoCaptcha\Laravel\ServiceProvider'
        ];
    }

    /**
     * Register Aliases
     *
     * @return array
     */
    protected function getPackageAliases()
    {
        return [
            'Facade' => 'Arcanedev\NoCaptcha\Laravel\Facade'
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getEnvironmentSetUp($app)
    {
        //
    }
}
