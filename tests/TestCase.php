<?php

declare(strict_types=1);

namespace Vhood\Laravel\AutowireTests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Vhood\Laravel\Autowire\AutowireServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            AutowireServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('autowire.scan_directories', [
            __DIR__ . '/Fixtures',
        ]);

        $app['config']->set('autowire.cache_path', __DIR__ . '/tmp/autowire_bindings.php');
        $app['config']->set('autowire.use_cache', false);
    }

    protected function tearDown(): void
    {
        $cacheFile = __DIR__ . '/tmp/autowire_bindings.php';
        if (file_exists($cacheFile)) {
            @unlink($cacheFile);
        }

        parent::tearDown();
    }
}
