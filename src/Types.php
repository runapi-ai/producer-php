<?php

declare(strict_types=1);

namespace RunApi\Producer;

final class Types
{
    /**
     * Allowed model slugs for text to music requests.
     *
     * @var list<string>
     */
    public const TEXT_TO_MUSIC_MODELS = [
        'fuzz-2.0',
        'fuzz-2.0-pro',
        'fuzz-2.0-raw',
        'fuzz-1.1-pro',
        'fuzz-1.0-pro',
        'fuzz-1.0',
        'fuzz-1.1',
        'fuzz-0.8',
    ];

    private function __construct()
    {
    }
}
