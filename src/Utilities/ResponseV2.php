<?php namespace Arcanedev\NoCaptcha\Utilities;

/**
 * Class     ResponseV2
 *
 * @package  Arcanedev\NoCaptcha\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ResponseV2 extends AbstractResponse
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Build the response from an array.
     *
     * @param  array $array
     *
     * @return \Arcanedev\NoCaptcha\Utilities\ResponseV2
     */
    public static function fromArray(array $array)
    {
        $hostname       = $array['hostname'] ?? null;
        $challengeTs    = $array['challenge_ts'] ?? null;
        $apkPackageName = $array['apk_package_name'] ?? null;

        if (isset($array['success']) && $array['success'] == true)
            return new static(true, [], $hostname, $challengeTs, $apkPackageName);

        if ( ! (isset($array['error-codes']) && is_array($array['error-codes'])))
            $array['error-codes'] = [ResponseV3::E_UNKNOWN_ERROR];

        return new static(false, $array['error-codes'], $hostname, $challengeTs, $apkPackageName);
    }

    /**
     * Convert the response object to array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'success'          => $this->isSuccess(),
            'hostname'         => $this->getHostname(),
            'challenge_ts'     => $this->getChallengeTs(),
            'apk_package_name' => $this->getApkPackageName(),
            'error-codes'      => $this->getErrorCodes(),
        ];
    }
}
