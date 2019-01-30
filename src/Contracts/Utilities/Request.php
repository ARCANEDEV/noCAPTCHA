<?php namespace Arcanedev\NoCaptcha\Contracts\Utilities;

/**
 * Interface  RequestInterface
 *
 * @package   Arcanedev\NoCaptcha\Contracts\Utilities
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Request
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Run the request and get response.
     *
     * @param  string  $url
     * @param  bool    $curled
     *
     * @return string
     */
    public function send($url, $curled = true);
}
