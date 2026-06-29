<?php

declare(strict_types=1);

namespace Vhood\Laravel\Autowire\Services;

use Composer\ClassMapGenerator\ClassMapGenerator;
use Illuminate\Contracts\Foundation\Application;
use ReflectionClass;
use Vhood\Laravel\Autowire\Attributes\Autowire;

class BinderService
{
    public function __construct(
        private Application $app,
        private array $config
    ) {}

    /**
     * Bind your scanned dependencies into the Laravel service container.
     */
    public function bind(): void
    {
        $shouldUseCache = $this->config['use_cache'] ?? $this->app->environment('production');

        if ($shouldUseCache && file_exists($this->config['cache_path'])) {
            $bindings = require $this->config['cache_path'];
            $this->registerFromMap($bindings);

            return;
        }

        $bindings = $this->scanDirectories();
        $this->registerFromMap($bindings);
    }

    /**
     * Scan configured directories for classes marked with the #[Autowire] attribute.
     */
    public function scanDirectories(): array
    {
        $bindings = [];
        $directories = $this->config['scan_directories'];

        $validDirs = array_filter($directories, 'is_dir');
        if (empty($validDirs)) {
            return [];
        }

        foreach ($validDirs as $directory) {
            $classMap = ClassMapGenerator::createMap($directory);

            foreach (array_keys($classMap) as $className) {
                $reflection = new ReflectionClass($className);
                if ($reflection->isInterface() || $reflection->isAbstract() || $reflection->isTrait()) {
                    continue;
                }

                $attributes = $reflection->getAttributes(Autowire::class);

                foreach ($attributes as $attribute) {
                    /** @var Autowire $instance */
                    $instance = $attribute->newInstance();

                    $bindings[$instance->abstract] = [
                        'concrete' => $className,
                        'shared' => $instance->shared,
                    ];
                }
            }
        }

        return $bindings;
    }

    /**
     * Register bindings inside the application container container.
     */
    private function registerFromMap(array $bindings): void
    {
        foreach ($bindings as $abstract => $params) {
            if ($params['shared']) {
                $this->app->singleton($abstract, $params['concrete']);
            } else {
                $this->app->bind($abstract, $params['concrete']);
            }
        }
    }
}
