<?php

declare(strict_types=1);

namespace Vhood\Laravel\Autowire\Commands;

use Illuminate\Console\Command;
use Vhood\Laravel\Autowire\Services\BinderService;

class CacheCommand extends Command
{
    protected $signature = 'autowire:cache';

    protected $description = 'Pre-compile and cache all Autowire DI bindings';

    public function handle(BinderService $binder): int
    {
        $this->info('Scanning directories for Autowire attributes...');

        $bindings = $binder->scanDirectories();
        $cachePath = config('autowire.cache_path');

        $content = "<?php\n\nreturn " . var_export($bindings, true) . ";\n";
        file_put_contents($cachePath, $content);

        $this->info('Cached ' . count($bindings) . ' bindings successfully!');

        return self::SUCCESS;
    }
}
