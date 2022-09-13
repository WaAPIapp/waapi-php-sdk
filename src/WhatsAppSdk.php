<?php

namespace Eaze\WhatsAppPhpSdk;

use Eaze\WhatsAppPhpSdk\Actions\CheckApiAvailable;
use Eaze\WhatsAppPhpSdk\Actions\ManagesInstances;
use GuzzleHttp\Client as HttpClient;

class WhatsAppSdk
{
    use MakesHttpRequests;
    use CheckApiAvailable;
    use ManagesInstances;

    private const TEST_BASE_URL = 'https://dev-wa-proxy.eazeapps.io';
    private const LIVE_BASE_URL = 'https://wa-proxy.eazeapps.io';

    /**
     * If the test or live environment should be used
     *
     * @var bool
     */
    protected $testMode;

    /**
     * The EAZE WhatsApp API Key,
     *
     * @var string
     */
    protected $apiKey;

    /**
     * The Guzzle HTTP Client instance.
     *
     * @var HttpClient
     */
    public $guzzle;

    /**
     * Number of seconds a request is retried.
     *
     * @var int
     */
    public $timeout = 30;

    /**
     * Create a new WhatsAppSdk instance.
     *
     * @param  string|null  $apiKey
     * @param  bool  $testMode
     * @param  HttpClient|null  $guzzle
     * @return void
     */
    public function __construct($apiKey = null, $testMode = false, HttpClient $guzzle = null)
    {
        $this->testMode = $testMode;

        if (! is_null($apiKey)) {
            $this->setApiKey($apiKey, $guzzle);
        }

        if (! is_null($guzzle)) {
            $this->guzzle = $guzzle;
        }
    }

    /**
     * Transform the items of the collection to the given class.
     *
     * @param  array  $collection
     * @param  string  $class
     * @param  array  $extraData
     * @return array
     */
    protected function transformCollection($collection, $class, $extraData = [])
    {
        return array_map(function ($data) use ($class, $extraData) {
            return new $class($data + $extraData, $this);
        }, $collection);
    }

    /**
     * Set the api key and setup the guzzle request object.
     *
     * @param string $apiKey
     * @param HttpClient|null $guzzle
     * @return $this
     */
    public function setApiKey($apiKey, $guzzle = null)
    {
        $this->apiKey = $apiKey;

        $this->guzzle = $guzzle ?: new HttpClient([
            'base_uri' => $this->testMode ? self::TEST_BASE_URL : self::LIVE_BASE_URL,
            'http_errors' => false,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        return $this;
    }

}