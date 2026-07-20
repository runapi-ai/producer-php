# Producer PHP SDK for RunAPI

[![Packagist](https://img.shields.io/packagist/v/runapi-ai/producer)](https://packagist.org/packages/runapi-ai/producer)
[![License](https://img.shields.io/github/license/runapi-ai/producer-php)](https://github.com/runapi-ai/producer-php/blob/main/LICENSE)

The Producer PHP SDK is the language-specific package for Producer
on RunAPI. Use this package when your application needs Composer installs,
associative-array request bodies, task status lookup, and consistent RunAPI
errors in PHP.

This README is the PHP package guide for the public `producer-php` split
repository. For model details, use https://runapi.ai/models/producer; for API
reference, use https://runapi.ai/docs#producer; for SDK docs, use
https://runapi.ai/docs#sdk-producer.

## Install

```bash
composer require runapi-ai/producer
```

## Quick start

```php
<?php

require __DIR__ . "/vendor/autoload.php";

use RunApi\Producer\ProducerClient;

$client = new ProducerClient(); // reads RUNAPI_API_KEY

$task = $client->textToMusic->create([
    'model' => 'fuzz-2.0',
    'lyrics' => '[Verse] Morning light across the room',
    'prompt' => 'Warm acoustic pop with clear vocals',
    'title' => 'Morning Light',
    'vocal_mode' => 'exact_lyrics',
]);

$status = $client->textToMusic->get($task->id);

$result = $client->textToMusic->run([
    'model' => 'fuzz-2.0',
    'lyrics' => '[Verse] Morning light across the room',
    'prompt' => 'Cinematic orchestral music with a gradual build',
    'title' => 'Morning Light',
    'vocal_mode' => 'exact_lyrics',
]);

echo $result->audios[0]->audioUrl . PHP_EOL;
```

Use `create()` to submit a task and return quickly, `get()` to fetch the latest
task state, and `run()` when a script should create and poll until completion.
In web request handlers, prefer `create()` plus webhook or later `get()`
polling so a worker is not held open.

RunAPI-generated file URLs are temporary. Download and store generated files
in your own durable storage within the retention window; do not treat returned
URLs as long-term assets.

## Language notes

Pass request parameters as associative arrays with snake_case keys. The
available resources are `textToMusic`. Keep `RUNAPI_API_KEY` in the environment
or your secret manager; never commit API keys or callback secrets.

## Links

- Model page: https://runapi.ai/models/producer
- SDK docs: https://runapi.ai/docs#sdk-producer
- Product docs: https://runapi.ai/docs#producer
- Pricing and rate limits: https://runapi.ai/models/producer
- Full catalog: https://runapi.ai/models
- GitHub repository: https://github.com/runapi-ai/producer-php
- Multi-language SDK repository: https://github.com/runapi-ai/producer-sdk

## License

Licensed under the Apache License, Version 2.0.
