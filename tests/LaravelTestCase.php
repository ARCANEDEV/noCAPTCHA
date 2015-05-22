<?php namespace Arcanedev\NoCaptcha\Tests;

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
