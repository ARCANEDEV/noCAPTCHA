<?php namespace Arcanedev\NoCaptcha;

use Arcanedev\NoCaptcha\Utilities\Attributes;
use Arcanedev\NoCaptcha\Utilities\ResponseV2;
use Illuminate\Support\Arr;

/**
 * Class     NoCaptchaV2
 *
 * @package  Arcanedev\NoCaptcha
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class NoCaptchaV2 extends AbstractNoCaptcha
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * Decides if we've already loaded the script file or not.
     *
     * @param bool
     */
    protected $scriptLoaded = false;

    /**
     * noCaptcha Attributes
     *
     * @var \Arcanedev\NoCaptcha\Utilities\Attributes
     */
    protected $attributes;

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
     * @param  array        $attributes
     */
    public function __construct($secret, $siteKey, $lang = null, array $attributes = [])
    {
        parent::__construct($secret, $siteKey, $lang);

        $this->setAttributes(new Attributes($attributes));
    }

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get script source link.
     *
     * @param  string|null  $callbackName
     *
     * @return string
     */
    private function getScriptSrc($callbackName = null)
    {
        $queries = [];

        if ($this->hasLang())
            Arr::set($queries, 'hl', $this->lang);

        if ($this->hasCallbackName($callbackName)) {
            Arr::set($queries, 'onload', $callbackName);
            Arr::set($queries, 'render', 'explicit');
        }

        return static::CLIENT_URL . (count($queries) ? '?' . http_build_query($queries) : '');
    }

    /**
     * Set noCaptcha Attributes.
     *
     * @param  \Arcanedev\NoCaptcha\Utilities\Attributes  $attributes
     *
     * @return self
     */
    public function setAttributes(Attributes $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Display Captcha.
     *
     * @param  string|null  $name
     * @param  array        $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function display($name = null, array $attributes = [])
    {
        $attributes = $this->attributes->build($this->siteKey, array_merge(
            $this->attributes->prepareNameAttribute($name),
            $attributes
        ));

        return $this->toHtmlString("<div {$attributes}></div>");
    }

    /**
     * Display image Captcha.
     *
     * @param  string|null  $name
     * @param  array        $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function image($name = null, array $attributes = [])
    {
        return $this->display(
            $name, array_merge($attributes, $this->attributes->getImageAttribute())
        );
    }

    /**
     * Display audio Captcha.
     *
     * @param  string|null  $name
     * @param  array        $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function audio($name = null, array $attributes = [])
    {
        return $this->display(
            $name, array_merge($attributes, $this->attributes->getAudioAttribute())
        );
    }
    /**
     * Display an invisible Captcha (bind the challenge to a button).
     *
     * @param  string  $value
     * @param  array   $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function button($value, array $attributes = [])
    {
        $attributes = $this->attributes->build($this->siteKey, array_merge([
            'data-callback' => 'onSubmit',
        ], $attributes));

        return $this->toHtmlString(
            "<button {$attributes}>{$value}</button>"
        );
    }

    /**
     * Get script tag.
     *
     * @param  string|null  $callbackName
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function script($callbackName = null)
    {
        $script = '';

        if ( ! $this->scriptLoaded) {
            $script = '<script src="'.$this->getScriptSrc($callbackName).'" async defer></script>';
            $this->scriptLoaded = true;
        }

        return $this->toHtmlString($script);
    }

    /**
     * Get the NoCaptcha API Script.
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function getApiScript()
    {
        return $this->toHtmlString(
            "<script>
                window.noCaptcha = {
                    captchas: [],
                    reset: function(name) {
                        var captcha = window.noCaptcha.get(name);
        
                        if (captcha)
                            window.noCaptcha.resetById(captcha.id);
                    },
                    resetById: function(id) {
                        grecaptcha.reset(id);
                    },
                    get: function(name) {
                        return window.noCaptcha.find(function (captcha) {
                            return captcha.name === name;
                        });
                    },
                    getById: function(id) {
                        return window.noCaptcha.find(function (captcha) {
                            return captcha.id === id;
                        });
                    },
                    find: function(callback) {
                        return window.noCaptcha.captchas.find(callback);
                    },
                    render: function(name, sitekey) {
                        var captcha = {
                            id: grecaptcha.render(name, {'sitekey' : sitekey}),
                            name: name
                        };
                        
                        window.noCaptcha.captchas.push(captcha);
                        
                        return captcha;
                    }
                }
            </script>"
        );
    }

    /**
     * Get script tag with a callback function.
     *
     * @param  array   $captchas
     * @param  string  $callbackName
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function scriptWithCallback(array $captchas, $callbackName = 'captchaRenderCallback')
    {
        $script = $this->script($callbackName)->toHtml();

        if ( ! empty($script) && ! empty($captchas)) {
            $script = implode(PHP_EOL, [implode(PHP_EOL, [
                $this->getApiScript()->toHtml(),
                '<script>',
                    "var $callbackName = function() {",
                        $this->renderCaptchas($captchas),
                    '};',
                '</script>'
            ]), $script]);
        }

        return $this->toHtmlString($script);
    }

    /**
     * Rendering captchas with callback function.
     *
     * @param  array  $captchas
     *
     * @return string
     */
    private function renderCaptchas(array $captchas)
    {
        return implode(PHP_EOL, array_map(function($captcha) {
            return "if (document.getElementById('{$captcha}')) { window.noCaptcha.render('{$captcha}', '{$this->siteKey}'); }";
        }, $captchas));
    }

    /* -----------------------------------------------------------------
     |  Check Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check if callback is not empty.
     *
     * @param  string|null  $callbackName
     *
     * @return bool
     */
    private function hasCallbackName($callbackName)
    {
        return ! (is_null($callbackName) || trim($callbackName) === '');
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Parse the response.
     *
     * @param  string  $json
     *
     * @return \Arcanedev\NoCaptcha\Utilities\AbstractResponse|mixed
     */
    protected function parseResponse($json)
    {
        return ResponseV2::fromJson($json);
    }
}
