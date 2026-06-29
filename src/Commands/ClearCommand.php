<?php

declare(strict_types=1);

namespace Vhood\Laravel\Autowire\Commands;

use Illuminate\Console\Command;

class ClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autowire:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush the autowired DI bindings cache file';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $cachePath = config('autowire.cache_path');

        if (! file_exists($cachePath)) {
            $this->info('No cached autowire bindings found.');

            return self::SUCCESS;
        }

        if (@unlink($cachePath)) {
            $this->info('Autowired bindings cache cleared successfully.');

            return self::SUCCESS;
        }

        $this->error('Failed to delete the autowire cache file. Check permissions.');

        return self::FAILURE;
    }
}
