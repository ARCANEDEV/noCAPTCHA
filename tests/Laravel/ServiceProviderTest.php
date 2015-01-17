<?php namespace Arcanedev\NoCaptcha\Tests\Laravel;

use Arcanedev\NoCaptcha\Laravel\ServiceProvider;
use Arcanedev\NoCaptcha\Tests\LaravelTestCase;

class ServiceProviderTest extends LaravelTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @var ServiceProvider
     */
    private $serviceProvider;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->serviceProvider = new ServiceProvider($this->app);
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->serviceProvider);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @test
     */
    public function testCanGetWhatHeProvides()
    {
        // This is for 100% code converge
        $this->assertEquals([
            'arcanedev.no-captcha'
        ], $this->serviceProvider->provides());
    }
}
