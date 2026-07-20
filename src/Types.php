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
    public const TEXT_TO_MUSIC_MODELS = ['fuzz-2.0'];

    private function __construct()
    {
    }
}
