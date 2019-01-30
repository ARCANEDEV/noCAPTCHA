<?php namespace Arcanedev\NoCaptcha\Tests;

use Arcanedev\NoCaptcha\NoCaptchaV3;
use Arcanedev\NoCaptcha\Utilities\Request;
use Prophecy\Argument;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class     NoCaptchaV3Test
 *
 * @package  Arcanedev\NoCaptcha\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class NoCaptchaV3Test extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \Arcanedev\NoCaptcha\NoCaptchaV3 */
    private $noCaptcha;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp()
    {
        parent::setUp();

        $this->noCaptcha = $this->createCaptcha();
    }

    public function tearDown()
    {
        unset($this->noCaptcha);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        static::assertInstanceOf(NoCaptchaV3::class, $this->noCaptcha);
        static::assertSame(
            '<script src="https://www.google.com/recaptcha/api.js?render=site-key"></script>',
            $this->noCaptcha->script()->toHtml()
        );
    }

    /** @test */
    public function it_can_be_instantiated_with_defaults()
    {
        static::assertSame(
            '<input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">',
            $this->noCaptcha->input()->toHtml()
        );
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\NoCaptcha\Exceptions\ApiException
     * @expectedExceptionMessage  The secret key must be a string value, NULL given
     */
    public function it_must_throw_invalid_type_exception_on_secret_key()
    {
        $this->createCaptcha(null, null);
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\NoCaptcha\Exceptions\ApiException
     * @expectedExceptionMessage  The secret key must not be empty
     */
    public function it_must_throw_api_exception_on_empty_secret_key()
    {
        $this->createCaptcha('   ', null);
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\NoCaptcha\Exceptions\ApiException
     * @expectedExceptionMessage  The site key must be a string value, NULL given
     */
    public function it_must_throw_invalid_type_exception_on_site_key()
    {
        $this->createCaptcha('secret', null);
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\NoCaptcha\Exceptions\ApiException
     * @expectedExceptionMessage  The site key must not be empty
     */
    public function it_must_throw_api_exception_on_empty_site_key()
    {
        $this->createCaptcha('secret', '   ');
    }

    /** @test */
    public function it_can_switch_locale()
    {
        $this->noCaptcha->setLang('fr');

        static::assertInstanceOf(NoCaptchaV3::class, $this->noCaptcha);
        static::assertSame(
            '<script src="https://www.google.com/recaptcha/api.js?hl=fr&render=site-key"></script>',
            $this->noCaptcha->script()->toHtml()
        );
    }

    /** @test */
    public function it_can_render_script_tag()
    {
        static::assertSame(
            '<script src="https://www.google.com/recaptcha/api.js?render=site-key"></script>',
            $this->noCaptcha->script()->toHtml()
        );

        // Echo out only once
        static::assertEmpty($this->noCaptcha->script()->toHtml());
    }

    /** @test */
    public function it_can_render_script_tag_with_lang()
    {
        $this->noCaptcha->setLang('fr');

        static::assertSame(
            '<script src="https://www.google.com/recaptcha/api.js?hl=fr&render=site-key"></script>',
            $this->noCaptcha->script()->toHtml()
        );

        // Not twice
        static::assertEmpty($this->noCaptcha->script()->toHtml());
    }

    /** @test */
    public function it_can_render_script_tag_with_onload_callback()
    {
        static::assertSame(
            '<script src="https://www.google.com/recaptcha/api.js?render=site-key&onload=no_captcha_onload"></script>',
            $this->noCaptcha->script('no_captcha_onload')->toHtml()
        );

        // Not twice
        static::assertEmpty($this->noCaptcha->script()->toHtml());
    }

    /** @test */
    public function it_can_render_api_script()
    {
        static::assertSame(
            "<script>
                window.noCaptcha = {
                    render: function(action, callback) {
                        grecaptcha.execute('site-key', {action})
                              .then(callback);
                    }
                }
            </script>",
            $this->noCaptcha->getApiScript()->toHtml()
        );
    }

    /** @test */
    public function it_can_verify()
    {
        static::assertNull($this->noCaptcha->getLastResponse());

        /** @var  \Arcanedev\NoCaptcha\Utilities\Request  $request */
        $request = tap($this->prophesize(Request::class), function ($request) {
            $request->send(Argument::type('string'))->willReturn('{"success": true}');
        })->reveal();

        $response = $this->noCaptcha
            ->setRequestClient($request)
            ->verify('re-captcha-response');

        static::assertTrue($response->isSuccess());

        static::assertSame(
            $response,
            $this->noCaptcha->getLastResponse()
        );
    }

    /** @test */
    public function it_can_verify_psr7_request()
    {
        /** @var  \Arcanedev\NoCaptcha\Utilities\Request  $client */
        $client = tap($this->prophesize(Request::class), function ($client) {
            $client->send(Argument::type('string'))->willReturn(
                '{"success": true}'
            );
        })->reveal();

        /** * @var  \Psr\Http\Message\ServerRequestInterface  $request */
        $request = tap($this->prophesize(ServerRequestInterface::class), function ($request) {
            $request->getParsedBody()->willReturn([
                'g-recaptcha-response' => true,
            ]);
            $request->getServerParams()->willReturn([
                'REMOTE_ADDR' => '127.0.0.1'
            ]);
        })->reveal();

        $response = $this->noCaptcha
            ->setRequestClient($client)
            ->verifyRequest($request);

        static::assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_verify_with_fails()
    {
        $response = $this->noCaptcha->verify('');

        static::assertFalse($response->isSuccess());

        $request = tap($this->prophesize(Request::class), function ($request) {
            $request->send(Argument::type('string'))->willReturn(
                json_encode([
                    'success'     => false,
                    'error-codes' => 'invalid-input-response'
                ])
            );
        })->reveal();

        $response = $this->noCaptcha
            ->setRequestClient($request)
            ->verify('re-captcha-response');

        static::assertFalse($response->isSuccess());
    }

    /** @test */
    public function it_can_render_captcha_with_optional_name()
    {
        static::assertSame(
            '<input type="hidden" id="g-recaptcha" name="g-recaptcha">',
            $this->noCaptcha->input('g-recaptcha')->toHtml()
        );
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Create Captcha for testing
     *
     * @param  string       $secret
     * @param  string       $siteKey
     * @param  string|null  $lang
     *
     * @return \Arcanedev\NoCaptcha\NoCaptchaV3
     */
    private function createCaptcha($secret = 'secret-key', $siteKey = 'site-key', $lang = null)
    {
        return new NoCaptchaV3($secret, $siteKey, $lang);
    }
}
