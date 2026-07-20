<?php

declare(strict_types=1);

namespace RunApi\Producer;

use RunApi\Core\BaseClient;
use RunApi\Core\ClientOptions;
use RunApi\Producer\Resources\TextToMusic;

/**
 * Producer RunAPI PHP client.
 *
 * The client exposes typed model resources plus the universal `files` and
 * `account` resources.
 */
final class ProducerClient extends BaseClient
{
    /** Text to music operations for Producer. */
    public readonly TextToMusic $textToMusic;

    /** Create a Producer client with optional API key, base URL, and transport overrides. */
    public function __construct(ClientOptions $options = new ClientOptions())
    {
        parent::__construct($options);
        $this->textToMusic = TextToMusic::fromHttp($this->http);
    }
}
