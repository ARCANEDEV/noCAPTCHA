<?php namespace Arcanedev\NoCaptcha\Tests;

use Arcanedev\NoCaptcha\NoCaptchaServiceProvider;

/**
 * Class NoCaptchaServiceProviderTest
 * @package Arcanedev\NoCaptcha\Tests\Laravel
 */
class NoCaptchaServiceProviderTest extends LaravelTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @var NoCaptchaServiceProvider
     */
    private $provider;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->provider = $this->app->getProvider(NoCaptchaServiceProvider::class);
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->provider);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_get_what_he_provides()
    {
        // This is for 100% code converge
        $this->assertEquals([
            'arcanedev.no-captcha'
        ], $this->provider->provides());
    }
}
