<?php namespace Arcanedev\NoCaptcha;

use Arcanedev\NoCaptcha\Contracts\NoCaptchaInterface;
use Arcanedev\NoCaptcha\Exceptions\ApiException;
use Arcanedev\NoCaptcha\Exceptions\InvalidTypeException;

class NoCaptcha implements NoCaptchaInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The shared key between your site and ReCAPTCHA
     *
     * @var string
     */
    private $secret;

    /**
     * Your site key
     *
     * @var string
     */
    private $siteKey;

    /**
     * Forces the widget to render in a specific language.
     * Auto-detects the user's language if unspecified.
     *
     * @var string
     */
    protected $lang;

    const CLIENT_URL = 'https://www.google.com/recaptcha/api.js';
    const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Constructor
     *
     * @param string      $secret
     * @param string      $siteKey
     * @param string|null $lang
     */
    public function __construct($secret, $siteKey, $lang = null)
    {
        $this->setSecret($secret);
        $this->setSiteKey($siteKey);
        $this->setLang($lang);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set the secret key
     *
     * @param  string $secret
     *
     * @return NoCaptcha
     */
    protected function setSecret($secret)
    {
        $this->checkSecret($secret);

        $this->secret = $secret;

        return $this;
    }

    /**
     * Set Site key
     *
     * @param  string $siteKey
     *
     * @return NoCaptcha
     */
    protected function setSiteKey($siteKey)
    {
        $this->checkSiteKey($siteKey);

        $this->siteKey = $siteKey;

        return $this;
    }

    /**
     * Get language code
     *
     * @return string
     */
    protected function getLang()
    {
        return $this->lang;
    }

    /**
     * Set language code
     *
     * @param  string $lang
     *
     * @return NoCaptcha
     */
    protected function setLang($lang)
    {
        $this->lang = $lang;

        return $this;
    }

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
    public function display($attributes = [])
    {
        $attributes['data-sitekey'] = $this->siteKey;

        return '<div class="g-recaptcha"' . $this->buildAttributes($attributes) . '></div>';
    }

    /**
     * Verify Response
     *
     * @param  string $response
     * @param  string $clientIp
     *
     * @return bool
     */
    public function verify($response, $clientIp = null)
    {
    }

    /**
     * Get script tag
     *
     * @return string
     */
    public function script()
    {
        return '<script src="' . $this->getScriptSrc() . '" async defer></script>';
    }

    /**
     * Get script source link
     *
     * @return string
     */
    private function getScriptSrc()
    {
        $link = static::CLIENT_URL;

        if ($this->hasLang()) {
            $link .= ('?hl=' . $this->getLang());
        }

        return $link;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if has language code
     *
     * @return bool
     */
    private function hasLang()
    {
        return ! empty($this->lang);
    }

    /**
     * Check secret key
     *
     * @param  string $secret
     *
     * @throws ApiException
     * @throws InvalidTypeException
     */
    private function checkSecret(&$secret)
    {
        if (! is_string($secret)) {
            throw new InvalidTypeException(
                'The secret key must be a string value, '.gettype($secret).' given'
            );
        }

        $secret = trim($secret);

        if (empty($secret)) {
            throw new ApiException('The secret key must not be empty');
        }
    }

    /**
     * Check site key
     *
     * @param  string $siteKey
     *
     * @throws ApiException
     * @throws InvalidTypeException
     */
    private function checkSiteKey(&$siteKey)
    {
        if (! is_string($siteKey)) {
            throw new InvalidTypeException(
                'The site key must be a string value, '.gettype($siteKey).' given'
            );
        }

        $siteKey = trim($siteKey);

        if (empty($siteKey)) {
            throw new ApiException('The site key must not be empty');
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Build attributes
     *
     * @param  array  $attributes
     *
     * @return string
     */
    protected function buildAttributes(array $attributes)
    {
        if (count($attributes) == 0) {
            return '';
        }

        $output = [];

        foreach ($attributes as $key => $value) {
            $output[] = $key . '="' . $value . '"';
        }

        return ' ' . implode(' ', $output);
    }
}
