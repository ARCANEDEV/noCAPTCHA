<?php namespace Arcanedev\NoCaptcha\Tests\Utilities;

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
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const URL_TO_CURL_OR_WHATEVER = 'http://httpbin.org/get';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Request */
    private $request;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->request = new Request;
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->request);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(Request::class, $this->request);
    }

    /** @test */
    public function it_can_curl()
    {
        $response = $this->request->send(self::URL_TO_CURL_OR_WHATEVER);

        $this->assertInternalType('array', $response);
        $this->assertTrue(isset($response['url']));
        $this->assertEquals(self::URL_TO_CURL_OR_WHATEVER, $response['url']);
    }

    /** @test */
    public function it_can_get_response_with_the_ugly_file_get_contents()
    {
        $response = $this->request->send(self::URL_TO_CURL_OR_WHATEVER, false);

        $this->assertInternalType('array', $response);
        $this->assertTrue(isset($response['url']));
        $this->assertEquals(self::URL_TO_CURL_OR_WHATEVER, $response['url']);
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\NoCaptcha\Exceptions\InvalidUrlException
     * @expectedExceptionMessage  The url must be a string value, NULL given
     */
    public function it_must_throw_invalid_type_exception_on_url()
    {
        $this->request->send(null);
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\NoCaptcha\Exceptions\InvalidUrlException
     * @expectedExceptionMessage  The url must not be empty
     */
    public function it_must_throw_api_exception_on_url()
    {
        $this->request->send('');
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\NoCaptcha\Exceptions\InvalidUrlException
     * @expectedExceptionMessage  The url [trust-me-im-a-valid-url] is invalid
     */
    public function it_must_throw_invalid_url_exception_on_url()
    {
        $this->request->send('trust-me-im-a-valid-url');
    }
}
