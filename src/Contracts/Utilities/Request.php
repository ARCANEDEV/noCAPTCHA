<?php

declare(strict_types=1);

namespace Arcanedev\NoCaptcha\Contracts\Utilities;

/**
 * Interface  Request
 *
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
