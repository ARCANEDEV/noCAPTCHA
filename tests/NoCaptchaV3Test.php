<?php

declare(strict_types=1);

namespace Arcanedev\NoCaptcha\Tests;

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
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_get_client_url(): void
    {
        static::assertSame(
            'https://www.google.com/recaptcha/api.js',
            NoCaptchaV3::getClientUrl()
        );

        NoCaptchaV3::$useGlobalDomain = true;

        static::assertSame(
            'https://www.recaptcha.net/recaptcha/api.js',
            NoCaptchaV3::getClientUrl()
        );

        NoCaptchaV3::$useGlobalDomain = false;

        static::assertSame(
            'https://www.google.com/recaptcha/api.js',
            NoCaptchaV3::getClientUrl()
        );
    }

    /** @test */
    public function it_can_get_verification_url(): void
    {
        static::assertSame(
            'https://www.google.com/recaptcha/api/siteverify',
            NoCaptchaV3::getVerificationUrl()
        );

        NoCaptchaV3::$useGlobalDomain = true;

        static::assertSame(
            'https://www.recaptcha.net/recaptcha/api/siteverify',
            NoCaptchaV3::getVerificationUrl()
        );

        NoCaptchaV3::$useGlobalDomain = false;

        static::assertSame(
            'https://www.google.com/recaptcha/api/siteverify',
            NoCaptchaV3::getVerificationUrl()
        );
    }

    /** @test */
    public function it_can_be_instantiated(): void
    {
        $noCaptcha = $this->createCaptcha();

        static::assertInstanceOf(NoCaptchaV3::class, $noCaptcha);
        static::assertSame(
            '<script src="https://www.google.com/recaptcha/api.js?render=site-key"></script>',
            $noCaptcha->script()->toHtml()
        );
    }

    /** @test */
    public function it_can_be_instantiated_with_defaults(): void
    {
        $noCaptcha = $this->createCaptcha();

        static::assertSame(
            '<input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">',
            $noCaptcha->input()->toHtml()
        );
    }

    /** @test */
    public function it_must_throw_invalid_type_exception_on_secret_key(): void
    {
        $this->expectException(\Arcanedev\NoCaptcha\Exceptions\ApiException::class);
        $this->expectExceptionMessage('The secret key must be a string value, NULL given');

        $this->createCaptcha(null, null);
    }

    /** @test */
    public function it_must_throw_api_exception_on_empty_secret_key(): void
    {
        $this->expectException(\Arcanedev\NoCaptcha\Exceptions\ApiException::class);
        $this->expectExceptionMessage('The secret key must not be empty');

        $this->createCaptcha('   ', null);
    }

    /** @test */
    public function it_must_throw_invalid_type_exception_on_site_key(): void
    {
        $this->expectException(\Arcanedev\NoCaptcha\Exceptions\ApiException::class);
        $this->expectExceptionMessage('The site key must be a string value, NULL given');

        $this->createCaptcha('secret', null);
    }

    /** @test */
    public function it_must_throw_api_exception_on_empty_site_key(): void
    {
        $this->expectException(\Arcanedev\NoCaptcha\Exceptions\ApiException::class);
        $this->expectExceptionMessage('The site key must not be empty');

        $this->createCaptcha('secret', '   ');
    }

    /** @test */
    public function it_can_switch_locale(): void
    {
        $noCaptcha = $this->createCaptcha()->setLang('fr');

        static::assertInstanceOf(NoCaptchaV3::class, $noCaptcha);
        static::assertSame(
            '<script src="https://www.google.com/recaptcha/api.js?hl=fr&render=site-key"></script>',
            $noCaptcha->script()->toHtml()
        );
    }

    /** @test */
    public function it_can_render_script_tag(): void
    {
        $noCaptcha = $this->createCaptcha();

        static::assertSame(
            '<script src="https://www.google.com/recaptcha/api.js?render=site-key"></script>',
            $noCaptcha->script()->toHtml()
        );

        // Echo out only once
        static::assertEmpty($noCaptcha->script()->toHtml());
    }

    /** @test */
    public function it_can_render_script_tag_with_lang(): void
    {
        $noCaptcha = $this->createCaptcha()->setLang('fr');

        static::assertSame(
            '<script src="https://www.google.com/recaptcha/api.js?hl=fr&render=site-key"></script>',
            $noCaptcha->script()->toHtml()
        );

        // Not twice
        static::assertEmpty($noCaptcha->script()->toHtml());
    }

    /** @test */
    public function it_can_render_script_tag_with_onload_callback(): void
    {
        $noCaptcha = $this->createCaptcha();

        static::assertSame(
            '<script src="https://www.google.com/recaptcha/api.js?render=site-key&onload=no_captcha_onload"></script>',
            $noCaptcha->script('no_captcha_onload')->toHtml()
        );

        // Not twice
        static::assertEmpty($noCaptcha->script()->toHtml());
    }

    /** @test */
    public function it_can_render_api_script(): void
    {
        $noCaptcha = $this->createCaptcha();

        static::assertSame(
            "<script>
                window.noCaptcha = {
                    render: function(action, callback) {
                        grecaptcha.execute('site-key', {action})
                              .then(callback);
                    }
                }
            </script>",
            $noCaptcha->getApiScript()->toHtml()
        );
    }

    /** @test */
    public function it_can_verify(): void
    {
        $noCaptcha = $this->createCaptcha();

        static::assertNull($noCaptcha->getLastResponse());

        /** @var  \Arcanedev\NoCaptcha\Utilities\Request  $request */
        $request = tap($this->prophesize(Request::class), function ($request) {
            $request->send(Argument::type('string'))->willReturn('{"success": true}');
        })->reveal();

        $response = $noCaptcha
            ->setRequestClient($request)
            ->verify('re-captcha-response');

        static::assertTrue($response->isSuccess());

        static::assertSame(
            $response,
            $noCaptcha->getLastResponse()
        );
    }

    /** @test */
    public function it_can_verify_psr7_request(): void
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

        $response = $this->createCaptcha()
            ->setRequestClient($client)
            ->verifyRequest($request);

        static::assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_verify_with_fails(): void
    {
        $noCaptcha = $this->createCaptcha();

        $response = $noCaptcha->verify('');

        static::assertFalse($response->isSuccess());

        $request = tap($this->prophesize(Request::class), function ($request) {
            $request->send(Argument::type('string'))->willReturn(
                json_encode([
                    'success'     => false,
                    'error-codes' => 'invalid-input-response'
                ])
            );
        })->reveal();

        $response = $noCaptcha
            ->setRequestClient($request)
            ->verify('re-captcha-response');

        static::assertFalse($response->isSuccess());
    }

    /** @test */
    public function it_can_render_captcha_with_optional_name(): void
    {
        $noCaptcha = $this->createCaptcha();

        static::assertSame(
            '<input type="hidden" id="g-recaptcha" name="g-recaptcha">',
            $noCaptcha->input('g-recaptcha')->toHtml()
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
