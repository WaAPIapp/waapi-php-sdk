<?php

namespace WaAPI\WaAPISdk\Actions;

use WaAPI\WaAPISdk\Exceptions\FailedActionException;
use WaAPI\WaAPISdk\Exceptions\NotFoundException;
use WaAPI\WaAPISdk\Exceptions\ValidationException;
use WaAPI\WaAPISdk\Resources\ExecutedAction;
use WaAPI\WaAPISdk\Resources\Instance;
use WaAPI\WaAPISdk\Resources\InstanceClientMe;
use WaAPI\WaAPISdk\Resources\InstanceClientQrCode;
use WaAPI\WaAPISdk\Resources\InstanceClientStatus;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

trait ManagesInstances
{

    /**
     * Get the collection of instances.
     *
     * @return Instance[]
     *
     * @throws Exception | FailedActionException | NotFoundException | ValidationException | GuzzleException
     */
    public function instances()
    {
        return $this->transformCollection(
            $this->get('api/v1/instances')['instances'],
            Instance::class
        );
    }

    /**
     * Get an existing instance.
     *
     * @param int|string $instanceId
     * @return Instance
     *
     * @throws Exception | FailedActionException | NotFoundException | ValidationException | GuzzleException
     */
    public function getInstance($instanceId)
    {
        $data = $this->get("api/v1/instances/{$instanceId}")['instance'];

        return new Instance($data, $this);
    }

    /**
     * Get the WhatsApp client status of an existing instance.
     *
     * @param int|string $instanceId
     * @return InstanceClientStatus
     *
     * @throws Exception | FailedActionException | NotFoundException | ValidationException | GuzzleException
     */
    public function getInstanceClientStatus($instanceId)
    {
        $data = $this->get("api/v1/instances/{$instanceId}/client/status")['clientStatus'];

        return new InstanceClientStatus($data, $this);
    }

    /**
     * Get the WhatsApp client QR code of an existing instance. This is only working when the client state is "qr".
     * Otherwise an error will be thrown.
     *
     * @param int|string $instanceId
     * @return InstanceClientQrCode
     *
     * @throws Exception | FailedActionException | NotFoundException | ValidationException | GuzzleException
     */
    public function getInstanceClientQrCode($instanceId)
    {
        $data = $this->get("api/v1/instances/{$instanceId}/client/qr")['qrCode'];

        return new InstanceClientQrCode($data, $this);
    }

    /**
     * Get information about the connected WhatsApp number via the QR code. This is only working after a successful
     * connection via the QR code.
     *
     * @param int|string $instanceId
     * @return InstanceClientMe
     *
     * @throws Exception | FailedActionException | NotFoundException | ValidationException | GuzzleException
     */
    public function getInstanceClientInfo($instanceId)
    {
        $data = $this->get("api/v1/instances/{$instanceId}/client/me")['me'];

        return new InstanceClientMe($data, $this);
    }

    /**
     * Create a new instance. If you want to set a webhook or subscribe to events, update the instance afterwards.
     *
     * @return Instance
     *
     * @throws Exception | FailedActionException | NotFoundException | ValidationException | GuzzleException
     */
    public function createInstance()
    {
        $data = $this->post("api/v1/instances")['instance'];

        return new Instance($data, $this);
    }

    /**
     * Update an existing instance.
     *
     * @param int|string $instanceId
     * @param string|null $webhookUrl
     * @param string[]|null $webhookEvents
     * @return InstanceClientStatus
     *
     * @throws Exception | FailedActionException | NotFoundException | ValidationException | GuzzleException
     */
    public function updateInstance($instanceId, $webhookUrl = null, $webhookEvents = [])
    {
        $data = $this->put("api/v1/instances/{$instanceId}", [
            'webhook' => [
                'url' => $webhookUrl,
                'events' => $webhookEvents ?? []
            ]
        ])['data'];

        return new InstanceClientStatus($data, $this);
    }

    /**
     * Delete an existing instance.
     *
     * @param int|string $instanceId
     * @return void
     *
     * @throws Exception | FailedActionException | NotFoundException | ValidationException | GuzzleException
     */
    public function deleteInstance($instanceId)
    {
        $this->delete("api/v1/instances/{$instanceId}");
    }


    /**
     * Execute an action on the given instance. Each action returns individual/different data.
     *
     * @param int|string $instanceId
     * @param string $actionName
     * @param array $requestData
     * @return ExecutedAction
     *
     * @throws Exception | FailedActionException | NotFoundException | ValidationException | GuzzleException
     */
    public function executeInstanceAction($instanceId, $actionName, $requestData = [])
    {
        $data = $this->post("api/v1/instances/{$instanceId}/client/action/{$actionName}", $requestData)['data'];

        return new ExecutedAction($data, $this);
    }

}
