<?php namespace Arcanedev\NoCaptcha\Utilities;

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
     */
    private function checkUrl(&$url)
    {
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
        return json_decode($result, true);
    }
}
