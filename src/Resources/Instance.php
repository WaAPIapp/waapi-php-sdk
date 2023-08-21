<?php

namespace WaAPI\WaAPISdk\Resources;

use WaAPI\WaAPISdk\Exceptions\FailedActionException;
use WaAPI\WaAPISdk\Exceptions\NotFoundException;
use WaAPI\WaAPISdk\Exceptions\ValidationException;
use GuzzleHttp\Exception\GuzzleException;

class Instance extends Resource
{

    /**
     * The unique id of this instance.
     *
     * @var int
     */
    public $id;

    /**
     * The email address of the owner (user) of this instance.
     *
     * @var string
     */
    public $owner;

    /**
     * The url that will be used to send webhooks for subscribed events.
     *
     * @var string
     */
    public $webhookUrl;

    /**
     * All subscribed events. Webhooks will be sent for all of these events.
     *
     * @var string[]
     */
    public $webhookEvents;

    /**
     * @param string|null $webhookUrl
     * @param string[]|null $webhookEvents
     * @return InstanceClientStatus
     *
     * @throws FailedActionException
     * @throws NotFoundException
     * @throws ValidationException
     * @throws GuzzleException
     */
    public function update($webhookUrl = null, $webhookEvents = []) {
        return $this->whatsAppSdk->updateInstance($this->id, $webhookUrl, $webhookEvents);
    }

    /**
     * @return void
     *
     * @throws FailedActionException
     * @throws NotFoundException
     * @throws ValidationException
     * @throws GuzzleException
     */
    public function delete() {
        $this->whatsAppSdk->deleteInstance($this->id);
    }

    /**
     * @return InstanceClientStatus
     *
     * @throws FailedActionException
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function clientStatus() {
        return $this->whatsAppSdk->getInstanceClientStatus($this->id);
    }

    /**
     * @return InstanceClientQrCode
     *
     * @throws FailedActionException
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function clientQrCode() {
        return $this->whatsAppSdk->getInstanceClientQrCode($this->id);
    }

    /**
     * @return InstanceClientMe
     *
     * @throws FailedActionException
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function clientInfo() {
        return $this->whatsAppSdk->getInstanceClientInfo($this->id);
    }

    /**
     * @param string $actionName
     * @param array $actionData
     * @return ExecutedAction
     *
     * @throws FailedActionException
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function executeClientAction($actionName, $actionData = []) {
        return $this->whatsAppSdk->executeInstanceAction($this->id, $actionName, $actionData);
    }

}
