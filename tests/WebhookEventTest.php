<?php

namespace WaAPI\WaAPISdk\Tests;

use PHPUnit\Framework\TestCase;
use WaAPI\WaAPISdk\Resources\WebhookEvent;

class WebhookEventTest extends TestCase
{
    public function test_media_download_retry_is_a_known_event(): void
    {
        $this->assertSame('media_download_retry', WebhookEvent::MEDIA_DOWNLOAD_RETRY);
        $this->assertContains('media_download_retry', WebhookEvent::ALL);
    }
}
