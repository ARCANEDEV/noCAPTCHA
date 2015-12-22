<?php namespace Arcanedev\NoCaptcha\Tests\Utilities;

use Arcanedev\NoCaptcha\Tests\TestCase;
use Arcanedev\NoCaptcha\Utilities\Attributes;

/**
 * Class AttributesTest
 * @package Arcanedev\NoCaptcha\Tests\Utilities
 */
class AttributesTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const ATTRIBUTES_CLASS = 'Arcanedev\\NoCaptcha\\Utilities\\Attributes';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Attributes */
    private $attributes;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->attributes = new Attributes;
    }

    public function tearDown()
    {
        unset($this->attributes);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(self::ATTRIBUTES_CLASS, $this->attributes);
        $siteKey = 'my-site-key';
        $this->assertEquals(
            'class="g-recaptcha" data-sitekey="' . $siteKey . '"',
            $this->attributes->build($siteKey)
        );
    }

    /** @test */
    public function it_can_build_type_attribute()
    {
        $siteKey    = 'my-site-key';
        $attributes = 'class="g-recaptcha" data-sitekey="' . $siteKey . '" data-type="image"';

        $this->assertEquals(
            $attributes,
            $this->attributes->build($siteKey, [
                'data-type' => 'image'
            ])
        );
        $this->assertEquals(
            $attributes,
            $this->attributes->build($siteKey, [
                'data-type' => 'video'
            ])
        );

        $attributes = 'class="g-recaptcha" data-sitekey="' . $siteKey . '" data-type="audio"';
        $this->assertEquals(
            $attributes,
            $this->attributes->build($siteKey, [
                'data-type' => 'audio'
            ])
        );
    }

    /** @test */
    public function it_can_build_theme_attribute()
    {
        $siteKey    = 'my-site-key';

        $attributes = 'class="g-recaptcha" data-sitekey="' . $siteKey . '" data-theme="dark"';
        $this->assertEquals(
            $attributes,
            $this->attributes->build($siteKey, [
                'data-theme' => 'dark'
            ])
        );

        $attributes = 'class="g-recaptcha" data-sitekey="' . $siteKey . '" data-theme="light"';
        $this->assertEquals(
            $attributes,
            $this->attributes->build($siteKey, [
                'data-theme' => 'light'
            ])
        );

        $this->assertEquals(
            $attributes,
            $this->attributes->build($siteKey, [
                'data-theme' => 'not-light-and-not-dark'
            ])
        );
    }

    /** @test */
    public function it_can_build_with_custom_attributes()
    {
        $siteKey = 'my-site-key';
        $this->assertEquals(
            'class="g-recaptcha" data-sitekey="' . $siteKey . '" data-name="super-captcha"',
            $this->attributes->build($siteKey, [
                'data-name' => 'super-captcha'
            ])
        );
    }
}
