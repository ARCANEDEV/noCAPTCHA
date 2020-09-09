<?php

declare(strict_types=1);

namespace Arcanedev\NoCaptcha\Tests\Laravel;

use Arcanedev\NoCaptcha\{NoCaptchaV2, NoCaptchaV3};
use Arcanedev\NoCaptcha\Tests\LaravelTestCase;

/**
 * Class     NoCaptchaManagerTest
 *
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

    protected function setUp(): void
    {
        parent::setUp();

        $this->manager = $this->app->make(\Arcanedev\NoCaptcha\Contracts\NoCaptchaManager::class);
    }

    protected function tearDown(): void
    {
        unset($this->manager);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated(): void
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
    public function it_can_get_default_driver(): void
    {
        static::assertInstanceOf(
            NoCaptchaV3::class,
            $this->manager->version()
        );
    }

    /** @test */
    public function it_can_get_default_driver_via_helper(): void
    {
        static::assertInstanceOf(
            NoCaptchaV3::class,
            no_captcha()
        );
    }

    /** @test */
    public function it_can_get_driver_by_given_version(): void
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

    /** @test */
    public function it_must_throw_exception_on_unsupported_version(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Driver [v1] not supported.');

        $this->manager->version('v1');
    }
}
