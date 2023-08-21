<?php

namespace WaAPI\WaAPISdk\Resources;

use WaAPI\WaAPISdk\Exceptions\FailedActionException;
use WaAPI\WaAPISdk\Exceptions\NotFoundException;
use WaAPI\WaAPISdk\Exceptions\ValidationException;
use GuzzleHttp\Exception\GuzzleException;

class InstanceClientStatus extends Resource
{

    public const STATUS_BOOTING = 'booting';
    public const STATUS_LOADING_SCREEN = 'loading_screen';
    public const STATUS_QR = 'qr';
    public const STATUS_AUTHENTICATED = 'authenticated';
    public const STATUS_AUTH_FAILURE = 'auth_failure';
    public const STATUS_READY = 'ready';
    public const STATUS_DISCONNECTED = 'disconnected';

    /**
     * The unique id of this instance.
     *
     * @var string
     */
    public $instanceId;

    /**
     * The WhatsApp status of this instance.
     *
     * @var string
     */
    public $instanceStatus;

    /**
     * The url that will be used to send webhooks for subscribed events.
     *
     * @var string
     */
    public $instanceWebhook;

    /**
     * All subscribed events. Webhooks will be sent for all of these events.
     *
     * @var string[]
     */
    public $instanceEvents;

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
