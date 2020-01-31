<?php

declare(strict_types=1);

namespace Arcanedev\NoCaptcha\Tests\Utilities;

use Arcanedev\NoCaptcha\Tests\TestCase;
use Arcanedev\NoCaptcha\Utilities\Request;

/**
 * Class     RequestTest
 *
 * @package  Arcanedev\NoCaptcha\Tests\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RequestTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Constants
     | -----------------------------------------------------------------
     */

    const URL_TO_CURL_OR_WHATEVER = 'http://httpbin.org/get';

    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var Request */
    private $request;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp(): void
    {
        parent::setUp();

        $this->request = new Request;
    }

    public function tearDown(): void
    {
        unset($this->request);

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
            \Arcanedev\NoCaptcha\Contracts\Utilities\Request::class,
            \Arcanedev\NoCaptcha\Utilities\Request::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $this->request);
        }
    }

    /** @test */
    public function it_must_throw_invalid_type_exception_on_url(): void
    {
        $this->expectException(\Arcanedev\NoCaptcha\Exceptions\InvalidUrlException::class);
        $this->expectExceptionMessage('The url must be a string value, NULL given');

        $this->request->send(null);
    }

    /** @test */
    public function it_must_throw_api_exception_on_url(): void
    {
        $this->expectException(\Arcanedev\NoCaptcha\Exceptions\InvalidUrlException::class);
        $this->expectExceptionMessage('The url must not be empty');

        $this->request->send('');
    }

    /** @test */
    public function it_must_throw_invalid_url_exception_on_url(): void
    {
        $this->expectException(\Arcanedev\NoCaptcha\Exceptions\InvalidUrlException::class);
        $this->expectExceptionMessage('The url [trust-me-im-a-valid-url] is invalid');

        $this->request->send('trust-me-im-a-valid-url');
    }
}
