<?php

namespace Eaze\WhatsAppPhpSdk\Resources;

use Eaze\WhatsAppPhpSdk\Exceptions\FailedActionException;
use Eaze\WhatsAppPhpSdk\Exceptions\NotFoundException;
use Eaze\WhatsAppPhpSdk\Exceptions\ValidationException;
use GuzzleHttp\Exception\GuzzleException;

class ExecutedAction extends Resource
{

    /**
     * The unique id of this instance.
     *
     * @var string
     */
    public $instanceId;

    /**
     * Response data of the executed action
     *
     * @var array
     */
    public $data;

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