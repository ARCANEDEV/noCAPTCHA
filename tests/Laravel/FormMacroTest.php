<?php namespace Arcanedev\NoCaptcha\Tests\Laravel;

use Arcanedev\LaravelHtml\Contracts\FormBuilder;
use Arcanedev\NoCaptcha\Tests\LaravelTestCase;

/**
 * Class     FormMacroTest
 *
 * @package  Arcanedev\NoCaptcha\Tests\Laravel
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class FormMacroTest extends LaravelTestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_render_captcha_with_laravel_html_package()
    {
        $this->app->singleton('form', FormBuilder::class); // This is for BC

        foreach ([FormBuilder::class, 'form'] as $alias) {
            static::assertEquals(
                '<div class="g-recaptcha" data-sitekey="no-captcha-sitekey" id="captcha" name="captcha"></div>',
                $this->app[$alias]->captcha('captcha')
            );
        }
    }
}
