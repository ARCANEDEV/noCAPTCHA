<?php namespace Arcanedev\NoCaptcha;

use Arcanedev\Html\Elements\Input;
use Arcanedev\NoCaptcha\Utilities\ResponseV3;
use Illuminate\Support\Arr;

/**
 * Class     NoCaptchaV3
 *
 * @package  Arcanedev\NoCaptcha
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class NoCaptchaV3 extends AbstractNoCaptcha
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

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * @param  string  $name
     *
     * @return \Arcanedev\Html\Elements\Input
     */
    public function input($name = 'g-recaptcha-response')
    {
        return Input::make()->type('hidden')->id($name)->name($name);
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
            $script = implode(PHP_EOL, [
                '<script src="'.$this->getScriptSrc($callbackName).'"></script>',
            ]);
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
                    render: function(action, callback) {
                        grecaptcha.execute('".$this->getSiteKey()."', {action})
                              .then(callback);
                    }
                }
            </script>"
        );
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
        return ResponseV3::fromJson($json);
    }

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

        Arr::set($queries, 'render', $this->getSiteKey());

        if ($this->hasCallbackName($callbackName))
            Arr::set($queries, 'onload', $callbackName);

        return $this->getClientUrl() . (count($queries) ? '?' . http_build_query($queries) : '');
    }
}
