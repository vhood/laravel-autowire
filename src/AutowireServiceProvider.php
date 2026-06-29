<?php

declare(strict_types=1);

namespace Vhood\Laravel\Autowire;

use Illuminate\Support\ServiceProvider;
use Vhood\Laravel\Autowire\Commands\CacheCommand;
use Vhood\Laravel\Autowire\Commands\ClearCommand;
use Vhood\Laravel\Autowire\Services\BinderService;

class AutowireServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/autowire.php',
            'autowire'
        );

        $this->app->singleton(BinderService::class, function ($app) {
            return new BinderService($app, $app['config']['autowire']);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /** @var BinderService $binder */
        $binder = $this->app->make(BinderService::class);
        $binder->bind();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/autowire.php' => config_path('autowire.php'),
            ], 'autowire-config');

            $this->commands([
                CacheCommand::class,
                ClearCommand::class,
            ]);
        }
    }
}
