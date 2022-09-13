<?php

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
        return $this->get("ping") == 'pong';
    }

}