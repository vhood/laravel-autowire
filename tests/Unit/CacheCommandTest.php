<?php

use Vhood\Laravel\AutowireTests\Fixtures\EloquentUserRepository;
use Vhood\Laravel\AutowireTests\Fixtures\UserRepository;
use Vhood\Laravel\AutowireTests\TestCase;

test('it successfully compiles and caches bindings via artisan command', function () {
    /** @var TestCase $this */
    $cachePath = config('autowire.cache_path');

    if (file_exists($cachePath)) {
        @unlink($cachePath);
    }
    expect(file_exists($cachePath))->toBeFalse();

    $this->artisan('autowire:cache')
        ->assertSuccessful()
        ->expectsOutput('Scanning directories for Autowire attributes...')
        ->expectsOutput('Cached 1 bindings successfully!');

    expect(file_exists($cachePath))->toBeTrue();

    $cachedData = require $cachePath;

    expect($cachedData)->toBeArray()
        ->and($cachedData)->toHaveKey(UserRepository::class)
        ->and($cachedData[UserRepository::class])->toBe([
            'concrete' => EloquentUserRepository::class,
            'shared' => true,
        ]);
});
