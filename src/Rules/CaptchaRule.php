<?php namespace Arcanedev\NoCaptcha\Rules;

use Arcanedev\NoCaptcha\Contracts\NoCaptcha;
use Illuminate\Contracts\Validation\Rule;

/**
 * Class     CaptchaRule
 *
 * @package  Arcanedev\NoCaptcha\Rules
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CaptchaRule implements Rule
{
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
        return app(NoCaptcha::class)->verify($value, request()->ip());
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.captcha');
    }
}
