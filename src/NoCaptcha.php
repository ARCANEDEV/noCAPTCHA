<?php namespace Arcanedev\NoCaptcha;

use Arcanedev\NoCaptcha\Contracts\NoCaptchaInterface;
use Arcanedev\NoCaptcha\Contracts\Utilities\RequestInterface;
use Arcanedev\NoCaptcha\Exceptions\ApiException;
use Arcanedev\NoCaptcha\Exceptions\InvalidTypeException;
use Arcanedev\NoCaptcha\Utilities\Request;

class NoCaptcha implements NoCaptchaInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const CLIENT_URL = 'https://www.google.com/recaptcha/api.js';
    const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';
    const ATTR_TYPE  = 'data-type';
    const ATTR_THEME = 'data-theme';

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

    /**
     * Decides if we've already loaded the script file or not.
     *
     * @param bool
     */
    protected $scriptLoaded = false;

    /**
     * HTTP Request Client
     *
     * @var RequestInterface
     */
    protected $request;

    /**
     * The types of CAPTCHA to serve
     *
     * @var array
     */
    private $types   = ['image', 'audio'];

    /**
     * The color themes of the widget
     *
     * @var array
     */
    private $themes = ['light', 'dark'];

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

        $this->setRequestClient(new Request);
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
     * Get class attribute
     */
    private function getDefaultAttribute()
    {
        return [
            'data-sitekey' => $this->siteKey,
            'class'        => 'g-recaptcha',
        ];
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

    /**
     * Set HTTP Request Client
     * @param RequestInterface $request
     *
     * @return NoCaptcha
     */
    public function setRequestClient(RequestInterface $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get image type attribute
     *
     * @return array
     */
    protected function getImageTypeAttribute()
    {
        return [self::ATTR_TYPE => 'image'];
    }

    /**
     * Get audio type attribute
     *
     * @return array
     */
    private function getAudioTypeAttribute()
    {
        return [self::ATTR_TYPE => 'audio'];
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
        return '<div' . $this->attributes($attributes) . '></div>';
    }

    /**
     * Prepare attributes
     *
     * @param  array $attributes
     *
     * @return string
     */
    protected function attributes(array $attributes)
    {
        $this->checkAttributes($attributes);

        $attributes = array_merge(
            $attributes,
            $this->getDefaultAttribute()
        );

        return $this->buildAttributes($attributes);
    }

    /**
     * Display image Captcha
     *
     * @param  array $attributes
     *
     * @return string
     */
    public function image($attributes = [])
    {
        return $this->display(array_merge(
            $attributes,
            $this->getImageTypeAttribute()
        ));
    }

    /**
     * Display audio Captcha
     *
     * @param  array $attributes
     *
     * @return string
     */
    public function audio($attributes = [])
    {
        return $this->display(array_merge(
            $attributes,
            $this->getAudioTypeAttribute()
        ));
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
        if (empty($response)) {
            return false;
        }

        $response = $this->sendVerifyRequest([
            'secret'   => $this->secret,
            'response' => $response,
            'remoteip' => $clientIp
        ]);

        return isset($response['success']) and
               $response['success'] === true;
    }

    /**
     * Get script tag
     *
     * @return string
     */
    public function script()
    {
        if ($this->scriptLoaded) {
            return '';
        }

        $this->scriptLoaded = true;

        return '<script src="' . $this->getScriptSrc() . '" async defer></script>';
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

    /**
     * Check attributes
     *
     * @param array $attributes
     */
    private function checkAttributes(array &$attributes)
    {
        $this->checkTypeAttribute($attributes);
        $this->checkThemeAttribute($attributes);
    }

    /**
     * Check type attribute
     *
     * @param array $attributes
     */
    private function checkTypeAttribute(array &$attributes)
    {
        if (array_key_exists(self::ATTR_TYPE, $attributes)) {
            $this->checkDataAttribute($attributes, self::ATTR_TYPE, 'image', $this->types);
        }
    }

    /**
     * Check theme attribute
     *
     * @param array $attributes
     */
    private function checkThemeAttribute(array &$attributes)
    {
        if (array_key_exists(self::ATTR_THEME, $attributes)) {
            $this->checkDataAttribute($attributes, self::ATTR_THEME, 'light', $this->themes);
        }
    }

    /**
     * Check g-recaptcha data Attribute
     *
     * @param array  $attributes
     * @param string $name
     * @param string $default
     * @param array  $available
     */
    private function checkDataAttribute(array &$attributes, $name, $default, array $available)
    {
        if (
            ! is_string($attributes[ $name ]) or
            ! in_array($attributes[ $name ], $available)
        ) {
            $attributes[ $name ] = $default;
        }

        $attributes[ $name ] = strtolower(trim($attributes[ $name ]));
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Send verify request to API and get response
     *
     * @param  array $query
     *
     * @return array
     */
    private function sendVerifyRequest(array $query = [])
    {
        $query    = array_filter($query);
        $url      = static::VERIFY_URL . '?' . http_build_query($query);
        $response = $this->request->send($url);

        return $response;
    }

    /**
     * Build attributes
     *
     * @param  array  $attributes
     *
     * @return string
     */
    protected function buildAttributes(array $attributes)
    {
        $output = [];

        foreach (array_reverse($attributes) as $key => $value) {
            $output[] = trim($key) . '="' . trim($value) . '"';
        }

        return ' ' . implode(' ', $output);
    }
}
