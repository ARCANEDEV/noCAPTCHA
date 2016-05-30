<?php namespace Arcanedev\NoCaptcha\Contracts;

use Arcanedev\NoCaptcha\Contracts\Utilities\AttributesInterface;
use Arcanedev\NoCaptcha\Contracts\Utilities\RequestInterface;

/**
 * Interface  NoCaptcha
 *
 * @package   Arcanedev\NoCaptcha\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface NoCaptcha
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set HTTP Request Client.
     *
     * @param  \Arcanedev\NoCaptcha\Contracts\Utilities\RequestInterface  $request
     *
     * @return self
     */
    public function setRequestClient(RequestInterface $request);

    /**
     * Set noCaptcha Attributes.
     *
     * @param  \Arcanedev\NoCaptcha\Contracts\Utilities\AttributesInterface  $attributes
     *
     * @return self
     */
    public function setAttributes(AttributesInterface $attributes);

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Display Captcha.
     *
     * @param  string|null  $name
     * @param  array        $attributes
     *
     * @return string
     */
    public function display($name, array $attributes = []);

    /**
     * Display image Captcha.
     *
     * @param  string|null  $name
     * @param  array        $attributes
     *
     * @return string
     */
    public function image($name, array $attributes = []);

    /**
     * Display audio Captcha.
     *
     * @param  string|null  $name
     * @param  array        $attributes
     *
     * @return string
     */
    public function audio($name, array $attributes = []);

    /**
     * Verify Response.
     *
     * @param  string  $response
     * @param  string  $clientIp
     *
     * @return bool
     */
    public function verify($response, $clientIp = null);

    /**
     * Get script tag.
     *
     * @return string
     */
    public function script();

    /**
     * Get script tag with a callback function.
     *
     * @param  array   $captchas
     * @param  string  $callbackName
     *
     * @return string
     */
    public function scriptWithCallback(array $captchas, $callbackName = 'captchaRenderCallback');
}
