<?php namespace Arcanedev\NoCaptcha\Contracts\Utilities;

interface AttributesInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const ATTR_TYPE  = 'data-type';
    const ATTR_THEME = 'data-theme';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Build attributes
     *
     * @param  string $siteKey
     * @param  array  $items
     *
     * @return string
     */
    public function build($siteKey, array $items = []);
}
