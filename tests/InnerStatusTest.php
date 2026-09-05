<?php

namespace WaAPI\WaAPISdk\Tests;

use PHPUnit\Framework\TestCase;
use WaAPI\WaAPISdk\Exceptions\FailedActionException;

/**
 * The API answers with two status fields: the top-level `status` says the
 * request reached the instance, `data.status` says the instance carried the
 * action out -- and the inner one is authoritative. A malformed chatId comes
 * back as HTTP 200, status success, data.status error, with nothing sent.
 */
class InnerStatusTest extends TestCase
{
    use FakesTheApi;

    public function test_an_inner_status_error_is_a_failed_action_not_a_sent_message(): void
    {
        $sdk = $this->fakeRefusedAction('incorrect chatId format.', '"bogus" is missing a domain, expected <id>@c.us.');

        $this->expectException(FailedActionException::class);
        $this->expectExceptionMessage('incorrect chatId format.');

        $sdk->executeInstanceAction(1, 'send-message', ['chatId' => 'bogus', 'message' => 'x']);
    }

    public function test_a_carried_out_action_with_inner_status_success_is_returned(): void
    {
        $sdk = $this->fakeAction(['status' => 'success', 'instanceId' => '1', 'data' => ['_data' => ['id' => ['_serialized' => 'true_x_y']]]]);

        $result = $sdk->executeInstanceAction(1, 'send-message', ['chatId' => '4915112345678@c.us', 'message' => 'hi']);

        $this->assertNotNull($result);
    }

    public function test_a_list_payload_without_inner_status_is_not_a_refusal(): void
    {
        // Several actions return a list in `data`, which carries no status.
        $sdk = $this->fakeAction(['data' => [['id' => 1], ['id' => 2]]]);

        $result = $sdk->executeInstanceAction(1, 'get-chats', []);

        $this->assertNotNull($result);
    }
}
