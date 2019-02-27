<?php namespace Arcanedev\NoCaptcha;

use Arcanedev\NoCaptcha\Utilities\Request;
use Illuminate\Support\HtmlString;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class     AbstractNoCaptcha
 *
 * @package  Arcanedev\NoCaptcha
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class AbstractNoCaptcha implements Contracts\NoCaptcha
{
    /* -----------------------------------------------------------------
     |  Constants
     | -----------------------------------------------------------------
     */

    const CAPTCHA_NAME = 'g-recaptcha-response';

    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The shared key between your site and ReCAPTCHA
     *
     * @var string
     */
    protected $secret;

    /**
     * Your site key
     *
     * @var string
     */
    protected $siteKey;

    /**
     * Forces the widget to render in a specific language.
     * Auto-detects the user's language if unspecified.
     *
     * @var string
     */
    protected $lang;

    /**
     * HTTP Request Client
     *
     * @var \Arcanedev\NoCaptcha\Contracts\Utilities\Request
     */
    protected $request;

    /**
     * ReCaptcha's response.
     *
     * @var \Arcanedev\NoCaptcha\Utilities\ResponseV3|null
     */
    protected $response;

    /**
     * Use the global domain.
     *
     * @var bool
     */
    public static $useGlobalDomain = false;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * NoCaptcha constructor.
     *
     * @param  string       $secret
     * @param  string       $siteKey
     * @param  string|null  $lang
     */
    public function __construct($secret, $siteKey, $lang = null)
    {
        $this->setSecret($secret);
        $this->setSiteKey($siteKey);
        $this->setLang($lang);

        $this->setRequestClient(new Request);
    }

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Set the secret key.
     *
     * @param  string  $secret
     *
     * @return $this
     */
    protected function setSecret($secret)
    {
        self::checkKey('secret key', $secret);

        $this->secret = $secret;

        return $this;
    }

    /**
     * Get the site key.
     *
     * @return string
     */
    public function getSiteKey()
    {
        return $this->siteKey;
    }

    /**
     * Set Site key.
     *
     * @param  string  $siteKey
     *
     * @return $this
     */
    protected function setSiteKey($siteKey)
    {
        self::checkKey('site key', $siteKey);

        $this->siteKey = $siteKey;

        return $this;
    }

    /**
     * Set language code.
     *
     * @param  string  $lang
     *
     * @return $this
     */
    public function setLang($lang)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * Set HTTP Request Client.
     *
     * @param  \Arcanedev\NoCaptcha\Contracts\Utilities\Request  $request
     *
     * @return $this
     */
    public function setRequestClient(Contracts\Utilities\Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get the last response.
     *
     * @return \Arcanedev\NoCaptcha\Utilities\AbstractResponse|null
     */
    public function getLastResponse()
    {
        return $this->response;
    }

    /**
     * Get the client url.
     *
     * @return string
     */
    public static function getClientUrl()
    {
        return static::$useGlobalDomain
            ? 'https://www.recaptcha.net/recaptcha/api.js'
            : 'https://www.google.com/recaptcha/api.js';
    }

    /**
     * Get the verification url.
     *
     * @return string
     */
    public static function getVerificationUrl()
    {
        return static::$useGlobalDomain
            ? 'https://www.recaptcha.net/recaptcha/api/siteverify'
            : 'https://www.google.com/recaptcha/api/siteverify';
    }

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
     * @return \Arcanedev\NoCaptcha\Utilities\AbstractResponse|mixed
     */
    public function verify($response, $clientIp = null)
    {
        return $this->response = $this->sendVerifyRequest([
            'secret'   => $this->secret,
            'response' => $response,
            'remoteip' => $clientIp
        ]);
    }

    /**
     * Calls the reCAPTCHA siteverify API to verify whether the user passes CAPTCHA
     * test using a PSR-7 ServerRequest object.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface  $request
     *
     * @return \Arcanedev\NoCaptcha\Utilities\AbstractResponse
     */
    public function verifyRequest(ServerRequestInterface $request)
    {
        $body   = $request->getParsedBody();
        $server = $request->getServerParams();

        return $this->verify(
            $body[self::CAPTCHA_NAME] ?? '',
            $server['REMOTE_ADDR'] ?? null
        );
    }

    /**
     * Send verify request to API and get response.
     *
     * @param  array  $query
     *
     * @return \Arcanedev\NoCaptcha\Utilities\ResponseV3
     */
    protected function sendVerifyRequest(array $query = [])
    {
        $query = array_filter($query);
        $json  = $this->request->send(
            $this->getVerificationUrl().'?'.http_build_query($query)
        );

        return $this->parseResponse($json);
    }

    /**
     * Parse the response.
     *
     * @param  string  $json
     *
     * @return \Arcanedev\NoCaptcha\Utilities\AbstractResponse|mixed
     */
    abstract protected function parseResponse($json);

    /* -----------------------------------------------------------------
     |  Check Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check if has lang.
     *
     * @return bool
     */
    protected function hasLang()
    {
        return ! empty($this->lang);
    }

    /**
     * Check key.
     *
     * @param  string  $name
     * @param  string  $value
     */
    private static function checkKey($name, &$value)
    {
        self::checkIsString($name, $value);

        $value = trim($value);

        self::checkIsNotEmpty($name, $value);
    }

    /**
     * Check if the value is a string value.
     *
     * @param  string  $name
     * @param  string  $value
     *
     * @throws \Arcanedev\NoCaptcha\Exceptions\ApiException
     */
    private static function checkIsString($name, $value)
    {
        if ( ! is_string($value)) {
            throw new Exceptions\ApiException(
                "The {$name} must be a string value, ".gettype($value).' given.'
            );
        }
    }

    /**
     * Check if the value is not empty.
     *
     * @param string  $name
     * @param string  $value
     *
     * @throws \Arcanedev\NoCaptcha\Exceptions\ApiException
     */
    private static function checkIsNotEmpty($name, $value)
    {
        if (empty($value)) {
            throw new Exceptions\ApiException("The {$name} must not be empty");
        }
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Transform the string to an Html serializable object
     *
     * @param  string  $html
     *
     * @return \Illuminate\Support\HtmlString
     */
    protected function toHtmlString($html)
    {
        return new HtmlString($html);
    }
}
