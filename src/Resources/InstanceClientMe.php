<?php

namespace Eaze\WhatsAppPhpSdk\Resources;

use Eaze\WhatsAppPhpSdk\Exceptions\FailedActionException;
use Eaze\WhatsAppPhpSdk\Exceptions\NotFoundException;
use Eaze\WhatsAppPhpSdk\Exceptions\ValidationException;
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