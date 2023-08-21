<?php

namespace WaAPI\WaAPISdk\Actions;

use WaAPI\WaAPISdk\Exceptions\FailedActionException;
use WaAPI\WaAPISdk\Exceptions\NotFoundException;
use WaAPI\WaAPISdk\Exceptions\ValidationException;
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
