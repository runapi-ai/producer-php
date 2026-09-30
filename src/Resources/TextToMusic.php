<?php

declare(strict_types=1);

namespace RunApi\Producer\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\Models\TaskCreateResponse;
use RunApi\Core\RequestOptions;
use RunApi\Core\Resources\TypedConfiguredResource;
use RunApi\Producer\Models\AudioTaskResponse;
use RunApi\Producer\Models\CompletedAudioTaskResponse;

/** Text to music operations for Producer. */
readonly class TextToMusic extends TypedConfiguredResource
{
    /**
     * Create a text to music task and return immediately with a task id.
     *
     * @param array{
     *   model: string,
     *   prompt: string,
     *   vocal_mode: string,
     *   callback_url?: string,
     *   lyrics?: string,
     *   title?: string
     * } $params
     */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        return parent::create($params, $options);
    }

    /** Fetch the current status of a text to music task. */
    public function get(string $id, ?RequestOptions $options = null): AudioTaskResponse
    {
        $response = parent::get($id, $options);

        /** @var AudioTaskResponse $response */
        return $response;
    }

    /**
     * Create a text to music task and poll until it completes.
     *
     * @param array{
     *   model: string,
     *   prompt: string,
     *   vocal_mode: string,
     *   callback_url?: string,
     *   lyrics?: string,
     *   title?: string
     * } $params
     */
    public function run(array $params, ?RequestOptions $options = null): CompletedAudioTaskResponse
    {
        $response = parent::run($params, $options);

        /** @var CompletedAudioTaskResponse $response */
        return $response;
    }

    /** Create the resource using the shared RunAPI HTTP transport. */
    public static function fromHttp(HttpClient $http): self
    {
        return new self(
            $http,
            '/api/v1/producer/text_to_music',
            AudioTaskResponse::class,
            CompletedAudioTaskResponse::class,
            'text-to-music',
            AudioTaskResponse::class,
            CompletedAudioTaskResponse::class,
        );
    }
}
