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
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var NoCaptcha */
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
        $this->assertInstanceOf(NoCaptcha::class, $this->noCaptcha);
        $this->assertSame(
            '<script src="' . NoCaptcha::CLIENT_URL . '" async defer></script>',
            $this->noCaptcha->script()->toHtml()
        );
    }

    /** @test */
    public function it_can_be_instantiated_with_attributes()
    {
        $this->noCaptcha = $this->createCaptcha(null, [
            'data-theme' => null,
            'data-type'  => null,
            'data-size'  => null
        ]);

        $this->assertSame(
            '<div class="g-recaptcha" data-sitekey="site-key" id="captcha" name="captcha"></div>',
            $this->noCaptcha->display('captcha')->toHtml()
        );

        $expectations = [
            [
                'attributes' => ['data-theme' => 'light'],
                'expected'   => '<div class="g-recaptcha" data-sitekey="site-key" data-theme="light" id="captcha" name="captcha"></div>',
            ],[
                'attributes' => ['data-theme' => 'dark'],
                'expected'   => '<div class="g-recaptcha" data-sitekey="site-key" data-theme="dark" id="captcha" name="captcha"></div>',
            ],[
                'attributes' => ['data-theme' => 'transparent'], // Invalid
                'expected'   => '<div class="g-recaptcha" data-sitekey="site-key" data-theme="light" id="captcha" name="captcha"></div>',
            ],[
                'attributes' => ['data-type' => 'image'],
                'expected'   => '<div class="g-recaptcha" data-sitekey="site-key" data-type="image" id="captcha" name="captcha"></div>',
            ],[
                'attributes' => ['data-type' => 'audio'],
                'expected'   => '<div class="g-recaptcha" data-sitekey="site-key" data-type="audio" id="captcha" name="captcha"></div>',
            ],[
                'attributes' => ['data-type' => 'video'], // Invalid
                'expected'   => '<div class="g-recaptcha" data-sitekey="site-key" data-type="image" id="captcha" name="captcha"></div>',
            ],[
                'attributes' => ['data-size' => 'normal'],
                'expected'   => '<div class="g-recaptcha" data-sitekey="site-key" data-size="normal" id="captcha" name="captcha"></div>',
            ],[
                'attributes' => ['data-size' => 'compact'],
                'expected'   => '<div class="g-recaptcha" data-sitekey="site-key" data-size="compact" id="captcha" name="captcha"></div>',
            ],[
                'attributes' => ['data-size' => 'huge'], // Invalid
                'expected'   => '<div class="g-recaptcha" data-sitekey="site-key" data-size="normal" id="captcha" name="captcha"></div>',
            ],
        ];

        foreach ($expectations as $data) {
            $this->assertSame(
                $data['expected'], $this->createCaptcha(null, $data['attributes'])->display('captcha')->toHtml()
            );
        }
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
        $this->assertSame(
            '<script src="' . NoCaptcha::CLIENT_URL . '?hl=' . $locale . '" async defer></script>',
            $this->noCaptcha->script()->toHtml()
        );
    }

    /** @test */
    public function it_can_render_script_tag()
    {
        $tag = '<script src="' . NoCaptcha::CLIENT_URL . '" async defer></script>';

        $this->assertSame($tag, $this->noCaptcha->script()->toHtml());

        // Echo out only once
        $this->assertEmpty($this->noCaptcha->script()->toHtml());
    }

    /** @test */
    public function it_can_render_script_tag_with_lang()
    {
        $lang = 'fr';
        $tag  = '<script src="' . NoCaptcha::CLIENT_URL . '?hl=' . $lang . '" async defer></script>';

        $this->noCaptcha = $this->createCaptcha($lang);

        $this->assertSame($tag, $this->noCaptcha->script()->toHtml());

        // Not even twice
        $this->assertEmpty($this->noCaptcha->script()->toHtml());
    }

    /** @test */
    public function it_can_render_script_with_callback()
    {
        $captchas = ['captcha-1', 'captcha-2'];
        $script   =
            '<script>
                var captchaRenderCallback = function() {
                    if (document.getElementById(\'captcha-1\')) { grecaptcha.render(\'captcha-1\', {\'sitekey\' : \'site-key\'}); }
                    if (document.getElementById(\'captcha-2\')) { grecaptcha.render(\'captcha-2\', {\'sitekey\' : \'site-key\'}); }
                };
            </script>
            <script src="' . NoCaptcha::CLIENT_URL . '?onload=captchaRenderCallback&render=explicit" async defer></script>';

        $this->assertSame(
            array_map('trim', preg_split('/\r\n|\r|\n/', $script)),
            array_map('trim', preg_split('/\r\n|\r|\n/', $this->noCaptcha->scriptWithCallback($captchas)->toHtml()))
        );

        // Not even twice
        $this->assertEmpty($this->noCaptcha->script()->toHtml());
        $this->assertEmpty($this->noCaptcha->scriptWithCallback($captchas)->toHtml());
    }

    /** @test */
    public function it_can_display_captcha()
    {
        $this->assertSame(
            '<div class="g-recaptcha" data-sitekey="site-key" id="captcha" name="captcha"></div>',
            $this->noCaptcha->display('captcha')->toHtml()
        );

        $this->assertSame(
            '<div class="g-recaptcha" data-sitekey="site-key" id="captcha_1" name="captcha"></div>',
            $this->noCaptcha->display('captcha', ['id' => 'captcha_1'])->toHtml()
        );

        $this->assertSame(
            '<div class="g-recaptcha" data-sitekey="site-key" id="captcha" name="captcha" data-type="image" data-theme="light"></div>',
            $this->noCaptcha->display('captcha', [
                'data-type'  => 'image',
                'data-theme' => 'light',
            ])->toHtml()
        );

        $this->assertSame(
            '<div class="g-recaptcha" data-sitekey="site-key" id="captcha" name="captcha" data-type="audio" data-theme="dark"></div>',
            $this->noCaptcha->display('captcha', [
                'data-type'  => 'audio',
                'data-theme' => 'dark',
            ])->toHtml()
        );
    }

    /** @test */
    public function it_can_display_image_captcha()
    {
        $this->assertSame(
            '<div class="g-recaptcha" data-sitekey="site-key" id="captcha" name="captcha" data-type="image"></div>',
            $this->noCaptcha->image('captcha')->toHtml()
        );

        $this->assertSame(
            '<div class="g-recaptcha" data-sitekey="site-key" id="captcha" name="captcha" data-theme="dark" data-type="image"></div>',
            $this->noCaptcha->image('captcha', ['data-theme' => 'dark'])->toHtml()
        );

        $this->assertSame(
            '<div class="g-recaptcha" data-sitekey="site-key" id="captcha" name="captcha" data-theme="light" data-type="image"></div>',
            $this->noCaptcha->image('captcha', ['data-theme' => 'light'])->toHtml()
        );

        $this->assertSame(
            '<div class="g-recaptcha" data-sitekey="site-key" id="captcha" name="captcha" data-theme="light" data-type="image"></div>',
            $this->noCaptcha->image('captcha', [
                'data-theme' => 'light',
                'data-type'  => 'audio', // Intruder
            ])->toHtml()
        );
    }

    /** @test */
    public function it_can_display_audio_captcha()
    {
        $this->assertSame(
            '<div class="g-recaptcha" data-sitekey="site-key" id="captcha" name="captcha" data-type="audio"></div>',
            $this->noCaptcha->audio('captcha')->toHtml()
        );

        $this->assertSame(
            '<div class="g-recaptcha" data-sitekey="site-key" id="captcha" name="captcha" data-theme="dark" data-type="audio"></div>',
            $this->noCaptcha->audio('captcha', ['data-theme' => 'dark'])->toHtml()
        );

        $this->assertSame(
            '<div class="g-recaptcha" data-sitekey="site-key" id="captcha" name="captcha" data-theme="light" data-type="audio"></div>',
            $this->noCaptcha->audio('captcha', ['data-theme' => 'light'])->toHtml()
        );

        $this->assertSame(
            '<div class="g-recaptcha" data-sitekey="site-key" id="captcha" name="captcha" data-theme="light" data-type="audio"></div>',
            $this->noCaptcha->audio('captcha', [
                'data-theme' => 'light',
                'data-type'  => 'image', // Intruder
            ])->toHtml()
        );
    }

    /** @test */
    public function it_can_display_captcha_with_defaults()
    {
        $this->assertSame(
            '<div class="g-recaptcha" data-sitekey="site-key" id="captcha" name="captcha" data-type="image"></div>',
            $this->noCaptcha->display('captcha', ['data-type'  => 'video'])->toHtml()
        );

        $this->assertSame(
            '<div class="g-recaptcha" data-sitekey="site-key" id="captcha" name="captcha" data-type="image"></div>',
            $this->noCaptcha->display('captcha', ['data-type'  => true])->toHtml()
        );

        $this->assertSame(
            '<div class="g-recaptcha" data-sitekey="site-key" id="captcha" name="captcha" data-theme="light"></div>',
            $this->noCaptcha->display('captcha', ['data-theme' => 'blue'])->toHtml()
        );

        $this->assertSame(
            '<div class="g-recaptcha" data-sitekey="site-key" id="captcha" name="captcha" data-theme="light"></div>',
            $this->noCaptcha->display('captcha', ['data-theme' => true])->toHtml()
        );
    }

    /** @test */
    public function it_can_display_captcha_with_style_attribute()
    {
        $this->assertSame(
            '<div class="g-recaptcha" data-sitekey="site-key" id="captcha" name="captcha" style="transform: scale(0.77); transform-origin: 0 0;"></div>',
            $this->noCaptcha->display('captcha', [
                'style' => 'transform: scale(0.77); transform-origin: 0 0;'
            ])->toHtml()
        );
    }

    /** @test */
    public function it_can_display_invisible_captcha()
    {
        $this->assertSame(
            '<button class="g-recaptcha" data-sitekey="site-key" data-callback="onSubmit">Send</button>',
            $this->noCaptcha->button('Send')->toHtml()
        );

        $this->assertSame(
            '<button class="g-recaptcha" data-sitekey="site-key" data-callback="submitForm">Post the form</button>',
            $this->noCaptcha->button('Post the form', ['data-callback' => 'submitForm'])->toHtml()
        );

        $this->assertSame(
            '<button class="g-recaptcha" data-sitekey="site-key" data-callback="onSubmit" data-size="invisible">Send</button>',
            $this->noCaptcha->button('Send', ['data-size' => 'invisible'])->toHtml()
        );

        $this->assertSame(
            '<button class="g-recaptcha" data-sitekey="site-key" data-callback="onSubmit" data-badge="bottomright">Send</button>',
            $this->noCaptcha->button('Send', ['data-badge' => 'bottomright'])->toHtml()
        );

        $this->assertSame(
            '<button class="g-recaptcha" data-sitekey="site-key" data-callback="onSubmit" data-badge="bottomright">Send</button>',
            $this->noCaptcha->button('Send', ['data-badge' => 'topright'])->toHtml()
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

    /** @test */
    public function it_can_render_captcha_with_optional_name()
    {
        $this->assertSame(
            '<div class="g-recaptcha" data-sitekey="site-key"></div>',
            $this->noCaptcha->display()->toHtml()
        );

        $this->assertSame(
            '<div class="g-recaptcha" data-sitekey="site-key" data-type="image"></div>',
            $this->noCaptcha->image()->toHtml()
        );

        $this->assertSame(
            '<div class="g-recaptcha" data-sitekey="site-key" data-type="audio"></div>',
            $this->noCaptcha->audio()->toHtml()
        );
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\NoCaptcha\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage  The captcha name must be different from "g-recaptcha-response".
     */
    public function it_must_throw_an_invalid_argument_exception_when_the_generic_captcha_name_is_same_as_captcha_response_name()
    {
        $this->noCaptcha->display(NoCaptcha::CAPTCHA_NAME);
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\NoCaptcha\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage  The captcha name must be different from "g-recaptcha-response".
     */
    public function it_must_throw_an_invalid_argument_exception_when_the_image_captcha_name_is_same_as_captcha_response_name()
    {
        $this->noCaptcha->image(NoCaptcha::CAPTCHA_NAME);
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\NoCaptcha\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage  The captcha name must be different from "g-recaptcha-response".
     */
    public function it_must_throw_an_invalid_argument_exception_when_the_audio_captcha_name_is_same_as_captcha_response_name()
    {
        $this->noCaptcha->audio(NoCaptcha::CAPTCHA_NAME);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Create Captcha for testing
     *
     * @param  string|null  $lang
     * @param  array        $attributes
     *
     * @return \Arcanedev\NoCaptcha\NoCaptcha
     */
    private function createCaptcha($lang = null, array $attributes = [])
    {
        return new NoCaptcha('secret-key', 'site-key', $lang, $attributes);
    }
}
