<?php

namespace WaAPI\WaAPISdk\Resources;

use WaAPI\WaAPISdk\Exceptions\FailedActionException;
use WaAPI\WaAPISdk\Exceptions\NotFoundException;
use WaAPI\WaAPISdk\Exceptions\ValidationException;
use GuzzleHttp\Exception\GuzzleException;

class WebhookSubscription extends Resource
{

    /**
     * The unique id of this subscription.
     *
     * @var int
     */
    public $id;

    /**
     * The unique id of the instance this subscription belongs to.
     *
     * @var int|string
     */
    public $instanceId;

    /**
     * The URL that receives event payloads.
     *
     * @var string
     */
    public $url;

    /**
     * All subscribed events.
     *
     * @var string[]
     */
    public $events;

    /**
     * Who registered this subscription (zapier, make, n8n or api).
     *
     * @var string
     */
    public $source;

    /**
     * Whether this subscription is currently active.
     *
     * @var bool
     */
    public $isActive;

    /**
     * The date and time this subscription was created.
     *
     * @var string
     */
    public $createdAt;

    /**
     * @return void
     *
     * @throws FailedActionException
     * @throws NotFoundException
     * @throws ValidationException
     * @throws GuzzleException
     */
    public function delete() {
        $this->whatsAppSdk->deleteWebhookSubscription($this->instanceId, $this->id);
    }

}
