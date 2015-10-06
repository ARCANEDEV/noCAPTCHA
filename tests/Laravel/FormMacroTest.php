<?php namespace Arcanedev\NoCaptcha\Tests\Laravel;

use Arcanedev\NoCaptcha\Tests\LaravelTestCase;

/**
 * Class     FormMacroTest
 *
 * @package  Arcanedev\NoCaptcha\Tests\Laravel
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class FormMacroTest extends LaravelTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_render_captcha()
    {
        if ($this->app->bound('form')) {
            $captcha = $this->app['form']->captcha();

            $this->assertEquals(
                '<div class="g-recaptcha" data-sitekey="no-captcha-sitekey"></div>',
                $captcha
            );
        }
    }
}
