<?php

use Vhood\Laravel\AutowireTests\TestCase;

test('it successfully flushes compiled bindings via clear command', function () {
    /** @var TestCase $this */
    $cachePath = config('autowire.cache_path');

    if (! is_dir(dirname($cachePath))) {
        mkdir(dirname($cachePath), 0755, true);
    }
    file_put_contents($cachePath, '<?php return [];');
    expect(file_exists($cachePath))->toBeTrue();

    $this->artisan('autowire:clear')
        ->assertSuccessful()
        ->expectsOutput('Autowired bindings cache cleared successfully.');

    expect(file_exists($cachePath))->toBeFalse();
});

test('it outputs notice when clearing empty cache', function () {
    /** @var TestCase $this */
    $cachePath = config('autowire.cache_path');

    if (file_exists($cachePath)) {
        @unlink($cachePath);
    }

    $this->artisan('autowire:clear')
        ->assertSuccessful()
        ->expectsOutput('No cached autowire bindings found.');
});
