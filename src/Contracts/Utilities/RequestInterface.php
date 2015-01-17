<?php namespace Arcanedev\NoCaptcha\Contracts\Utilities;

interface RequestInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Run the request and get response
     *
     * @param  string $url
     * @param  bool   $curled
     *
     * @return array
     */
    public function send($url, $curled = true);
}
