<?php

declare(strict_types=1);

namespace WaAPI\WaAPISdk\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use WaAPI\WaAPISdk\WaAPISdk;

/**
 * A faked transport, so a test asserts what the SDK sends rather than what the
 * live service answers.
 *
 * The constructor already accepts a Guzzle client, so nothing in the package
 * needed changing to make this possible -- what was missing was a test that
 * used it. Before this, the suite was a single assertTrue(true).
 */
trait FakesTheApi
{
    /** @var list<array<string, mixed>> */
    protected array $recorded = [];

    /**
     * `status` belongs at the TOP level of the body, not inside `data`:
     * MakesHttpRequests checks $body['status'] === 'success' there and throws
     * the raw body as an exception otherwise.
     *
     * @param  array<string, mixed>  $data
     */
    protected function fakeAction(array $data = [], int $status = 200): WaAPISdk
    {
        $this->recorded = [];

        $mock = new MockHandler(array_fill(0, 50, new Response(
            $status,
            ['Content-Type' => 'application/json'],
            json_encode([
                'status' => 'success',
                'data' => $data + ['id' => 1],
            ], JSON_THROW_ON_ERROR),
        )));

        $stack = HandlerStack::create($mock);
        $stack->push(Middleware::history($this->recorded));

        return new WaAPISdk('test-token', new Client(['handler' => $stack]));
    }

    /**
     * Asserts the action that was called, that the instance id reached the URL
     * rather than the payload, and the exact body that carried the rest.
     *
     * @param  array<string, mixed>  $expected
     */
    protected function assertActionCalled(string $action, array $expected, int $instanceId = 1): void
    {
        $this->assertNotEmpty($this->recorded, 'The SDK sent no request at all.');

        $request = $this->recorded[0]['request'];
        $path = $request->getUri()->getPath();

        $this->assertStringEndsWith(
            "/instances/{$instanceId}/client/action/{$action}",
            $path,
            'The method called a different action, or put the instance id somewhere other than the URL.'
        );

        $raw = (string) $request->getBody();
        $body = json_decode($raw, true);

        // Requests go out as form_params unless the payload carries a `json`
        // key (MakesHttpRequests::request), so the body is URL-encoded.
        if (! is_array($body)) {
            parse_str($raw, $body);
        }

        foreach ($expected as $key => $value) {
            $this->assertArrayHasKey($key, $body, "Parameter {$key} never reached the request body.");

            // Compared as strings: form encoding has no types, so 42 arrives as
            // "42". Asserting identity would test the encoder, not the method.
            $this->assertSame(
                $this->flatten($value),
                $this->flatten($body[$key]),
                "Parameter {$key} arrived with a different value."
            );
        }
    }

    /**
     * @param  mixed  $value
     */
    private function flatten($value): string
    {
        if (is_array($value)) {
            return implode('|', array_map(fn ($v): string => $this->flatten($v), $value));
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        return (string) $value;
    }
}
