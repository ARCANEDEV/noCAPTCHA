<?php
//
namespace Arcanedev\NoCaptcha\Contracts;

interface NoCaptchaInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Display Captcha
     *
     * @param  array $attributes
     *
     * @return string
     */
    public function display($attributes = []);

    /**
     * Display image Captcha
     *
     * @param  array $attributes
     *
     * @return string
     */
    public function image($attributes = []);

    /**
     * Display audio Captcha
     *
     * @param  array $attributes
     *
     * @return string
     */
    public function audio($attributes = []);

    /**
     * Verify Response
     *
     * @param  string $response
     * @param  string $clientIp
     *
     * @return bool
     */
    public function verify($response, $clientIp = null);

    /**
     * Get script tag
     *
     * @return string
     */
    public function script();
}