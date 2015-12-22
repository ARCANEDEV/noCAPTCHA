<?php namespace Arcanedev\NoCaptcha\Utilities;

use Arcanedev\NoCaptcha\Contracts\Utilities\AttributesInterface;

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
    /** @var array  */
    protected $items = [];

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
        $this->items = $items;

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
     * Get Image Attribute.
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
