<?php namespace Arcanedev\NoCaptcha\Tests\Utilities;

use Arcanedev\NoCaptcha\Tests\TestCase;
use Arcanedev\NoCaptcha\Utilities\Attributes;

class AttributesTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    const ATTRIBUTES_CLASS = 'Arcanedev\\NoCaptcha\\Utilities\\Attributes';
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
        parent::tearDown();

        unset($this->attributes);
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
        $this->assertInstanceOf(self::ATTRIBUTES_CLASS, $this->attributes);
        $siteKey = 'my-site-key';
        $this->assertEquals(
            'class="g-recaptcha" data-sitekey="' . $siteKey . '"',
            $this->attributes->build($siteKey)
        );
    }

    /**
     * @test
     */
    public function testCanBuildTypeAttribute()
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

    /**
     * @test
     */
    public function testCanBuildThemeAttribute()
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

    /**
     * @test
     */
    public function testCanBuildWithCustomAttributes()
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
