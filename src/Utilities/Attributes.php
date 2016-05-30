<?php namespace Arcanedev\NoCaptcha\Utilities;

use Arcanedev\NoCaptcha\Contracts\Utilities\AttributesInterface;
use Arcanedev\NoCaptcha\Exceptions\InvalidArgumentException;
use Arcanedev\NoCaptcha\NoCaptcha;

/**
 * Class     Attributes
 *
 * @package  Arcanedev\NoCaptcha\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Attributes implements AttributesInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Attribute collection.
     *
     * @var array
     */
    protected $items    = [];

    /**
     * Defaults attributes.
     *
     * @var array
     */
    protected $defaults = [];

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Attributes constructor.
     *
     * @param  array  $defaults
     */
    public function __construct(array $defaults = [])
    {
        $this->defaults = array_filter($defaults);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get all items.
     *
     * @param  string  $siteKey
     *
     * @return array
     */
    protected function getItems($siteKey)
    {
        return array_merge(
            $this->getDefaultAttributes($siteKey),
            $this->items
        );
    }

    /**
     * Get Default attributes.
     *
     * @param  string  $siteKey
     *
     * @return array
     */
    private function getDefaultAttributes($siteKey)
    {
        return [
            'class'        => 'g-recaptcha',
            'data-sitekey' => $siteKey,
        ];
    }

    /**
     * Set items.
     *
     * @param  array  $items
     *
     * @return self
     */
    private function setItems(array $items)
    {
        $this->items = array_merge($this->defaults, $items);

        $this->checkAttributes();

        return $this;
    }

    /**
     * Get an item value by name.
     *
     * @param  string  $name
     *
     * @return string
     */
    private function getItem($name)
    {
        if ( ! $this->hasItem($name)) {
            return null;
        }

        return $this->items[$name];
    }

    /**
     * Set an item.
     *
     * @param  string  $name
     * @param  string  $value
     *
     * @return self
     */
    private function setItem($name, $value)
    {
        $this->items[$name] = $value;

        return $this;
    }

    /**
     * Get image type attribute.
     *
     * @return array
     */
    public function getImageAttribute()
    {
        return [self::ATTR_TYPE => 'image'];
    }

    /**
     * Get audio type attribute.
     *
     * @return array
     */
    public function getAudioAttribute()
    {
        return [self::ATTR_TYPE => 'audio'];
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Build attributes.
     *
     * @param  string  $siteKey
     * @param  array   $items
     *
     * @return string
     */
    public function build($siteKey, array $items = [])
    {
        $this->setItems($items);

        $output = [];

        foreach ($this->getItems($siteKey) as $key => $value) {
            $output[] = trim($key) . '="' . trim($value) . '"';
        }

        return implode(' ', $output);
    }

    /**
     * Prepare the name and id attributes.
     *
     * @param  string|null  $name
     *
     * @return array
     *
     * @throws \Arcanedev\NoCaptcha\Exceptions\InvalidArgumentException
     */
    public function prepareNameAttribute($name)
    {
        if (is_null($name)) return [];

        if ($name === NoCaptcha::CAPTCHA_NAME) {
            throw new InvalidArgumentException(
                'The captcha name must be different from "' . NoCaptcha::CAPTCHA_NAME . '".'
            );
        }

        return array_combine(['id', 'name'], [$name, $name]);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check attributes.
     */
    private function checkAttributes()
    {
        $this->checkTypeAttribute();
        $this->checkThemeAttribute();
        $this->checkSizeAttribute();
    }

    /**
     * Check type attribute.
     */
    private function checkTypeAttribute()
    {
        $this->checkDataAttribute(self::ATTR_TYPE, 'image', ['image', 'audio']);
    }

    /**
     * Check theme attribute.
     */
    private function checkThemeAttribute()
    {
        $this->checkDataAttribute(self::ATTR_THEME, 'light', ['light', 'dark']);
    }

    /**
     * Check size attribute.
     */
    private function checkSizeAttribute()
    {
        $this->checkDataAttribute(self::ATTR_SIZE, 'normal', ['normal', 'compact']);
    }

    /**
     * Check data Attribute.
     *
     * @param  string  $name
     * @param  string  $default
     * @param  array   $available
     */
    private function checkDataAttribute($name, $default, array $available)
    {
        $item = $this->getItem($name);

        if ( ! is_null($item)) {
            $item = (is_string($item) and in_array($item, $available))
                ? strtolower(trim($item))
                : $default;

            $this->setItem($name, $item);
        }
    }

    /**
     * Check if has an item.
     *
     * @param  string  $name
     *
     * @return bool
     */
    private function hasItem($name)
    {
        return array_key_exists($name, $this->items);
    }
}
