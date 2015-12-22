<?php namespace Arcanedev\NoCaptcha\Tests\Laravel;

use Arcanedev\NoCaptcha\Laravel\ServiceProvider;
use Arcanedev\NoCaptcha\Tests\LaravelTestCase;

/**
 * Class     ServiceProviderTest
 *
 * @package  Arcanedev\NoCaptcha\Tests\Laravel
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
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

        $this->serviceProvider = $this->app->getProvider('Arcanedev\NoCaptcha\Laravel\ServiceProvider');
    }

    public function tearDown()
    {
        unset($this->serviceProvider);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_provides()
    {
        $expected = ['arcanedev.no-captcha'];

        $this->assertEquals($expected, $this->serviceProvider->provides());
    }
}
