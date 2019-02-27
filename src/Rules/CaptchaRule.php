<?php namespace Arcanedev\NoCaptcha\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;

/**
 * Class     CaptchaRule
 *
 * @package  Arcanedev\NoCaptcha\Rules
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CaptchaRule implements Rule
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  string|null */
    protected $version;

    /** @var  array */
    protected $skipIps = [];

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * CaptchaRule constructor.
     *
     * @param  string|null  $version
     */
    public function __construct($version = null)
    {
        $this->version($version);
        $this->skipIps(
            config()->get('no-captcha.skip-ips', [])
        );
    }

    /* -----------------------------------------------------------------
     |  Setters
     | -----------------------------------------------------------------
     */

    /**
     * Set the ReCaptcha version.
     *
     * @param  string|null  $version
     *
     * @return $this
     */
    public function version($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Set the ips to skip.
     *
     * @param  string|array  $ip
     *
     * @return $this
     */
    public function skipIps($ip)
    {
        $this->skipIps = Arr::wrap($ip);

        return $this;
    }

    /* -----------------------------------------------------------------
     |  Main methods
     | -----------------------------------------------------------------
     */

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $ip = request()->ip();

        if (in_array($ip, $this->skipIps))
            return true;

        return no_captcha($this->version)
            ->verify($value, $ip)
            ->isSuccess();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return (string) trans('validation.captcha');
    }
}
