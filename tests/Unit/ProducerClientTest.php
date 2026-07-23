<?php

declare(strict_types=1);

namespace RunApi\Producer\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RunApi\Core\ClientOptions;
use RunApi\Core\Errors\ValidationException;
use RunApi\Core\Tests\Fixtures\QueueHttpClient;
use RunApi\Producer\Models\CompletedAudioTaskResponse;
use RunApi\Producer\ProducerClient;
use RunApi\Producer\Resources\TextToMusic;
use RunApi\Producer\Types;

final class ProducerClientTest extends TestCase
{
    public function testExposesTypedResources(): void
    {
        $client = new ProducerClient(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        self::assertInstanceOf(TextToMusic::class, $client->textToMusic);
    }

    public function testExposesEveryProducerModelSlug(): void
    {
        self::assertSame([
            'fuzz-2.0',
            'fuzz-2.0-pro',
            'fuzz-2.0-raw',
            'fuzz-1.1-pro',
            'fuzz-1.0-pro',
            'fuzz-1.0',
            'fuzz-1.1',
            'fuzz-0.8',
        ], Types::TEXT_TO_MUSIC_MODELS);
    }

    public function testCreatePostsCompactedBodyToCorrectPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
        ]);
        $client = new ProducerClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $task = $client->textToMusic->create([
            'model' => 'fuzz-2.0',
            'lyrics' => '[Verse] Morning light across the room',
            'prompt' => 'A product render',
            'title' => 'Morning Light',
            'vocal_mode' => 'exact_lyrics',
            'callback_url' => '',
            'seed' => null,
        ]);

        $body = json_decode((string) $transport->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);

        self::assertSame('task_1', $task->id);
        self::assertSame('/api/v1/producer/text_to_music', $transport->requests[0]->getUri()->getPath());
        self::assertSame('fuzz-2.0', $body['model']);
        self::assertArrayNotHasKey('callback_url', $body);
        self::assertArrayNotHasKey('seed', $body);
    }

    public function testRunReturnsTypedCompletedResponseAndPreservesUnknownFields(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed","audios":[{"id":"audio_1","audio_url":"https://file.runapi.ai/result","image_url":"https://file.runapi.ai/cover","model_name":"fuzz-2.0","title":"Morning Light","duration_seconds":78.35,"lyrics":"Morning light"}],"generation_stage":"all_audios_ready","extra_field":"kept"}'),
        ]);
        $client = new ProducerClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->textToMusic->run([
            'model' => 'fuzz-2.0',
            'lyrics' => '[Verse] Morning light across the room',
            'prompt' => 'A product render',
            'title' => 'Morning Light',
            'vocal_mode' => 'exact_lyrics',
        ]);

        self::assertInstanceOf(CompletedAudioTaskResponse::class, $result);
        self::assertSame('https://file.runapi.ai/result', $result->audios[0]->audioUrl);
        self::assertSame(78.35, $result->audios[0]->durationSeconds);
        self::assertSame('all_audios_ready', $result->generationStage);
        self::assertSame('kept', $result->toArray()['extra_field']);
        self::assertSame('/api/v1/producer/text_to_music/task_1', $transport->requests[1]->getUri()->getPath());
    }

    public function testCompletedResponseRequiresResultFiles(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed"}'),
        ]);
        $client = new ProducerClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('audios is required');

        $client->textToMusic->run([
            'model' => 'fuzz-2.0',
            'lyrics' => '[Verse] Morning light across the room',
            'prompt' => 'A product render',
            'title' => 'Morning Light',
            'vocal_mode' => 'exact_lyrics',
        ]);
    }

    public function testRejectsInvalidContractEnum(): void
    {
        $client = new ProducerClient(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('vocal_mode must be one of the allowed values');

        $client->textToMusic->create([
            'model' => 'fuzz-2.0',
            'lyrics' => '[Verse] Morning light across the room',
            'prompt' => 'A product render',
            'title' => 'Morning Light',
            'vocal_mode' => 'not-valid',
        ]);
    }

    public function testRejectsInvalidContractModel(): void
    {
        $client = new ProducerClient(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('model must be one of the allowed values');

        $client->textToMusic->create([
            'model' => 'not-a-producer-model',
            'prompt' => 'A product render',
            'vocal_mode' => 'instrumental',
        ]);
    }

    public function testSecondaryResourceUsesItsOwnPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_2"}'),
        ]);
        $client = new ProducerClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $client->textToMusic->create([
            'model' => 'fuzz-2.0',
            'lyrics' => '[Verse] Morning light across the room',
            'prompt' => 'A product render',
            'title' => 'Morning Light',
            'vocal_mode' => 'exact_lyrics',
        ]);

        self::assertSame('/api/v1/producer/text_to_music', $transport->requests[0]->getUri()->getPath());
    }
}
