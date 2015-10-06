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
