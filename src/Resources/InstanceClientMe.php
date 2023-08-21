<?php

namespace WaAPI\WaAPISdk\Resources;

use WaAPI\WaAPISdk\Exceptions\FailedActionException;
use WaAPI\WaAPISdk\Exceptions\NotFoundException;
use WaAPI\WaAPISdk\Exceptions\ValidationException;
use GuzzleHttp\Exception\GuzzleException;

class InstanceClientMe extends Resource
{

    /**
     * The unique id of this instance.
     *
     * @var string
     */
    public $instanceId;

    /**
     * Name of the contactId
     *
     * @var string
     */
    public $displayName;

    /**
     * The contact id of the phone number that was connected via the qr code
     *
     * @var string
     */
    public $contactId;

    /**
     * The contactId as a formatted number
     *
     * @var string
     */
    public $formattedNumber;

    /**
     * Profile image as url of the formatted number / contact
     *
     * @var string
     */
    public $profilePicUrl;

    public function __construct(array $attributes, $whatsAppSdk = null)
    {
        parent::__construct($attributes, $whatsAppSdk);

        $this->fill($attributes['data']);
    }

    /**
     * @return Instance
     * @throws FailedActionException
     * @throws NotFoundException
     * @throws ValidationException
     * @throws GuzzleException
     */
    public function instance() {
        return $this->whatsAppSdk->getInstance($this->instanceId);
    }
}
