<?php

namespace Eaze\WhatsAppPhpSdk\Resources;

use Eaze\WhatsAppPhpSdk\Exceptions\FailedActionException;
use Eaze\WhatsAppPhpSdk\Exceptions\NotFoundException;
use Eaze\WhatsAppPhpSdk\Exceptions\ValidationException;
use GuzzleHttp\Exception\GuzzleException;

class InstanceClientQrCode extends Resource
{

    /**
     * The unique id of this instance.
     *
     * @var string
     */
    public $instanceId;

    /**
     * The QR code as a base64 image string (data:image/png;base64,...). Set this string in the src attribute of a &lt;img src=""&gt; html tag.
     *
     * @var string
     */
    public $qrCode;

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