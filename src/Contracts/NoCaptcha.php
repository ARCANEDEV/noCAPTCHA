<?php

declare(strict_types=1);

namespace Arcanedev\NoCaptcha\Contracts;

use Arcanedev\NoCaptcha\Contracts\Utilities\Request;

/**
 * Interface  NoCaptcha
 *
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface NoCaptcha
{
    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Set HTTP Request Client.
     *
     * @param  \Arcanedev\NoCaptcha\Contracts\Utilities\Request  $request
     *
     * @return $this
     */
    public function setRequestClient(Request $request);

    /**
     * Set language code.
     *
     * @param  string  $lang
     *
     * @return $this
     */
    public function setLang($lang);

    /**
     * Get language code.
     *
     * @return string|null
     */
    public function getLang();

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Verify Response.
     *
     * @param  string  $response
     * @param  string  $clientIp
     *
     * @return \Arcanedev\NoCaptcha\Utilities\ResponseV3
     */
    public function verify($response, $clientIp = null);

    /**
     * Get script tag.
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function script();

    /**
     * Get the NoCaptcha API Script.
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function getApiScript();
}
