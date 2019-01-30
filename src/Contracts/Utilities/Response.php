<?php namespace Arcanedev\NoCaptcha\Contracts\Utilities;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

/**
 * Interface     Response
 *
 * @package  Arcanedev\NoCaptcha\Contracts\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Response extends Arrayable, Jsonable, JsonSerializable
{
    /* -----------------------------------------------------------------
     |  Getters
     | -----------------------------------------------------------------
     */

    /**
     * Get error codes.
     *
     * @return array
     */
    public function getErrorCodes();

    /**
     * Get hostname.
     *
     * @return string
     */
    public function getHostname();

    /**
     * Get challenge timestamp
     *
     * @return string
     */
    public function getChallengeTs();

    /**
     * Get APK package name
     *
     * @return string
     */
    public function getApkPackageName();

    /**
     * Get score
     *
     * @return float
     */
    public function getScore();

    /**
     * Get action
     *
     * @return string
     */
    public function getAction();

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Build the response from the expected JSON returned by the service.
     *
     * @param  string  $json
     *
     * @return \Arcanedev\NoCaptcha\Utilities\ResponseV3
     */
    public static function fromJson($json);

    /**
     * Build the response from an array.
     *
     * @param  array  $array
     *
     * @return \Arcanedev\NoCaptcha\Utilities\ResponseV3
     */
    public static function fromArray(array $array);

    /* -----------------------------------------------------------------
     |  Check Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check if the response is successful.
     *
     * @return bool
     */
    public function isSuccess();

    /**
     * Check the score.
     *
     * @param  float  $score
     *
     * @return bool
     */
    public function isScore($score);

    /**
     * Check the hostname.
     *
     * @param  string  $hostname
     *
     * @return bool
     */
    public function isHostname($hostname);

    /**
     * Check the action name.
     *
     * @param  string  $action
     *
     * @return bool
     */
    public function isAction($action);
}
