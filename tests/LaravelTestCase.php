<?php

declare(strict_types=1);

namespace Arcanedev\NoCaptcha\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Prophecy\PhpUnit\ProphecyTrait;

/**
 * Class     LaravelTestCase
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class LaravelTestCase extends BaseTestCase
{
    /* -----------------------------------------------------------------
     |  Traits
     | -----------------------------------------------------------------
     */

    use ProphecyTrait;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register Service Providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            \Arcanedev\NoCaptcha\NoCaptchaServiceProvider::class,
        ];
    }
}
