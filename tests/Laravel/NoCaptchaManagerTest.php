<?php namespace Arcanedev\NoCaptcha\Tests\Laravel;

use Arcanedev\NoCaptcha\NoCaptchaV2;
use Arcanedev\NoCaptcha\NoCaptchaV3;
use Arcanedev\NoCaptcha\Tests\LaravelTestCase;

/**
 * Class     NoCaptchaManagerTest
 *
 * @package  Arcanedev\NoCaptcha\Tests\Laravel
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class NoCaptchaManagerTest extends LaravelTestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \Arcanedev\NoCaptcha\Contracts\NoCaptchaManager */
    protected $manager;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    protected function setUp()
    {
        parent::setUp();

        $this->manager = $this->app->make(\Arcanedev\NoCaptcha\Contracts\NoCaptchaManager::class);
    }

    protected function tearDown()
    {
        unset($this->manager);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        $expectations = [
            \Illuminate\Support\Manager::class,
            \Arcanedev\NoCaptcha\Contracts\NoCaptchaManager::class,
            \Arcanedev\NoCaptcha\NoCaptchaManager::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $this->manager);
        }
    }

    /** @test */
    public function it_can_get_default_driver()
    {
        static::assertInstanceOf(
            NoCaptchaV3::class,
            $this->manager->version()
        );
    }

    /** @test */
    public function it_can_get_default_driver_via_helper()
    {
        static::assertInstanceOf(
            NoCaptchaV3::class,
            no_captcha()
        );
    }

    /** @test */
    public function it_can_get_driver_by_given_version()
    {
        static::assertInstanceOf(
            NoCaptchaV3::class,
            $this->manager->version('v3')
        );

        static::assertInstanceOf(
            NoCaptchaV2::class,
            $this->manager->version('v2')
        );
    }
}
