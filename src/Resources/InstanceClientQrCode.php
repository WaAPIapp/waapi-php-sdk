<?php

namespace WaAPI\WaAPISdk\Resources;

use WaAPI\WaAPISdk\Exceptions\FailedActionException;
use WaAPI\WaAPISdk\Exceptions\NotFoundException;
use WaAPI\WaAPISdk\Exceptions\ValidationException;
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
