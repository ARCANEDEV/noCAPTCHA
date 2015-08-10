<?php namespace Arcanedev\NoCaptcha\Tests;

/**
 * Class LaravelTestCase
 * @package Arcanedev\NoCaptcha\Tests
 */
abstract class LaravelTestCase extends \Orchestra\Testbench\TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

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
            \Illuminate\Html\HtmlServiceProvider::class,
            \Arcanedev\NoCaptcha\Laravel\ServiceProvider::class,
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
            'Form'      => \Illuminate\Html\FormFacade::class,
            'HTML'      => \Illuminate\Html\HtmlFacade::class,
            'NoCaptcha' => \Arcanedev\NoCaptcha\Laravel\Facade::class,
        ];
    }

    /**
     * Call artisan command and return code.
     *
     * @param  string $command
     * @param  array  $parameters
     *
     * @return int
     */
    public function artisan($command, $parameters = [])
    {
    }
}
