<?php

namespace Eaze\WhatsAppPhpSdk\Actions;

use Eaze\WhatsAppPhpSdk\Exceptions\FailedActionException;
use Eaze\WhatsAppPhpSdk\Exceptions\NotFoundException;
use Eaze\WhatsAppPhpSdk\Exceptions\ValidationException;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

trait CheckApiAvailable
{

    /**
     * Check if the EAZE API is available.
     *
     * @return bool
     *
     * @throws Exception | FailedActionException | NotFoundException | ValidationException | GuzzleException
     */
    public function isApiAvailable()
    {
        return $this->get("api/ping") == 'pong';
    }

}