<?php namespace Arcanedev\NoCaptcha\Contracts\Utilities;

/**
 * Interface  AttributesInterface
 *
 * @package   Arcanedev\NoCaptcha\Contracts\Utilities
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface AttributesInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const ATTR_TYPE  = 'data-type';
    const ATTR_THEME = 'data-theme';
    const ATTR_SIZE  = 'data-size';

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get image type attribute.
     *
     * @return array
     */
    public function getImageAttribute();

    /**
     * Get audio type attribute.
     *
     * @return array
     */
    public function getAudioAttribute();

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
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
    public function build($siteKey, array $items = []);

    /**
     * Prepare the name and id attributes.
     *
     * @param  string|null  $name
     *
     * @return array
     */
    public function prepareNameAttribute($name);
}
