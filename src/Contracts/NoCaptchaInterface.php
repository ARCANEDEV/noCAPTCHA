<?php namespace Arcanedev\NoCaptcha\Contracts;

use Arcanedev\NoCaptcha\Contracts\Utilities\AttributesInterface;
use Arcanedev\NoCaptcha\Contracts\Utilities\RequestInterface;

/**
 * Interface NoCaptchaInterface
 * @package Arcanedev\NoCaptcha\Contracts
 */
interface NoCaptchaInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set HTTP Request Client
     *
     * @param  RequestInterface $request
     *
     * @return NoCaptchaInterface
     */
    public function setRequestClient(RequestInterface $request);

    /**
     * Set noCaptcha Attributes
     *
     * @param  AttributesInterface $attributes
     *
     * @return NoCaptchaInterface
     */
    public function setAttributes(AttributesInterface $attributes);

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
