<?php namespace Arcanedev\NoCaptcha\Tests;

use Arcanedev\NoCaptcha\NoCaptcha;
use Mockery as m;

class NoCaptchaTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var NoCaptcha */
    private $noCaptcha;

    const NO_CAPTCHA_CLASS = 'Arcanedev\\NoCaptcha\\NoCaptcha';

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
    /**
     * @test
     */
    public function testCanBeInstantiated()
    {
        $this->assertInstanceOf(self::NO_CAPTCHA_CLASS, $this->noCaptcha);
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\NoCaptcha\Exceptions\InvalidTypeException
     * @expectedExceptionMessage The secret key must be a string value, NULL given
     */
    public function testMustThrowInvalidTypeExceptionOnSecretKey()
    {
        new NoCaptcha(null, null);
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\NoCaptcha\Exceptions\ApiException
     * @expectedExceptionMessage The secret key must not be empty
     */
    public function testMustThrowApiExceptionOnEmptySecretKey()
    {
        new NoCaptcha('   ', null);
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\NoCaptcha\Exceptions\InvalidTypeException
     * @expectedExceptionMessage The site key must be a string value, NULL given
     */
    public function testMustThrowInvalidTypeExceptionOnSiteKey()
    {
        new NoCaptcha('secret', null);
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\NoCaptcha\Exceptions\ApiException
     * @expectedExceptionMessage The site key must not be empty
     */
    public function testMustThrowApiExceptionOnEmptySiteKey()
    {
        new NoCaptcha('secret', '   ');
    }

    /**
     * @test
     */
    public function testCanRenderScriptTag()
    {
        $tag = '<script src="' . NoCaptcha::CLIENT_URL . '" async defer></script>';

        $this->assertEquals($tag, $this->noCaptcha->script());

        // Echo out only once
        $this->assertEquals('', $this->noCaptcha->script());
    }

    /**
     * @test
     */
    public function testCanRenderScriptTagWithLang()
    {
        $lang = 'fr';
        $tag = '<script src="' . NoCaptcha::CLIENT_URL . '?hl=' . $lang . '" async defer></script>';

        $this->noCaptcha = $this->createCaptcha($lang);

        $this->assertEquals($tag, $this->noCaptcha->script());

        // Echo out only once
        $this->assertEquals('', $this->noCaptcha->script());
    }

    /**
     * @test
     */
    public function testCanDisplayCaptcha()
    {
        $tag = '<div class="g-recaptcha" data-sitekey="site-key"></div>';

        $this->assertEquals($tag, $this->noCaptcha->display());

        $attributes = [
            'data-theme' => 'dark',
            'data-type'  => 'audio',
        ];

        $tag = '<div class="g-recaptcha" data-sitekey="site-key" data-theme="dark" data-type="audio"></div>';

        $this->assertEquals($tag, $this->noCaptcha->display($attributes));
    }

    /**
     * @test
     */
    public function testCanVerify()
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

    /**
     * @test
     */
    public function testCanVerifyButFails()
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
