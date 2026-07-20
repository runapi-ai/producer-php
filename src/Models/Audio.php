<?php

declare(strict_types=1);

namespace RunApi\Producer\Models;

use RunApi\Core\Models\BaseModel;
use RunApi\Core\Support\Payload;

/** Generated Producer audio metadata. */
readonly class Audio extends BaseModel
{
    /**
     * @param array<string, mixed> $raw Raw response payload preserved by `toArray()`.
     */
    public function __construct(
        public ?string $id = null,
        public ?string $audioUrl = null,
        public ?string $imageUrl = null,
        public ?string $modelName = null,
        public ?string $title = null,
        public ?float $durationSeconds = null,
        public ?string $lyrics = null,
        array $raw = [],
    ) {
        parent::__construct($raw === [] ? array_filter([
            'id' => $id,
            'audio_url' => $audioUrl,
            'image_url' => $imageUrl,
            'model_name' => $modelName,
            'title' => $title,
            'duration_seconds' => $durationSeconds,
            'lyrics' => $lyrics,
        ], static fn (mixed $value): bool => $value !== null) : $raw);
    }

    /** @param array<string, mixed> $raw */
    public static function fromArray(array $raw): self
    {
        $durationSeconds = $raw['duration_seconds'] ?? null;
        if ($durationSeconds !== null && !is_int($durationSeconds) && !is_float($durationSeconds)) {
            throw new \RunApi\Core\Errors\ValidationException('duration_seconds must be numeric');
        }

        return new self(
            id: Payload::optionalString($raw, 'id'),
            audioUrl: Payload::optionalString($raw, 'audio_url'),
            imageUrl: Payload::optionalString($raw, 'image_url'),
            modelName: Payload::optionalString($raw, 'model_name'),
            title: Payload::optionalString($raw, 'title'),
            durationSeconds: $durationSeconds === null ? null : (float) $durationSeconds,
            lyrics: Payload::optionalString($raw, 'lyrics'),
            raw: $raw,
        );
    }
}
