<?php

declare(strict_types=1);

namespace RunApi\Producer\Models;

use RunApi\Core\Support\Payload;

/** Completed audio task response returned by `run()`; audios are guaranteed present. */
readonly class CompletedAudioTaskResponse extends AudioTaskResponse
{
    /**
     * Hydrate a completed task response and require generated audios.
     *
     * @param array<string, mixed> $raw
     */
    public static function fromArray(array $raw): self
    {
        return new self(id: Payload::string($raw, 'id'), status: Payload::string($raw, 'status'), error: self::error($raw), audios: self::audios($raw, required: true), generationStage: Payload::optionalString($raw, 'generation_stage'), raw: $raw);
    }

    /** Narrow a polled task response after completion has been confirmed. */
    public static function fromResponse(AudioTaskResponse $response): self
    {
        return self::fromArray($response->toArray());
    }
}
