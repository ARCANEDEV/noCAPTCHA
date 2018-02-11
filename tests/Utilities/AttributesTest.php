<?php namespace Arcanedev\NoCaptcha\Tests\Utilities;

use Arcanedev\NoCaptcha\Tests\TestCase;
use Arcanedev\NoCaptcha\Utilities\Attributes;

/**
 * Class     AttributesTest
 *
 * @package  Arcanedev\NoCaptcha\Tests\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class AttributesTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var Attributes */
    private $attributes;

    /** @var string */
    private $siteKey = 'my-site-key';

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
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

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        static::assertInstanceOf(Attributes::class, $this->attributes);
        static::assertSame(
            'class="g-recaptcha" data-sitekey="'.$this->siteKey.'"',
            $this->attributes->build($this->siteKey)
        );
    }

    /** @test */
    public function it_can_be_instantiated_with_defaults()
    {
        $this->attributes = new Attributes([
            'theme' => null,
            'type'  => null,
            'size'  => null,
        ]);

        static::assertInstanceOf(Attributes::class, $this->attributes);
        static::assertSame(
            'class="g-recaptcha" data-sitekey="'.$this->siteKey.'"',
            $this->attributes->build($this->siteKey)
        );

        $this->attributes = new Attributes(['data-theme' => 'light']);

        static::assertInstanceOf(Attributes::class, $this->attributes);
        static::assertSame(
            'class="g-recaptcha" data-sitekey="'.$this->siteKey.'" data-theme="light"',
            $this->attributes->build($this->siteKey)
        );

        $this->attributes = new Attributes(['data-type' => 'image']);

        static::assertInstanceOf(Attributes::class, $this->attributes);
        static::assertSame(
            'class="g-recaptcha" data-sitekey="'.$this->siteKey.'" data-type="image"',
            $this->attributes->build($this->siteKey)
        );

        $this->attributes = new Attributes(['data-size' => 'normal']);

        static::assertInstanceOf(Attributes::class, $this->attributes);
        static::assertSame(
            'class="g-recaptcha" data-sitekey="'.$this->siteKey.'" data-size="normal"',
            $this->attributes->build($this->siteKey)
        );

        // Invalid
        $this->attributes = new Attributes(['data-size' => 'huge']);

        static::assertInstanceOf(Attributes::class, $this->attributes);
        static::assertSame(
            'class="g-recaptcha" data-sitekey="'.$this->siteKey.'" data-size="normal"',
            $this->attributes->build($this->siteKey)
        );
    }

    /** @test */
    public function it_can_build_type_attribute()
    {
        $attributes = 'class="g-recaptcha" data-sitekey="'.$this->siteKey.'" data-type="image"';

        static::assertSame(
            $attributes,
            $this->attributes->build($this->siteKey, ['data-type' => 'image'])
        );
        static::assertSame(
            $attributes,
            $this->attributes->build($this->siteKey, ['data-type' => 'video'])
        );

        static::assertSame(
            'class="g-recaptcha" data-sitekey="'.$this->siteKey.'" data-type="audio"',
            $this->attributes->build($this->siteKey, ['data-type' => 'audio'])
        );
    }

    /** @test */
    public function it_can_build_theme_attribute()
    {
        $attributes = 'class="g-recaptcha" data-sitekey="'.$this->siteKey.'" data-theme="light"';

        static::assertSame(
            $attributes,
            $this->attributes->build($this->siteKey, ['data-theme' => 'light'])
        );

        static::assertSame(
            $attributes,
            $this->attributes->build($this->siteKey, ['data-theme' => 'not-light-and-not-dark'])
        );

        static::assertSame(
            'class="g-recaptcha" data-sitekey="'.$this->siteKey.'" data-theme="dark"',
            $this->attributes->build($this->siteKey, ['data-theme' => 'dark'])
        );
    }

    /** @test */
    public function it_can_build_size_attribute()
    {
        $attributes = 'class="g-recaptcha" data-sitekey="'.$this->siteKey.'" data-size="normal"';

        static::assertSame(
            $attributes,
            $this->attributes->build($this->siteKey, ['data-size' => 'normal'])
        );

        static::assertSame(
            $attributes,
            $this->attributes->build($this->siteKey, ['data-size' => 'humongous'])
        );

        static::assertSame(
            'class="g-recaptcha" data-sitekey="'.$this->siteKey.'" data-size="compact"',
            $this->attributes->build($this->siteKey, ['data-size' => 'compact'])
        );
    }

    /** @test */
    public function it_can_build_with_custom_attributes()
    {
        static::assertSame(
            'class="g-recaptcha" data-sitekey="'.$this->siteKey.'" data-name="super-captcha"',
            $this->attributes->build($this->siteKey, ['data-name' => 'super-captcha'])
        );
    }
}
