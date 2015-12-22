<?php namespace Arcanedev\NoCaptcha\Tests;

use Arcanedev\NoCaptcha\NoCaptcha;
use Arcanedev\NoCaptcha\Utilities\Request;
use Prophecy\Argument;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class     NoCaptchaTest
 *
 * @package  Arcanedev\NoCaptcha\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class NoCaptchaTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var NoCaptcha */
    private $noCaptcha;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
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

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(NoCaptcha::class, $this->noCaptcha);
        $this->assertEquals(
            '<script src="' . NoCaptcha::CLIENT_URL . '" async defer></script>',
            $this->noCaptcha->script()
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
        new NoCaptcha(null, null);
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\NoCaptcha\Exceptions\ApiException
     * @expectedExceptionMessage  The secret key must not be empty
     */
    public function it_must_throw_api_exception_on_empty_secret_key()
    {
        new NoCaptcha('   ', null);
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\NoCaptcha\Exceptions\ApiException
     * @expectedExceptionMessage  The site key must be a string value, NULL given
     */
    public function it_must_throw_invalid_type_exception_on_site_key()
    {
        new NoCaptcha('secret', null);
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\NoCaptcha\Exceptions\ApiException
     * @expectedExceptionMessage  The site key must not be empty
     */
    public function it_must_throw_api_exception_on_empty_site_key()
    {
        new NoCaptcha('secret', '   ');
    }

    /** @test */
    public function it_can_switch_locale()
    {
        $locale = 'fr';
        $this->noCaptcha->setLang($locale);
        $this->assertInstanceOf(NoCaptcha::class, $this->noCaptcha);
        $this->assertEquals(
            '<script src="' . NoCaptcha::CLIENT_URL . '?hl=' . $locale . '" async defer></script>',
            $this->noCaptcha->script()
        );
    }

    /** @test */
    public function it_can_render_script_tag()
    {
        $tag = '<script src="' . NoCaptcha::CLIENT_URL . '" async defer></script>';

        $this->assertEquals($tag, $this->noCaptcha->script());

        // Echo out only once
        $this->assertEmpty($this->noCaptcha->script());
    }

    /** @test */
    public function it_can_render_script_tag_with_lang()
    {
        $lang = 'fr';
        $tag  = '<script src="' . NoCaptcha::CLIENT_URL . '?hl=' . $lang . '" async defer></script>';

        $this->noCaptcha = $this->createCaptcha($lang);

        $this->assertEquals($tag, $this->noCaptcha->script());

        // Not even twice
        $this->assertEmpty($this->noCaptcha->script());
    }

    /** @test */
    public function it_can_render_script_with_callback()
    {
        $captchas = ['captcha-1', 'captcha-2'];
        $script   =
            '<script>
                var captchaRenderCallback = function() {
                    grecaptcha.render(\'captcha-1\', {\'sitekey\' : \'site-key\'});
                    grecaptcha.render(\'captcha-2\', {\'sitekey\' : \'site-key\'});
                };
            </script>
            <script src="' . NoCaptcha::CLIENT_URL . '?onload=captchaRenderCallback&render=explicit" async defer></script>';

        $this->assertEquals(
            array_map('trim', preg_split('/\r\n|\r|\n/', $script)),
            array_map('trim', preg_split('/\r\n|\r|\n/', $this->noCaptcha->scriptWithCallback($captchas)))
        );

        // Not even twice
        $this->assertEmpty($this->noCaptcha->script());
        $this->assertEmpty($this->noCaptcha->scriptWithCallback($captchas));
    }

    /** @test */
    public function it_can_display_captcha()
    {
        $this->assertEquals(
            '<div class="g-recaptcha" data-sitekey="site-key"></div>',
            $this->noCaptcha->display()
        );

        $this->assertEquals(
            '<div class="g-recaptcha" data-sitekey="site-key" id="captcha-1"></div>',
            $this->noCaptcha->display([
                'id'        => 'captcha-1'
            ])
        );

        $this->assertEquals(
            '<div class="g-recaptcha" data-sitekey="site-key" data-type="image" data-theme="light"></div>',
            $this->noCaptcha->display([
                'data-type'  => 'image',
                'data-theme' => 'light',
            ])
        );

        $this->assertEquals(
            '<div class="g-recaptcha" data-sitekey="site-key" data-type="audio" data-theme="dark"></div>',
            $this->noCaptcha->display([
                'data-type'  => 'audio',
                'data-theme' => 'dark',
            ])
        );
    }

    /** @test */
    public function it_can_display_image_captcha()
    {
        $this->assertEquals(
            '<div class="g-recaptcha" data-sitekey="site-key" data-type="image"></div>',
            $this->noCaptcha->image()
        );

        $this->assertEquals(
            '<div class="g-recaptcha" data-sitekey="site-key" data-theme="dark" data-type="image"></div>',
            $this->noCaptcha->image([
                'data-theme' => 'dark',
            ])
        );

        $this->assertEquals(
            '<div class="g-recaptcha" data-sitekey="site-key" data-theme="light" data-type="image"></div>',
            $this->noCaptcha->image([
                'data-theme' => 'light',
            ])
        );

        $this->assertEquals(
            '<div class="g-recaptcha" data-sitekey="site-key" data-theme="light" data-type="image"></div>',
            $this->noCaptcha->image([
                'data-theme' => 'light',
                'data-type'  => 'audio', // Intruder
            ])
        );
    }

    /** @test */
    public function it_can_display_audio_captcha()
    {
        $this->assertEquals(
            '<div class="g-recaptcha" data-sitekey="site-key" data-type="audio"></div>',
            $this->noCaptcha->audio()
        );

        $this->assertEquals(
            '<div class="g-recaptcha" data-sitekey="site-key" data-theme="dark" data-type="audio"></div>',
            $this->noCaptcha->audio([
                'data-theme' => 'dark',
            ])
        );

        $this->assertEquals(
            '<div class="g-recaptcha" data-sitekey="site-key" data-theme="light" data-type="audio"></div>',
            $this->noCaptcha->audio([
                'data-theme' => 'light',
            ])
        );

        $this->assertEquals(
            '<div class="g-recaptcha" data-sitekey="site-key" data-theme="light" data-type="audio"></div>',
            $this->noCaptcha->audio([
                'data-theme' => 'light',
                'data-type'  => 'image', // Intruder
            ])
        );
    }

    /** @test */
    public function it_can_display_captcha_with_defaults()
    {
        $this->assertEquals(
            '<div class="g-recaptcha" data-sitekey="site-key" data-type="image"></div>',
            $this->noCaptcha->display([
                'data-type'  => 'video'
            ])
        );

        $this->assertEquals(
            '<div class="g-recaptcha" data-sitekey="site-key" data-type="image"></div>',
            $this->noCaptcha->display([
                'data-type'  => true
            ])
        );

        $this->assertEquals(
            '<div class="g-recaptcha" data-sitekey="site-key" data-theme="light"></div>',
            $this->noCaptcha->display([
                'data-theme' => 'blue'
            ])
        );

        $this->assertEquals(
            '<div class="g-recaptcha" data-sitekey="site-key" data-theme="light"></div>',
            $this->noCaptcha->display([
                'data-theme' => true
            ])
        );
    }

    /** @test */
    public function it_can_display_captcha_with_style_attribute()
    {
        $this->assertEquals(
            '<div class="g-recaptcha" data-sitekey="site-key" style="transform: scale(0.77); transform-origin: 0 0;"></div>',
            $this->noCaptcha->display([
                'style' => 'transform: scale(0.77); transform-origin: 0 0;'
            ])
        );
    }

    /** @test */
    public function it_can_verify()
    {
        $requestClient = $this->prophesize(Request::class);
        $requestClient->send(Argument::type('string'))
            ->willReturn(['success' => true]);

        /** @var Request $requestClient */
        $passes = $this->noCaptcha
            ->setRequestClient($requestClient->reveal())
            ->verify('re-captcha-response');

        $this->assertTrue($passes);
    }

    /** @test */
    public function it_can_verify_psr7_request()
    {
        /**
         * @var  ServerRequestInterface  $request
         * @var  Request                 $requestClient
         */
        $requestClient = $this->prophesize(Request::class);
        $requestClient->send(Argument::type('string'))
            ->willReturn(['success' => true]);

        $request = $this->prophesize(ServerRequestInterface::class);
        $request->getParsedBody()->willReturn([
            'g-recaptcha-response' => true,
        ]);
        $request->getServerParams()->willReturn([
            'REMOTE_ADDR' => '127.0.0.1'
        ]);

        $passes = $this->noCaptcha
            ->setRequestClient($requestClient->reveal())
            ->verifyRequest($request->reveal());

        $this->assertTrue($passes);
    }

    /** @test */
    public function it_can_verify_with_fails()
    {
        $passes  = $this->noCaptcha->verify('');

        $this->assertFalse($passes);

        $requestClient = $this->prophesize(Request::class);
        $requestClient->send(Argument::type('string'))
            ->willReturn([
                'success'     => false,
                'error-codes' => 'invalid-input-response'
            ]);

        $passes = $this->noCaptcha
            ->setRequestClient($requestClient->reveal())
            ->verify('re-captcha-response');

        $this->assertFalse($passes);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create Captcha for testing
     *
     * @param  string|null  $lang
     *
     * @return \Arcanedev\NoCaptcha\NoCaptcha
     */
    private function createCaptcha($lang = null)
    {
        return new NoCaptcha('secret-key', 'site-key', $lang);
    }
}
