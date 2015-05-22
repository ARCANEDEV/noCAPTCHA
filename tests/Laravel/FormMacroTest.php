<?php namespace Arcanedev\NoCaptcha\Tests\Laravel;

use Arcanedev\NoCaptcha\Tests\LaravelTestCase;

class FormMacroTest extends LaravelTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_render_captcha()
    {
        $captcha = $this->app['form']->captcha();

        $this->assertEquals(
            '<div class="g-recaptcha" data-sitekey="no-captcha-sitekey"></div>',
            $captcha
        );
    }
}
