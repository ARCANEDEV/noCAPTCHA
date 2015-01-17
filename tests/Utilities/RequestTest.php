<?php namespace Arcanedev\NoCaptcha\Tests\Utilities;

use Arcanedev\NoCaptcha\Tests\TestCase;
use Arcanedev\NoCaptcha\Utilities\Request;

class RequestTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Request */
    private $request;

    const REQUEST_CLASS           = 'Arcanedev\\NoCaptcha\\Utilities\\Request';

    const URL_TO_CURL_OR_WHATEVER = 'http://httpbin.org/get';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->request = new Request(self::URL_TO_CURL_OR_WHATEVER);
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
    /**
     * @test
     */
    public function testCanBeInstantiated()
    {
        $this->assertInstanceOf(self::REQUEST_CLASS, $this->request);
    }

    /**
     * @test
     */
    public function testCanCurl()
    {
        $response = $this->request->send();

        $this->assertInternalType('array', $response);
        $this->assertTrue(isset($response['url']));
        $this->assertEquals(self::URL_TO_CURL_OR_WHATEVER, $response['url']);
    }

    /**
     * @test
     */
    public function testCanGetResponseWithTheUglyFileGetContents()
    {
        $response = $this->request->send(false);

        $this->assertInternalType('array', $response);
        $this->assertTrue(isset($response['url']));
        $this->assertEquals(self::URL_TO_CURL_OR_WHATEVER, $response['url']);
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\NoCaptcha\Exceptions\InvalidTypeException
     * @expectedExceptionMessage The url must be a string value, NULL given
     */
    public function testMustThrowInvalidTypeExceptionOnUrl()
    {
        new Request(null);
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\NoCaptcha\Exceptions\ApiException
     * @expectedExceptionMessage The url must not be empty
     */
    public function testMustThrowApiExceptionOnUrl()
    {
        new Request('');
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\NoCaptcha\Exceptions\InvalidUrlException
     * @expectedExceptionMessage The url [trust-me-im-a-valid-url] is invalid
     */
    public function testMustThrowInvalidUrlExceptionOnUrl()
    {
        new Request('trust-me-im-a-valid-url');
    }
}
