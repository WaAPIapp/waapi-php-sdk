<?php

namespace WaAPI\WaAPISdk;

use WaAPI\WaAPISdk\Exceptions\FailedActionException;
use WaAPI\WaAPISdk\Exceptions\NotFoundException;
use WaAPI\WaAPISdk\Exceptions\TimeoutException;
use WaAPI\WaAPISdk\Exceptions\ValidationException;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

trait MakesHttpRequests
{
    /**
     * Make a GET request to the WaAPI API and return the response.
     *
     * @param string $uri
     * @param bool $checkBodyStatus
     * @return mixed
     *
     * @throws Exception
     * @throws FailedActionException
     * @throws NotFoundException
     * @throws ValidationException
     * @throws GuzzleException
     */
    public function get($uri, $checkBodyStatus = true)
    {
        return $this->request('GET', $uri, [], $checkBodyStatus);
    }

    /**
     * Make a POST request to the WaAPI API and return the response.
     *
     * @param string $uri
     * @param array $payload
     * @param bool $checkBodyStatus
     * @return mixed
     *
     * @throws Exception
     * @throws FailedActionException
     * @throws NotFoundException
     * @throws ValidationException
     * @throws GuzzleException
     */
    public function post($uri, $payload = [], $checkBodyStatus = true)
    {
        return $this->request('POST', $uri, $payload, $checkBodyStatus);
    }

    /**
     * Make a PUT request to the WaAPI API and return the response.
     *
     * @param string $uri
     * @param array $payload
     * @param bool $checkBodyStatus
     * @return mixed
     *
     * @throws Exception
     * @throws FailedActionException
     * @throws NotFoundException
     * @throws ValidationException
     * @throws GuzzleException
     */
    public function put($uri, $payload = [], $checkBodyStatus = true)
    {
        return $this->request('PUT', $uri, $payload, $checkBodyStatus);
    }

    /**
     * Make a DELETE request to the WaAPI API and return the response.
     *
     * @param string $uri
     * @param array $payload
     * @param bool $checkBodyStatus
     * @return mixed
     *
     * @throws Exception
     * @throws FailedActionException
     * @throws NotFoundException
     * @throws ValidationException
     * @throws GuzzleException
     */
    public function delete($uri, $payload = [], $checkBodyStatus = true)
    {
        return $this->request('DELETE', $uri, $payload, $checkBodyStatus);
    }

    /**
     * Make request to the WaAPI API and return the response.
     *
     * @param string $verb
     * @param string $uri
     * @param array $payload
     * @param bool $checkBodyStatus
     * @return mixed
     *
     * @throws Exception
     * @throws FailedActionException
     * @throws NotFoundException
     * @throws ValidationException
     * @throws GuzzleException
     */
    protected function request($verb, $uri, $payload = [], $checkBodyStatus = true)
    {
        if (isset($payload['json'])) {
            $payload = ['json' => $payload['json']];
        } else {
            $payload = empty($payload) ? [] : ['form_params' => $payload];
        }

        $response = $this->guzzle->request($verb, $uri, $payload);

        $statusCode = $response->getStatusCode();

        if ($statusCode < 200 || $statusCode >= 300) {
            return $this->handleRequestError($response);
        }

        $responseBody = (string)$response->getBody();
        $responseBodyArr = json_decode($responseBody, true);

        if ($responseBodyArr != null && $checkBodyStatus) {
            $status = $responseBodyArr['status'] ?? null;
            if ($status != 'success') {
                return $this->handleRequestError($response);
            }

            // Two envelopes, and the inner one is the authoritative answer:
            //   status        did the request reach the instance
            //   data.status   did the instance carry the action out
            // A malformed chatId comes back as HTTP 200, status success,
            // data.status error -- nothing was sent. Checking only the outer
            // field reported that as a delivered message. `data` is a list for
            // some actions and carries no status then; only an explicit
            // inner "error" is a refusal.
            $data = $responseBodyArr['data'] ?? null;
            if (is_array($data) && ($data['status'] ?? null) === 'error') {
                $reason = $data['message'] ?? 'The instance did not carry the action out.';
                $explanation = isset($data['explanation']) ? ' ('.$data['explanation'].')' : '';

                throw new FailedActionException($reason.$explanation);
            }
        }

        return $responseBodyArr ?: $responseBody;
    }

    /**
     * Handle the request error.
     *
     * @param ResponseInterface $response
     * @return mixed
     *
     * @throws Exception
     * @throws FailedActionException
     * @throws NotFoundException
     * @throws ValidationException
     */
    protected function handleRequestError($response)
    {
        if ($response->getStatusCode() == 422) {
            throw new ValidationException(json_decode((string)$response->getBody(), true));
        }

        if ($response->getStatusCode() == 404) {
            throw new NotFoundException();
        }

        if ($response->getStatusCode() == 400) {
            throw new FailedActionException((string)$response->getBody());
        }

        throw new Exception((string)$response->getBody());
    }

    /**
     * Retry the callback or fail after x seconds.
     *
     * @param int $timeout
     * @param callable $callback
     * @param int $sleep
     * @return mixed
     *
     * @throws TimeoutException
     */
    public function retry($timeout, $callback, $sleep = 5)
    {
        $start = time();

        beginning:

        if ($output = $callback()) {
            return $output;
        }

        if (time() - $start < $timeout) {
            sleep($sleep);

            goto beginning;
        }

        if ($output === null || $output === false) {
            $output = [];
        }

        if (!is_array($output)) {
            $output = [$output];
        }

        throw new TimeoutException($output);
    }
}
