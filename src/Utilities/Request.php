<?php namespace Arcanedev\NoCaptcha\Utilities;

use Arcanedev\NoCaptcha\Exceptions\ApiException;
use Arcanedev\NoCaptcha\Exceptions\InvalidTypeException;
use Arcanedev\NoCaptcha\Exceptions\InvalidUrlException;

class Request
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * URL to request
     *
     * @var string
     */
    protected $url;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Constructor
     *
     * @param string $url
     */
    public function __construct($url = null)
    {
        $this->setUrl($url);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set URL
     *
     * @param  string $url
     *
     * @return $this
     */
    protected function setUrl($url)
    {
        $this->checkUrl($url);

        $this->url = $url;

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create an api request using curl
     *
     * @return array
     */
    protected function curl()
    {
        $ch     = curl_init($this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    /**
     * Create a simple api request using file_get_contents
     *
     * @return array
     */
    protected function simple()
    {
        $result   = file_get_contents($this->url);

        return $result;
    }

    /**
     * Run the request and get response
     *
     * @param bool $curled
     *
     * @return array
     */
    public function send($curled = true)
    {
        $result = ($this->isCurlExists() and $curled === true)
            ? $this->curl()
            : $this->simple();

        return $this->interpretResponse($result);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check URL
     *
     * @param string $url
     *
     * @throws ApiException
     * @throws InvalidTypeException
     * @throws InvalidUrlException
     */
    private function checkUrl(&$url)
    {
        if (! is_string($url)) {
            throw new InvalidTypeException(
                'The url must be a string value, '.gettype($url).' given'
            );
        }

        $url = trim($url);

        if (empty($url)) {
            throw new ApiException('The url must not be empty');
        }

        if(filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new InvalidUrlException('The url [' . $url . '] is invalid');
        }
    }

    /**
     * Check if curl exists
     *
     * @return bool
     */
    private function isCurlExists()
    {
        return function_exists('curl_version');
    }

    /**
     * Check Result
     *
     * @param string $result
     *
     * @return bool
     */
    private function checkResult($result)
    {
        return is_string($result) and ! empty($result);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Convert the json response to array
     *
     * @param string $result
     *
     * @return array
     */
    private function interpretResponse($result)
    {
        if($this->checkResult($result)) {
            return json_decode($result, true);
        }

        return [];
    }
}
