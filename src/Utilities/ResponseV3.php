<?php namespace Arcanedev\NoCaptcha\Utilities;

use Arcanedev\NoCaptcha\Contracts\Utilities\Response as ResponseContract;

/**
 * Class     ResponseV3
 *
 * @package  Arcanedev\NoCaptcha\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ResponseV3 extends AbstractResponse implements ResponseContract
{
    /* -----------------------------------------------------------------
     |  Constants
     | -----------------------------------------------------------------
     */

    /**
     * Invalid JSON received
     */
    const E_INVALID_JSON = 'invalid-json';

    /**
     * Could not connect to service
     */
    const E_CONNECTION_FAILED = 'connection-failed';

    /**
     * Not a success, but no error codes received!
     */
    const E_UNKNOWN_ERROR = 'unknown-error';

    /**
     * Expected hostname did not match
     */
    const E_HOSTNAME_MISMATCH = 'hostname-mismatch';

    /**
     * Expected APK package name did not match
     */
    const E_APK_PACKAGE_NAME_MISMATCH = 'apk_package_name-mismatch';

    /**
     * Expected action did not match
     */
    const E_ACTION_MISMATCH = 'action-mismatch';

    /**
     * Score threshold not met
     */
    const E_SCORE_THRESHOLD_NOT_MET = 'score-threshold-not-met';

    /**
     * Challenge timeout
     */
    const E_CHALLENGE_TIMEOUT = 'challenge-timeout';

    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * Score assigned to the request
     *
     * @var float|null
     */
    private $score;

    /**
     * Action as specified by the page
     *
     * @var string
     */
    private $action;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Response constructor.
     *
     * @param  bool         $success
     * @param  array        $errorCodes
     * @param  string|null  $hostname
     * @param  string|null  $challengeTs
     * @param  string|null  $apkPackageName
     * @param  float|null   $score
     * @param  string|null  $action
     */
    public function __construct($success, array $errorCodes = [], $hostname = null, $challengeTs = null, $apkPackageName = null, $score = null, $action = null)
    {
        parent::__construct($success, $errorCodes, $hostname, $challengeTs, $apkPackageName);

        $this->score          = $score;
        $this->action         = $action;
    }

    /* -----------------------------------------------------------------
     |  Getters
     | -----------------------------------------------------------------
     */

    /**
     * Get score
     *
     * @return float
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Build the response from an array.
     *
     * @param  array  $array
     *
     * @return \Arcanedev\NoCaptcha\Utilities\ResponseV3
     */
    public static function fromArray(array $array)
    {
        $hostname       = $array['hostname'] ?? null;
        $challengeTs    = $array['challenge_ts'] ?? null;
        $apkPackageName = $array['apk_package_name'] ?? null;
        $score          = isset($array['score']) ? floatval($array['score']) : null;
        $action         = $array['action'] ?? null;

        if (isset($array['success']) && $array['success'] == true)
            return new static(true, [], $hostname, $challengeTs, $apkPackageName, $score, $action);

        if ( ! (isset($array['error-codes']) && is_array($array['error-codes'])))
            $array['error-codes'] = [ResponseV3::E_UNKNOWN_ERROR];

        return new static(false, $array['error-codes'], $hostname, $challengeTs, $apkPackageName, $score, $action);
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
            'score'            => $this->getScore(),
            'action'           => $this->getAction(),
            'error-codes'      => $this->getErrorCodes(),
        ];
    }

    /* -----------------------------------------------------------------
     |  Check Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check the score.
     *
     * @param  float  $score
     *
     * @return bool
     */
    public function isScore($score)
    {
        return $this->getScore() >= floatval($score);
    }

    /**
     * Check the action name.
     *
     * @param  string  $action
     *
     * @return bool
     */
    public function isAction($action)
    {
        return $this->getAction() === $action;
    }
}
