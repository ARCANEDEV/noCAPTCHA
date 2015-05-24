<?php namespace Arcanedev\NoCaptcha\Tests;

use Arcanedev\NoCaptcha\NoCaptcha;
use Mockery as m;

class NoCaptchaTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const NO_CAPTCHA_CLASS = 'Arcanedev\\NoCaptcha\\NoCaptcha';

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
        parent::tearDown();

        unset($this->noCaptcha);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(self::NO_CAPTCHA_CLASS, $this->noCaptcha);
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\NoCaptcha\Exceptions\InvalidTypeException
     * @expectedExceptionMessage The secret key must be a string value, NULL given
     */
    public function it_must_throw_invalid_type_exception_on_secret_key()
    {
        new NoCaptcha(null, null);
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\NoCaptcha\Exceptions\ApiException
     * @expectedExceptionMessage The secret key must not be empty
     */
    public function it_must_throw_api_exception_on_empty_secret_key()
    {
        new NoCaptcha('   ', null);
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\NoCaptcha\Exceptions\InvalidTypeException
     * @expectedExceptionMessage The site key must be a string value, NULL given
     */
    public function it_must_throw_invalid_type_exception_on_site_key()
    {
        new NoCaptcha('secret', null);
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\NoCaptcha\Exceptions\ApiException
     * @expectedExceptionMessage The site key must not be empty
     */
    public function it_must_throw_api_exception_on_empty_site_key()
    {
        new NoCaptcha('secret', '   ');
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
            '<script src="' . NoCaptcha::CLIENT_URL . '?onload=CaptchaCallback&render=explicit" async defer></script>
            <script>
                var CaptchaCallback = function(){
                    grecaptcha.render(\'captcha-1\', {\'sitekey\' : \'site-key\'});
                    grecaptcha.render(\'captcha-2\', {\'sitekey\' : \'site-key\'});
                };
            </script>';

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
    public function testCanDisplayAudioCaptcha()
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
        $request = m::mock('Arcanedev\NoCaptcha\Utilities\Request');
        $request->shouldReceive('send')->andReturn([
            'success' => true
        ]);

        $passes = $this->noCaptcha
            ->setRequestClient($request)
            ->verify('re-captcha-response');

        $this->assertTrue($passes);
    }

    /** @test */
    public function it_can_verify_with_fails()
    {
        $passes  = $this->noCaptcha->verify('');

        $this->assertFalse($passes);

        $request = m::mock('Arcanedev\NoCaptcha\Utilities\Request');
        $request->shouldReceive('send')->andReturn([
            'success'     => false,
            'error-codes' => 'invalid-input-response'
        ]);

        $passes = $this->noCaptcha
            ->setRequestClient($request)
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
     * @param  string|null $lang
     *
     * @return NoCaptcha
     */
    private function createCaptcha($lang = null)
    {
        return new NoCaptcha('secret-key', 'site-key', $lang);
    }
}
